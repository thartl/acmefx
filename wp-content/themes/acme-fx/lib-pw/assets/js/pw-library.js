( function( $ ) {  // No conflict implementation of JQuery in WordPress when enquequed in the footer

  $( document ).ready( function() {

    let button_group = document.getElementById( 'library-button-group' );

    if ( !button_group ) {
      return;
    }


    let docs_collection = document.querySelectorAll( 'ul.doc-listings li.library-item' );


    function button_on_click( button_click_event ) {

      let button_target = button_click_event.target;
      let button_ID = button_target.id;
      let button_classList = button_target.classList;
      let button_docType = button_target.getAttribute( 'data-doc_type' );


      if ( 'lbg-all' === button_ID ) {

        docs_collection.forEach( function( doc ) {
          doc.classList.remove( 'hide_doc' );
        } );

      }
      else {

        docs_collection.forEach( function( doc ) {
          if ( doc.classList.contains( button_docType ) ) {
            doc.classList.remove( 'hide_doc' );
          } else {
            doc.classList.add( 'hide_doc' );
          }

        } );

      }


    }

    let buttons = button_group.querySelectorAll( 'button' );


    // Set up listeners
    buttons.forEach( function( button ) {

      button.addEventListener( 'click', button_on_click, true );

    } );


  } );

} )( jQuery );

