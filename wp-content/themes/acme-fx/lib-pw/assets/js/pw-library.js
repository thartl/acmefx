( function( $ ) {  // No conflict implementation of JQuery in WordPress when enquequed in the footer

  $( document ).ready( function() {


    let button_group = document.getElementById( 'library-button-group' );

    if ( !button_group ) {
      return;
    }

    let buttons = button_group.querySelectorAll( 'button' );
    /**
     * Set up listeners
     */
    buttons.forEach( function( button ) {

      button.addEventListener( 'click', button_on_click, true );

    } );


    // Change button states
    // TODO: change button states (and keep them)...


    // TODO: change "Showing xx results"


    // TODO: maybe have button_on_click() handle only firing other functions: change button state, change "showing xx results" line, change display of library items


    let docs_collection = document.querySelectorAll( 'ul.doc-listings li.library-item' );
    /**
     * Change library items' states when buttons are clicked
     * @param button_click_event
     */
    function button_on_click( button_click_event ) {

      let button_target = button_click_event.target;
      let button_docType = button_target.getAttribute( 'data-doc_type' );

      if ( 'all' === button_docType ) {

        // Show all
        docs_collection.forEach( function( doc ) {
          doc.classList.remove( 'hide_doc' );
        } );

      }
      else {

        // Show all selected Doc Types
        docs_collection.forEach( function( doc ) {
          if ( doc.classList.contains( button_docType ) ) {
            doc.classList.remove( 'hide_doc' );
          }
          else {
            doc.classList.add( 'hide_doc' );
          }

        } );

      }


    }


  } );

} )( jQuery );

