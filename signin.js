var url_base = "www.cs.unc.edu/Courses/comp426-f16/users/palmour/final_project/backend";

$(document).ready(function(){
    $(".btn").on("submit", function(e){
        e.stopPropagation();
        e.preventDefault();
        var inputEmail = $("#inputEmail").text();
        var inputPassword = $("#inputPassword").text();
        var data = {'username': inputEmail, 'password': inputPassword};

        //window.location.assign('main.html');

        alert("before ajax");
        $.ajax(url_base + "/login.php", 
            {type: "GET",
                dataType: "json",
                data: $(".form-signin").serialize(),
                cache: false,
                success: function(return_data){
                    alert("reached success")
                    if(return_data){alert("login successful");}
                    else {alert("returned false");}
                },
            error: function(){
                alert("login failed");
           } 
        }); 
    });
});