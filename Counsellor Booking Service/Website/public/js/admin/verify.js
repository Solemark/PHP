function approve_clicked(sender){
    $.post("/admin/verify",{"id": $(sender).attr("data-id")}).done(function(){
        window.location.reload();
    });
}

function deny_clicked(sender){
    $.post("/admin/deny",{"id": $(sender).attr("data-id")}).done(function(){
        window.location.reload();
    });
}