jQuery( function ( $ ) {
	// FitVids
	$('.entry').fitVids();

	// Sticky Header options
	var options = {
			offset: 830
	};

	// Initialize with options
	var header = new Headhesive('.nav-secondary', options);
	var header = new Headhesive('.header-widget-area', 'offset: 820');

});
