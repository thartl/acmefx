jQuery( function( $ ) {

  // FitVids
  $( '.entry-content' ).fitVids();


  // Sticky Header options
  var options = {
    offset: 720,
  };

  // Initialize Sticky Header with options
  if ( Headhesive ) {
    new Headhesive( '.nav-secondary', options );
  }


  // Sticky sidebar: make room for sticky header. Note: match scroll comparison to offset Sticky Header options (above)
  var scrollTicking = false;

  $( window ).on( 'resize scroll', function() {

    requestScrollTick();
  } );

  function requestScrollTick() {
    if ( !scrollTicking ) {

      scrollTicking = true;

      // Throttle to 20/second
      window.setTimeout( function() {

        requestAnimationFrame( positionSidebar );
      }, 50 );

    }
  }

  function positionSidebar() {

    var scroll = $( window ).scrollTop();

    if ( scroll > 720 ) {

      $( '.sidebar' ).addClass( 'sidebar-sticky-nav' );
    }
    else {

      $( '.sidebar' ).removeClass( 'sidebar-sticky-nav' );
    }

    scrollTicking = false;

  }


} );