$(document).ready(function () {
    var table = $('#masterTable');
    
    var send_data = {};
    send_data['action'] = 'getMaster';
    
    var $table = $(".table-responsive-master tbody");
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
                '</span></td><td>'+song['Artist']+'</td><td>'+song['Release']+'</td><td class="add"><img src="icon_plus_big.png" width="30" height="30" class="img-responsive" alt="Generic placeholder thumbnail"></td></tr>');
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
    
    
    $(".table tbody").on("click", "td.add", function(){
        
        var $parent = $(this).parent().html();
        alert($parent);
        
        //remove (+)sign image
        var n = $parent.indexOf("<td class=\"add");
        var $new = $parent.substring(0,n);
        
        $('#playlistTable tbody').append("<tr>"+$new+"</tr>");
        
        

    });  
    
    
    
    $("#save").on("click", function() {
    
        var temp ={};
        temp['action'] = 'createPlaylist';
        temp['playlist_title'] = $('#playlist_input').val();
        alert(temp['playlist_title']);
            
        $.ajax("backend/songs.php",{
            type: "POST",
            dataType: 'json',
            data: JSON.stringify(temp),
            success: function(return_data) {
                alert('success');
            },
            error: function(return_data) {
                alert('error');
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
});