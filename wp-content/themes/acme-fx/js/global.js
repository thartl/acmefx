jQuery( function ( $ ) {
	// FitVids
	$('.entry').fitVids();

	// Sticky Header options
	var options = {
			offset: 850
	};

	// Initialize with options
	var header = new Headhesive('.nav-secondary', options);

});
