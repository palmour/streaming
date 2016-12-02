var url_base = "www.cs.unc.edu/Courses/comp426-f16/users/palmour/final_project/backend";

$(document).ready(function(){
    $(".btn").click(function(){
        var inputEmail = $("#inputEmail").text();
        var inputPassword = $("#inputPassword").text();
        var data = ['username': inputEmail, 
                    'password' : inputPassword];
    });
    
    $.ajax(url_base + "/login.php", 
           {type: "GET",
            dataType: "json",
            data: data,
            success: function(return_data){
                if(return_data){
                    alert("login successful");
                }
                else {alert("returned false");}
            },
            error: function(){
                alert("login failed");
           }
        
    }
    
    
});