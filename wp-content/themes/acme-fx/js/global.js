jQuery( function ( $ ) {
	// FitVids
	$('.entry').fitVids();

	// Sticky Header options
	var options = {
			offset: 860
	};

	// Initialize with options
	var header = new Headhesive('.nav-secondary', options);
	var header = new Headhesive('.header-widget-area', 'offset: 920');

});
