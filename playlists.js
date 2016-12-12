$(document).ready(function() {
    var playlist_data = {};
    playlist_data['action'] = 'getPlaylist';
    playlist_data['playlist'] = '';
    playlist_data['playlist_title'] = '';
    
    
    $.ajax("backend/songs.php",
        {
        type: "POST",
        dataType: "json",
        data: JSON.stringify(temp),
        success: function(return_data) {
            for(var obj in return_data) {
                
            }
        },
        error: function(return_data) {
            for(var obj in return_data) {
                
            }
        }
        
    }
    
    
});