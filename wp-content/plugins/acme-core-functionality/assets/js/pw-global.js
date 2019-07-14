/**
 * JS helpers
 *
 * @package
 * @since       1.0.0
 * @author      thartl
 * @link        https://parkdalewire.com
 * @license     GNU General Public License 2.0+
 */


( function( $ ) {  // No conflict implementation of JQuery in WordPress when enquequed in the footer

  $( document ).ready( function() {


    window.alert( 'pw-global.js has benn enqueued.' );


  } );

} )( jQuery );
