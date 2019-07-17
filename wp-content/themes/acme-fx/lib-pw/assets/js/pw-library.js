( function( $ ) {  // No conflict implementation of JQuery in WordPress when enquequed in the footer

  $( document ).ready( function() {

    let button_group = document.getElementById( 'library-button-group' );

    if ( !button_group ) {
      return;
    }




    function button_on_click( button_click_event ) {

      // let button = button_click_event.target;
      // let button_ID = buton_click_event.target.id;
      // let button_classes = button.classList;
      // console.log( button_classes );





    }

    let buttons = button_group.querySelectorAll( 'button' );


    // Set up listeners
    buttons.forEach( function( button ) {

      button.addEventListener( 'click', button_on_click, true );

    } );


  } );

} )( jQuery );

