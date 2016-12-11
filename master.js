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
                alert(return_data['status']); return;
            }
            for(var obj in return_data){
                var song = return_data[obj];
                contents = contents.concat('<tr><td></td><td>'+song['Title']+'<span class="hide songid">'+song['SongID']+
                '</span></td><td>'+song['Artist']+'</td><td>'+song['Release']+'</td><td class="add">Add</td></tr>');
            }   

            $table.html(contents);
        }, 
        error: function(return_data){
            for(var obj in return_data){
                alert(obj+": "+return_data[obj]);
            }
            alert("error loading songs");
        }

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
        if((un===undefined)||(un==null)){$(".username-header").text("Not logged in.");}
        else{$(".username-header").text("Logged in as "+un);}

        alert($library_tab.children("a").text());
        $library_tab.children("a").text(un+"\'s Library");
    },
    error: function(return_data){
        alert("error reached");
        for(var obj in return_data){
            alert(obj+": "+return_data);
        }
    }

    });

    $(".table tbody").on("click", "td.add", function(){
        var send_data2= {};
        send_data2['action'] = "addToLibrary";
        send_data2['songid'] = $(this).parent().find('span.songid').text();
        alert(send_data2['songid']);
        $.ajax("backend/songs.php", 
        {
            type: "POST",
            dataType: "json",
            data: JSON.stringify(send_data2),
            success: function(return_data){
                alert("success");
                for(var obj in return_data){
                    alert(obj+": "+return_data[obj]);
                }
            },
            error: function(return_data){
                alert("error");
                for(var obj in return_data){
                    alert(obj+": "+return_data[obj]);
                }
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
    

});