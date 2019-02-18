/*
 *  Shuffle children of a parent element
 */

( function( $ ) {


  $( document ).ready( function() {


    // Run on load
    shuffleChildren();

    // Or attach to a button...

    // $( '#shuffle-all' ).click( function() {
    //
    //   shuffleChildren();
    // } );


    function shuffleChildren() {

      let allShuffleParents = $( '.shuffle-children' );

      allShuffleParents.each( function() {

        let $parent = $( this );
        let $children = $parent.children();

        let shuffledChildren = shuffleArray( $children );

        $children.detach();
        shuffledChildren.appendTo( $parent );

      } );

    }


    // Ref: https://stackoverflow.com/questions/2450954/how-to-randomize-shuffle-a-javascript-array
    function shuffleArray( array ) {

      let currentIndex = array.length, temporaryValue, randomIndex;

      // While there remain elements to shuffle...
      while ( 0 !== currentIndex ) {

        // Pick a remaining element...
        randomIndex = Math.floor( Math.random() * currentIndex );
        currentIndex -= 1;

        // And swap it with the current element.
        temporaryValue = array[currentIndex];
        array[currentIndex] = array[randomIndex];
        array[randomIndex] = temporaryValue;
      }

      return array;

    }


  } );

} )( jQuery );