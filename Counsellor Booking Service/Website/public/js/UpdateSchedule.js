function timeslotCell_MouseUp(sender) {
    SwapState(sender, true)
}

function checkbox_MouseUp(event) {
    SwapState($(event.target).parent, false);
    event.stopPropagation();
}

function BuildScheduleString(Schedule) {
    dayStrings = [];
    for (var day = 0; day < 5; day++) {
        dayStrings.push(Schedule[day].join(","));
    }
    return dayStrings.join("/");
}

function SwapState(Cell, SwapCheckboxState = true) {
    if ($(Cell).children("input").prop("checked") == true) {
        if (SwapCheckboxState == true) {
            $(Cell).children("input").prop("checked", false);
        }
        $(Cell).removeClass("timeslotAvailable");

    }
    else {
        if (SwapCheckboxState == true) {
            $(Cell).children("input").prop("checked", true);
        }
        $(Cell).addClass("timeslotAvailable");

    }
}

function Submit_Clicked() {
    var payload = {};
    var schedule = [[], [], [], [], []];

    $(".timeCheckbox").each(function (index, cell) {
        if ($(cell).prop("checked") == false)
            return;

        var dayIndex = $(cell).parent().attr("data-dayIndex");
        var hour = $(cell).parent().attr("data-hour");
        schedule[dayIndex].push(hour);
    });
    var scheduleString = BuildScheduleString(schedule);
    $("#txtSchedule").val(scheduleString);
    $("#dateStartHidden").val($("#DateStart").val());
    $("#dateEndHidden").val($("#DateEnd").val());
}

function DisplayExistingValues(ScheduleString) {
    var daySplit = ScheduleString.split("/");
    for (i = 0; i < 5; i++) {
        var hourArray = daySplit[i].split(",");
        hourArray.forEach(function (hour) {
            if (hour == "")
                return;
            var selector = ".timeslot[data-dayindex=" + i + "][data-hour=" + hour + "]";
            var cell = $(selector);
            SwapState(cell, true);
        });

    }
}

function ValidateDates(){
    var date = new Date($("#DateStart").val());
    if (validateDate(date,1) == false) {
        $("#StartDateErrorMessage").removeClass("d-none");
        $("#DateStart").addClass("border-danger");
    }
    else{
        $("#StartDateErrorMessage").addClass("d-none");
        $("#DateStart").removeClass("border-danger");        
    }
     date = new Date($("#DateEnd").val());
    if (validateDate(date,5) == false) {
        $("#EndDateErrorMessage").removeClass("d-none");
        $("#DateEnd").addClass("border-danger");   
    }
    else{
        $("#EndDateErrorMessage").addClass("d-none");
        $("#DateEnd").removeClass("border-danger");        
    }

    if($("#StartDateErrorMessage").hasClass("d-none") == false|| $("#EndDateErrorMessage").hasClass("d-none") == false){
        $("#FormSubmit").attr("disabled","").css("cursor","no-drop");
    }
    else{
        $("#FormSubmit").removeAttr("disabled").css("cursor","");
    }
}

function startDate_valueChanged(event) {
    ValidateDates();
}

function endDate_valueChanged(event) {
    ValidateDates();    
}


function validateDate(date, expectedDayOfWeek) {
    if (date.getDay() == expectedDayOfWeek)
        return true;
    return false;
}

$(document).ready(function () {
    var scheduleString = $("#txtSchedule").val();
    DisplayExistingValues(scheduleString);
    ValidateDates();    
});

