(function($){

	$.fn.youTubeEmbed = function(settings){

		// Settings can be either a URL string,
		// or an object

		if(typeof settings == 'string'){
			settings = {'video' : settings}
		}

		// Default values

		var def = {
			width		: 640,
			progressBar	: true
		};

		settings = $.extend(def,settings);

		var elements = {
			originalDIV	: this,	// The "this" of the plugin
			container	: null,	// A container div, inserted by the plugin
			player		: null,	// The flash player
		};


		try{

			settings.videoID = settings.video.match(/v=(.{11})/)[1];

			// The safeID is a stripped version of the
			// videoID, ready for use as a function name

			settings.safeID = settings.videoID.replace(/[^a-z0-9]/ig,'');

		} catch (e){
			// If the url was invalid, just return the "this"
			return elements.originalDIV;
		}

		// Fetch data about the video from YouTube's API

		var youtubeAPI = 'http://gdata.youtube.com/feeds/api/videos?v=2&alt=jsonc';

		$.get(youtubeAPI,{'q':settings.videoID}, function(response){

			var data = response.data;

			if(!data.totalItems || data.items[0].accessControl.embed!="allowed"){

				// If the video was not found, or embedding is not allowed;

				return elements.originalDIV;
			}

			// data holds API info about the video:

			data = data.items[0];
			console.log(data);

			settings.ratio = 3/4;
			if(data.aspectRatio == "widescreen"){
				settings.ratio = 9/16;
			}

			settings.height = Math.round(settings.width*settings.ratio);

			// Creating a container inside the original div, which will
			// hold the object/embed code of the video

			elements.container = $('<div>',{class:'flashContainer',css:{
				width	: settings.width,
				height	: settings.height
			}}).appendTo(elements.originalDIV);

			elements.player = $('<div>',{id:'myPlayer'}).appendTo(elements.container);

			// Embedding the YouTube chromeless player
			// and loading the video inside it:

			// The video to load.
			var videoID = settings.videoID;
			// Lets Flash from another domain call JavaScript
			var params = {
				allowScriptAccess: "always",
				allowFullScreen: (settings.fullscreen) ? true : false
			};
			// The element id of the Flash embed
			// http://www.youtube.com/watch?v=u1zgFlCw8Aw
			var atts = { id: "ytPlayer" };
			// All of the magic handled by SWFObject (http://code.google.com/p/swfobject/)
			swfobject.embedSWF("https://www.youtube.com/v/" + videoID + "?showinfo=0&version=3&enablejsapi=1&playerapiid=player1", 'myPlayer', settings.width, settings.height, "9", null, null, params, atts, function callbackFn(e) {
				console.log(e);
			});

		},'jsonp');

		return elements.originalDIV;
	}

})(jQuery);