var url_base = "wwwp.cs.unc.edu/Courses/comp426-f16/users/palmour/final_project/backend/";

$(document).ready(function(){
    
    $(".btn").click(function(e){
        e.stopPropagation();
        e.preventDefault();
        var username = $("#inputEmail").val(); 
        var password = $("#inputPassword").val(); 
        var password1 = $("#inputPassword1").val();
        var data = {'username': inputEmail, 'password': inputPassword};

        if((username.length==0)&&(password.length==0)){
            $("p.red").text("Please enter a username and password");
            return;
        }

        if(username.length==0){
            $("p.red").text("Please enter a username"); return;
        }

        if(password.length==0){
            $("p.red").text("Please enter a password"); return;
        }

        if(password.length<5){
            $("p.red").text("Password should be at least 5 characters long."); return;
        }


        //window.location.assign('main.html');

        /*$.ajax("login.php?"+"username="+username+"&password="+password, 
            {type: "GET",
                dataType: "json",
                cache: false,
                success: function(return_data){
                    alert("reached success")
                    if(return_data){alert("login successful");}
                    else {alert("returned false");}
                },
            error: function(){
                alert("login failed");
           } 
        }); */
        if (password1 == password){

        $.ajax("backend/register.php?username="+username+"&password="+password,
        {type: "GET", 
            datatype: "json",
            cache: false,
            success: function(return_data){
                    window.location.assign('main.html');
            },
            error: function(return_data){
                alert("reached error");
            }

        });
        }else{alert("Passwords do not match.");}
        }
    });
});