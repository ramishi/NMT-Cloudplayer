$(function() { 
        // Setup the player to autoplay the next track
        var a = audiojs.createAll({createPlayer: {
            markup: false,
            playPauseClass: 'play-pauseZ',
            scrubberClass: 'scrubberZ',
            progressClass: 'progressZ',
            loaderClass: 'loadedZ',
            timeClass: 'timeZ',
            durationClass: 'durationZ',
            playedClass: 'playedZ',
            errorMessageClass: 'error-messageZ',
            playingClass: 'playingZ',
            loadingClass: 'loadingZ',
            errorClass: 'errorZ'
          },
        
          trackEnded: function() {
            var next = $('ul li.playing').next();
            if (!next.length) next = $('ul li.music').first();
            next.addClass('playing').siblings().removeClass('playing');
            audio.load($('a', next).attr('data-src'));
            audio.play();
          }
        });
        
		// volume control
		  var vol = 0.6;
		  barw = (vol * 100)+"%";
		  $('.bar').css('width',barw );
			   
           $('.volup').click(function(e) {
			   if (vol < 1) {
			   vol = vol + 0.2;
			   barw = (vol * 100)+"%";
			   $('.bar').css('width',barw );
            audio.setVolume(vol); 
			
			//alert (vol);
			};
           e.preventDefault();

        });
		$('.voldn').click(function(e) {
			   if (vol > 0) {
			   vol = vol.toFixed(1) - 0.2;
			    barw = (vol * 100)+"%";
			   if (vol <= 0) {
			   $('.bar').css('width',0 );
		   } else {
			   $('.bar').css('width',barw );};
            audio.setVolume(vol); 
			//alert (vol);
			};
	
           e.preventDefault();


        });
		
	
		
        // Load in the first track
       var audio = a[0];
            first = $('li.music a').attr('data-src');
        $('ul li.music').first().addClass('playing');
        audio.load(first);

        // Load in a track on click
        $('ul li.music').click(function(e) {
          e.preventDefault();
          $(this).addClass('playing').siblings().removeClass('playing');
          audio.load($('a', this).attr('data-src'));
          audio.play();
        });
		 $('.next').click(function(e) {
          var next = $('ul li.playing').next();
            if (!next.length) next = $('ul li.music').first();
            next.click();
        });
		 $('.prev').click(function(e) {
		 var prev = $('ul li.playing').prev();
            if (!prev.length) prev = $('ul li.music').last();
            prev.click();
			 });
      });
	  $(document).ready(function(e) {
        $('#playlist li:nth-child(odd)').addClass('alt');
		if ($('.music').length) {
			//alert('music found');
			$('#infopanel').show();
			$('#control').show();
			$('.content').addClass('rpanel');
		};
		
		


    });