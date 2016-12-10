$(document).ready(function(){
    var $song_table = $(".table-responsive tbody");
    $song_table.empty();

    $("#sign-out").click(function(){
        $.ajax("backend/logout.php", 
        {type: "GET",
            dataType: 'json',
            cache: false,
            success: function(){
                window.location.assign('signin.html');
            }, 
            error: function(){
                alert('logout failed');
            }

        });
    });

    $.ajax("backend/check-cookie.php",
    {type: "GET",
        dataType: "json",
        cache: false, 
        success: function(return_data){
            alert("success reached");
            for(var obj in return_data){
                alert(obj+": "+return_data[obj]);
            }

            var un = return_data['username'];
            if((un===undefined)||(un==null)){$(".page-header").text("Not logged in.");}
            else{$(".page-header").text(un+"\'s Library");}
        },
        error: function(return_data){
            alert("error reached");
            for(var obj in return_data){
                alert(obj+": "+return_data);
            }
        }

    });

    $('.table tbody').on('click', 'tr', function(){
        //$('div.current-song a').attr('href', )
    });
    
});