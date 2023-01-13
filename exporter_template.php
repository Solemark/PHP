<?php
namespace AlayaCare\Appbundle\Exporters\Payroll;  //The directory I'm working in e.g: alayacare\exporters\payrollexporters

use AccEmployeePayItem;
use Carbon\Carbon;
use DateUtil;
use FormatterFactory;
use ScheduleItem;
use GuidPremium;
use Premium;
use ScheduleRrule;
use PayrollExporter;
use TraitExporterHelper;

class ClassName extends PayrollExporter{    //extends whatever exporter type i'm targetting Payroll, Accounting etc
    use TraitExporterHelper;
    const HARDCODED_VALUE   = 'hardcoded value';
    const VISIT_HOLIDAY     = 'Visit on holiday';
    const VISIT_REG         = 'Visit Regular';
    const VISIT_OVERTIME    = 'Visit Overtime';
    const TIME_OFF          = 'Unavailable/On Holiday/Time Off';
    const DATE_FORMAT       = 'd/m/y';
    const MERGE_COLUMNS     = ['employee_id', 'rate'];

    public function getContent(){   //gets data for export
        $rows = [];
        $items = $this->get_accounting_items(); //using the AccEmployeePayItem.php class

        foreach ($items as $item){
            switch($item->type){    //if you dont need certain types of data just remove the relevant cases here
                case AccEmployeePayItem::TYPE_REGULAR:
                    $rows = array_merge($rows, $this->getRowVisit($item, $item->shift)); //use array_merge because more then 1 row could be returned
                    break;
                case AccEmployeePayItem::TYPE_DAILY_PREMIUM:
                case AccEmployeePayItem::TYPE_SHIFT_PREMIUM:
                    $rows[] = $this->getRowGuidPremium($item, $item->guid_premium);
                    break;
                case AccEmployeePayItem::TYPE_CALCULATED_PREMIUM:
                    $rows[] = $this->getRowPremium($item, $item->premium);
                    break;
                case AccEmployeePayItem::TYPE_TIME_OFF:
                    $rows[] = $this->getRowAvailability($item, $item->schedule_rrule);
                    break;
            }
        }

        $this->formatter->setHeader($this->getHeaders());
        $rows = $this->mergeRows($rows, self::MERGE_COLUMNS, [$this, 'mergeGroupedColumns']);   //uses another class to merge columns with identical keys
        return $rows
    }

    public function mergeGroupedColumns($mergedRow, $row){  //the callback function used by mergeRows() to do the merging
        $mergedRow['quantity'] += $row['quantity'];
        return $mergedRow;
    }

    public function getHeaders(){   //This function defines export table headers
        return [
            'item_type'         => 'Item Type',
            'employee_id'       => 'Employee ID',
            'employee_name'     => 'Employee Name',
            'date'              => 'Date',
            'quantity'          => 'Quantity/Hrs Worked',
            'rate'              => 'Employee Rate',
            'hardcoded_value'   => 'Hardcoded Value', //created a const for this value at the top of the class
        ];
    }
    public function getDefaultFormatter(){  //the default formatter (Top of the list)
        return FormatterFactory::CSV;
    }
    public function getValidFormatters(){   //provide the entire list of valid formatters
        return[
            FormatterFactory::CSV,
            FormatterFactory::TSV,
        ];
    }

    public function getBaseRow($item){  //use this to set default values
        $employeeFromFrozen = $this->getEmployeeFromFrozen($item);  //retrieving frozen data, using the TraitShiftEncoded.php class and the get___FromFrozen functions
        return[
            'item_type' => '',
            'employee_id' => $item->idemployee,
            'employee_name' => implode(', ', [  //using frozen data
                $employeeFromFrozen->first_name,
                $employeeFromFrozen->last_name,
            ]),
            'date' => '',  
            'quantity' => '',
            'rate' => '',
            'hardcoded_value' => self::HARDCODED_VALUE,
        ];
    }

