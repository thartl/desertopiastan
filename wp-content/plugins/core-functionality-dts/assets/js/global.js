/**
 * JS helpers
 *
 * @package
 * @since       1.0.0
 * @author      hartl
 * @link        https://parkdalewire.com
 * @license     GNU General Public License 2.0+
 */

let spoiler_href;


( function( $ ) {  // No conflict implementation of JQuery in WordPress when enquequed in the footer

  $( document ).ready( function() {


    // Attach alert to links that point to spoilers + save spoiler url to a global when clicked
    $( '.spoiler-alert' ).on( 'click', function( e ) {

      e.preventDefault();

      spoiler_href = $( this ).attr( 'href' );

      MicroModal.show( 'spoiler-alert' );

    } );


    // Proceed to spoiler url on click
    $( '#spoiler-continue' ).on( 'click', function( e ) {

      location = spoiler_href;

    } );


    // Initialize MicroModal
    MicroModal.init( {
      disableScroll: true, // [5]
      disableFocus: false, // [6]
      awaitCloseAnimation: false, // [7]
      debugMode: false, // [8]
    } );


  } );

} )( jQuery );

