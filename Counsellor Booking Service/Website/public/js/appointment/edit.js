function FormatTime(number) {
    if (number < 12)
        return number + ":00AM";
    else if (number == 12)
        return "12:00PM";
    else
        return (number - 12) + ":00PM";
}

function UpdateTimeslots(Date, ExistingTime = -1) {
    var url = "/appointments/getavailabletimeslots?CounsellorID=" + $("#txtCounsellor").val() + "&Date=" + Date;
    $.getJSON(url, function (result) {

        $("#selectTime").removeAttr("disabled");
        $("#selectTime").empty();
        $("#timeError").addClass("d-none");

        var existingTimeInserted = parseInt(ExistingTime) == -1;
        Object.keys(result).forEach(function (item) {
            var newTag = "";

            newTag = "<option value='" + result[item] + "'>" + FormatTime(result[item]) + "</option>";
            $("#selectTime").append(newTag);

            if (existingTimeInserted == false && parseInt(ExistingTime) > parseInt(result[item])) {
                newTag = "<option value='" + ExistingTime + "' selected>" + FormatTime(ExistingTime) + "</option>";
                existingTimeInserted = true;
                $("#selectTime").append(newTag);
            } 
        });
        if (existingTimeInserted == false) {
            var newTag = "<option value='" + ExistingTime + "' selected>" + FormatTime(ExistingTime) + "</option>";
            $("#selectTime").prepend(newTag);
        }

        $("#selectTime").attr("size", $("#selectTime").children().length);
    }).fail(function () {
        $("#selectTime").empty();
        if (parseInt(ExistingTime) != -1) {
            var newTag = "<option value='" + ExistingTime + "'>" + FormatTime(ExistingTime) + "</option>";
            $("#selectTime").append(newTag);
        }
        else {
            $("#selectTime").attr("disabled", "disabled");
            $("#timeError").removeClass("d-none");
        }
        $("#selectTime").attr("size", $("#selectTime").children().length);

    });
}

function appointmentDate_changed(sender) {
    UpdateTimeslots($(sender).val());
}

$(document).ready(function () {
    var date = new Date();
    $("#dateSelect").attr("min", new Date().toISOString().split('T')[0]);
    $("#dateSelect").val(new Date().toISOString().split('T')[0]);
    UpdateTimeslots($("#dateSelect").val(), $("#selectTime").attr("data-time"));
});