    public function getRowVisit(AccEmployeePayItem $item, ScheduleItem $visit){
        $rows = [];
        $clientFromFrozen = $this->getClientFromFrozen($item);  //retrieving frozen data
        $visitFromFrozen = $this->getVisitFromFrozen($item);
        $visitDate = DateUtil::createFromTimestamp($visitFromFrozen->start_time, $clientFromFrozen->timezone)->format(self::DATE_FORMAT);   //pass in starttime(timestamp) and timezone to get local starttime then format it as required

        if($item -> isHoliday()){ //the isHoliday() function is defined under the AccEmployeePayItem.php class
            $row = $this->getBaseRow($item);
            $employeeFromFrozen = $this->getEmployeeFromFrozen($item);  //this comes from TraitShiftEncoded.php
            $row['item_type'] = self::VISIT_HOLIDAY;   
            $row['date'] = $visitDate;             
            $row['quantity'] = $item->quantity_holiday; //accounting items comes from the pay item not frozen data
            $row['rate'] = $item->rate_holiday;         //accounting items comes from the pay item not frozen data

            $rows[] = $row; //add the row into the array
        }else{
            if($this->hasQuantityReg($item)){
                $row = $this->getBaseRow($item);    //Add a row if the item has a valid regular quantity
            
                $row['item_type'] = self::VISIT_REG;
                $row['date'] = $visitDate; 
                $row['quantity'] = $item->quantity_reg; //accounting items comes from the pay item not frozen data
                $row['rate'] = $item->rate;             //accounting items comes from the pay item not frozen data

                $rows[] = $row; //add the row into the array
            }
            if($this->hasQuantityOt($item)){
                $row = $this->getBaseRow($item);    //Add a row if the item has a valid overtime quantity
            
                $row['item_type'] = self::VISIT_OT;
                $row['date'] = $visitDate; 
                $row['quantity'] = $item->quantity_overtime; //accounting items comes from the pay item not frozen data
                $row['rate'] = $item->rate_overtime;         //accounting items comes from the pay item not frozen data

                $rows[] = $row; //add the row into the array
            }
        }
        return $rows;
    }
    public function getRowGuidPremium(AccEmployeePayItem $item, GuidPremium $guid_premium){
        $row = $this->getBaseRow($item);

        $date = '';
        if($item->type == AccEmployeePayItem::TYPE_DAILY_PREMIUM){
            $frozenData = $this->getShiftDecoded($item);    //getShiftDecoded returns all shift date data
            $applyDate = $frozenData->apply_date;
            $date = new Carbon($applyDate)->format(self::DATE_FORMAT);
        }

        $row['item_type'] = $item->type == AccEmployeePayItem::TYPE_SHIFT_PREMIUM ? self::VISIT_PREMIUM : self::DAILY_PREMIUM;
        $row['date'] = $date;
        $row['quantity'] = $item->quantity_reg; //Overtime and holiday are only used for visits, use reg in all other places
        $row['rate'] = $item->rate;             //Overtime and holiday are only used for visits, use reg in all other places
        return $row;
    }
    public function getRowPremium(AccEmployeePayItem $item, Premium $premium){
        $row = $this->getBaseRow($item);
        $row['quantity'] = $item->quantity_reg; //Overtime and holiday are only used for visits, use reg in all other places
        $row['rate'] = $item->rate;             //Overtime and holiday are only used for visits, use reg in all other places
        $row['item_type'] = self::CALCULATED_PREMIUM;
        return $row;
    }
    public function getRowAvailability(AccEmployeePayItem $item, ScheduleRrule $unavailability){
        $row = $this->getBaseRow($item);
        $row['item_type'] = self::TIME_OFF;
        $row['quantity'] = $item->quantity_reg; //Overtime and holiday are only used for visits, use reg in all other places
        $row['rate'] = $item->rate;             //Overtime and holiday are only used for visits, use reg in all other places
        return $row;
    }
}
//This comes from Payroll Example (Training Session Video 1)
//Need to add this code to the ExporterFactory.php
/*
SELECT *
FROM tbl_exporters_list tel
for the client's entire list of available exports 
