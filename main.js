var url_base = "https://wwwp.cs.unc.edu/Courses/comp426-f16/users/palmour/final_project/";
$(document).ready(function(){

    var $table = $(".table-responsive tbody"); $table.empty();
    var contents = "";

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
        var pathname = $(this).find('span.path').text();
        pathname = pathname.slice(3);
        pathname = url_base.concat(pathname);
        alert(pathname);
        var title = $(this).find('span.title').text();
        var artist = $(this).find('td.artist').text();
        /*soundManager.createSound({
                        id: title,
                        url: pathname
        });
        soundManager.play(title); */
        
        $('li.current-song a').attr('href', pathname);
        alert(pathname);
        $('li.current-song').find('span.info').html('<b>'+artist+'</b> - '+title); 
    });

    
    var lib_data = {};
    lib_data['action'] = 'getLibrary';
    $.ajax("backend/songs.php",
    {
        type: "POST", 
        dataType: "json",
        data: JSON.stringify(lib_data),
        success: function(return_data){
            alert('success');
            for(var obj in return_data){
                var song = return_data[obj];
                contents = contents.concat('<tr><td></td><td><span class="title">'+song['Title']+
                '</span><span class="hide songid">'+song['SongID']+'</span><span class="hide path">'+song['Pathname']+
                '</span></td><td class="artist">'+song['Artist']+'</td><td>'+song['Release']+'</td><td></td></tr>');
            }

            $table.html(contents);
        }, 
        error: function(return_data){
            alert("error");
            for(var obj in return_data){
                alert(obj+": "+return_data[obj]);
            }
        }
        
    });
});