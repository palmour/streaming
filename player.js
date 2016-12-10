$(document).ready(function(){
    $("#playListContainer").audioControls({
        autoPlay : false,
        timer: 'increment',
        onAudioChange : function(response){
            $('.songPlay').text(response.title + ' ...');
        },
        onVolumeChange : function(vol){
            var obj = $('.volume');
            if(vol <= 0){
                obj.attr('class','volume mute');
            } else if(vol <= 33){
                obj.attr('class','volume volume1');
            } else if(vol > 33 && vol <= 66){
                obj.attr('class','volume volume2');
            } else if(vol > 66){
                obj.attr('class','volume volume3');
            } else {
                obj.attr('class','volume volume1');
            }
        }
    });
});