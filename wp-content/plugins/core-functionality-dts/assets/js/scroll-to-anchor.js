/**
 *
 * Requires pw-jump.js
 *
 * Reference: BCI Foods
 *
 */


( function( $ ) {

  $( document ).ready( function() {

    let duration = 1100;


    // Smooth-scroll to anchor after load, if url has a hash
    if ( location.hash ) {

      let anchor = location.hash;
      anchor = anchor.length ? anchor : $( '[name=' + this.hash.slice( 1 ) + ']' );

      // If anchor exists on this page...
      if ( anchor.length && '#login' != anchor ) {

        // Stop document from scrolling after load (timeout for compatibility)
        setTimeout( function() {

          window.scrollTo( 0, 0 );
        }, 1 );

        scrollToAnchor( anchor, 720 );
console.log( 'External request smooth scroll fired' );
console.log( anchor );
      }

    }


    // Smooth-scroll on click to anchor on the same page
    $( 'a[href*="#"]:not([href="#"]):not([href^="#tab"]):not([href*="#login"])' ).click( function( e ) {
console.log( 'Smooth scroll fired' );
      if ( location.pathname.replace( /^\//, '' ) == this.pathname.replace( /^\//, '' )
          && location.hostname == this.hostname ) {

        e.preventDefault();
        e.stopPropagation();

        let $this = $( this );

        // Remove '.current' from siblings and apply to clicked link (if the link is of Menu or Catering subnav)
        if ( $this.parents( '.display-posts-listing' ) ) {

          $this.parent().siblings().find( 'a' ).removeClass( 'current' );
          $this.addClass( 'current' );

        }

        // Add hash to address bar URL, after scroll finishes
        let hashOnly = this.hash;
        let thisID = $( hashOnly );
        let target = thisID.length ? hashOnly : $( '[name=' + this.hash.slice( 1 ) + ']' );

        setTimeout( function() {

          // Add hash to url
          let stateObj = {
            method: "anchor",
          };

          history.pushState( stateObj, target, target );

        }, ( duration * 1.1 ) );


        // Smooth scroll to anchor (or to end of page)
        if ( target.length ) {

          scrollToAnchor( target, 60 );
        }

      }

    } );


    function scrollToAnchor( anchor, delay ) {

      // Smooth-scroll to target
      setTimeout( function() {

        jump( anchor, {
          duration: duration,
          offset: scrollOffset(),
        } );
      }, delay );

    }


    // Cache header and subnav nodes
    let $header = $( '#masthead > .navbar' );
    let $subnav = $( 'ul#menu-food-menu, ul#menu-catering-menu, ul#menu-contact-menu' );


    function scrollOffset() {

      let header_height = $header.outerHeight( true );
      let subnav_height = $subnav.outerHeight( true );
      let headerSection_height = 1 - header_height - subnav_height;

      return headerSection_height;

    }


  } );

} )( jQuery );

