$(document).ready(function(){    
    
    var $library_tab = $("ul.nav-sidebar li.active");

    var send_data = {};
    send_data['action'] = 'getMaster';
    
    var $table = $(".table-responsive tbody");
    var contents = "";
    $.ajax("backend/songs.php",
    {
        type: "POST",
        dataType: "json",
        data: JSON.stringify(send_data),
        success: function(return_data){

            if(return_data['status']!=undefined){
             return;
            }
            for(var obj in return_data){
                var song = return_data[obj];
                contents = contents.concat('<tr><td></td><td>'+song['Title']+'<span class="hide songid">'+song['SongID']+
                '</span></td><td>'+song['Artist']+'</td><td>'+song['Release']+'</td><td class="add"><img src="icon_plus_big.png" width="30" height="30" class="img-responsive" alt="Generic placeholder thumbnail"></td></tr>');
            }   

            $table.html(contents);
        }, 
        error: function(return_data){
            alert("error loading songs");
        }

    });

    $.ajax("backend/check-cookie.php",
    {type: "GET",
    dataType: "json",
    cache: false, 
    success: function(return_data){

        var un = return_data['username'];
        if((un===undefined)||(un==null)){$(".username-header").text("Not logged in.");}
        else{$(".username-header").text("Logged in as "+un);}

        
        $library_tab.children("a").text(un+"\'s Library");
    },
    error: function(return_data){
        alert("error reached");
    }

    });

    $(".table tbody").on("click", "td.add", function(){
        var send_data2= {};
        send_data2['action'] = "addToLibrary";
        send_data2['songid'] = $(this).parent().find('span.songid').text();
        
        $.ajax("backend/songs.php", 
        {
            type: "POST",
            dataType: "json",
            data: JSON.stringify(send_data2),
            success: function(return_data){
            },
            error: function(return_data){
                alert("error");
            }
        });
    });
    
   

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

    $library_tab.click(function(){
        window.location.assign("main.html");
    });
    
    $("#search").keyup(function(){
        contents = "";
        var searched = $("#search").val();
        var length = searched.length;
        //alert(""+searched+","+length);
         $.ajax("backend/songs.php",
    {
        type: "POST",
        dataType: "json",
        data: JSON.stringify(send_data),
        success: function(return_data){
        
            if(return_data['status']!=undefined){
                return;
            }
            for(var obj in return_data){
                var song = return_data[obj];
                var result = "";
                for (var i=0; i<length; i++){
                    result = result.concat(song['Title'][i]);
                }
                if (searched == result){
                contents = contents.concat('<tr><td></td><td>'+song['Title']+'<span class="hide songid">'+song['SongID']+
                '</span></td><td>'+song['Artist']+'</td><td>'+song['Release']+'</td><td class="add"><img src="icon_plus_big.png" width="30" height="30" class="img-responsive" alt="Generic placeholder thumbnail"></td></tr>');
                }
            }   

            $table.html(contents);
        }, 
        error: function(return_data){
            for(var obj in return_data){
                
            }
            alert("error loading songs");
        }

    });
    });

});