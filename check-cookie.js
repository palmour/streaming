$(document).ready(function(){
    $.ajax("backend/check-cookie.php",
    {type: "GET",
    dataType: "json",
    cache: false, 
    success: function(return_data){
        alert("success reached");
        for(var obj in return_data){
            alert(obj+": "+return_data[obj]);
        }
    },
    error: function(return_data){
        alert("error reached");
        for(var obj in return_data){
            alert(obj+": "+return_data);
        }
    }

    });
});