var ply = videojs("video");

var isViewed = false

ply.on('timeupdate',function(){

    var percentage = Math.ceil((ply.currentTime()/ply.duration())*100)

    if(percentage > 5 && !isViewed )
    {
        axios.put('/movies/'+window.movieId+'/views')
        isViewed = true
    }
})

