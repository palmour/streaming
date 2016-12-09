$(document).ready(function(){
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
                contents = contents.concat('<tr><td>'+song['Title']+'</td><td>'+song['Artist']+'</td><td>'+song['Release']+'</td><td></td></tr>');
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
});