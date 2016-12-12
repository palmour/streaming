$(document).ready(function() {
    var playlist_data = {};
    playlist_data['action'] = 'getPlaylist';
    playlist_data['playlist'] = '';
    playlist_data['playlist_title'] = '';
    
    
    $.ajax("backend/songs.php",
        {
        type: "POST",
        dataType: "json",
        data: JSON.stringify(playlist_data),
        success: function(return_data) {
            for(var obj in return_data) {
                var song = return_data[obj];
                contents = contents.concat('<tr><td></td><td><span class="title">'+song['Title']+
                '</span><span class="hide songid">'+song['SongID']+'</span><span class="hide path">'+song['Pathname']+
                '</span></td><td class="artist">'+song['Artist']+'</td><td>'+song['Release']+'</td><td></td></tr>');
            }
        },
        error: function(return_data) {
            for(var obj in return_data) {
                alert(obj+": "+return_data[obj]); 
            }
        }
        
    }

    
});