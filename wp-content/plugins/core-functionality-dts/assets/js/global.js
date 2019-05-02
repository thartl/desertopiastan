/**
 * JS helpers
 *
 * @package
 * @since       1.0.0
 * @author      hartl
 * @link        https://parkdalewire.com
 * @license     GNU General Public License 2.0+
 */


( function( $ ) {  // No conflict implementation of JQuery in WordPress when enquequed in the footer

  $( document ).ready( function() {


    let spoiler_href;

    // Attach alert to links that point to spoilers + save spoiler url
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


    // init Isotope (wait for grid to render, then init)

      var $grid = $( '.species-grid' ).isotope( {
        itemSelector: '.species-item',
        getSortData: {
          name: '.title',
          elevation: '.elevation',
        },
      } );

      // Isotope filter functions
      var filterFns = {

        // show if number is greater than 50
        numberGreaterThan50: function() {
          var number = $( this ).find( '.number' ).text();
          return parseInt( number, 10 ) > 50;
        },

        // show if name ends with -ium
        ium: function() {
          var name = $( this ).find( '.name' ).text();
          return name.match( /ium$/ );
        },
      };

      // bind filter button click
      $( '.filters-button-group' ).on( 'click', 'button', function() {
        var filterValue = $( this ).attr( 'data-filter' );

        // use filterFn if matches value
        filterValue = filterFns[filterValue] || filterValue;
        $grid.isotope( { filter: filterValue } );
      } );

      // bind sort button click
      $( '.sort-by-button-group' ).on( 'click', 'button', function() {
        var sortValue = $( this ).attr( 'data-sort-value' );
        $grid.isotope( { sortBy: sortValue } );
      } );


      // change is-checked class on buttons
      $( '.button-group' ).each( function( i, buttonGroup ) {
        var $buttonGroup = $( buttonGroup );
        $buttonGroup.on( 'click', 'button', function() {
          $buttonGroup.find( '.is-checked' ).removeClass( 'is-checked' );
          $( this ).addClass( 'is-checked' );
        } );
      } );

  } );

} )( jQuery );

