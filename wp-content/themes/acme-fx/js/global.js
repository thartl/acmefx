jQuery( function ( $ ) {

	// FitVids
	$('.entry-content').fitVids();

	
	// Sticky Header options
	var options = {
			offset: 725
	};
	// Initialize Sticky Header with options
	var header = new Headhesive('.nav-secondary', options);


	// Sticky sidebar: make room for sticky header. Note: match scroll comparison to offset Sticky Header options (above)
	$(window).scroll(function() {    
	    var scroll = $(window).scrollTop();
	    if (scroll > 725) {
	        $(".sidebar").addClass("sidebar-sticky-nav");
	    } else {
	        $(".sidebar").removeClass("sidebar-sticky-nav");
	    }

	});


});
