var url_base = "wwwp.cs.unc.edu/Courses/comp426-f16/users/palmour/final_project/backend/";

$(document).ready(function(){
    
    $(".btn").click(function(e){
        e.stopPropagation();
        e.preventDefault();
        var username = $("#inputEmail").val(); 
        var password = $("#inputPassword").val(); 
        var data = {'username': inputEmail, 'password': inputPassword};

        alert("before ajax");
        $.ajax("backend/login.php?"+"username="+username+"&password="+password, 
            {type: "GET",
                dataType: "json",
                cache: false,
                success: function(return_data){
                    alert("reached success")
                    for(var obj in return_data){
                        alert(obj+": "+return_data[obj]);
                    }
                    window.location.assign('main.html');

                },
            error: function(return_data){
                alert("reached error");
                for(var obj in return_data['responseJSON']){
                    alert(obj+": "+return_data['responseJSON'][obj]);
                }
           } 
        });  
    });
});