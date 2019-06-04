/**
 * Set up sticky sidebar etc.
 *
 * @package
 * @since       1.0.0
 * @author      hartl
 * @link        https://parkdalewire.com
 * @license     GNU General Public License 2.0+
 */

( function( $ ) {


  $( document ).ready( function() {


    let sidebar = document.getElementById( 'genesis-sidebar-primary' );

    if ( !sidebar ) {
      return;
    }

    let documentHeight = document.body.clientHeight;
    let windowHeight = window.innerHeight;

    let mainContent = document.getElementById( 'genesis-content' );

    let footer = document.getElementsByClassName( 'site-footer' )[0];
    let footerHeight = footer.clientHeight;

    let scrollTicking = false;


    window.addEventListener( 'resize', function() {

      documentHeight = document.body.clientHeight;
      windowHeight = window.innerHeight;
      footerHeight = footer.clientHeight;

      requestScrollTick();

    }, false );


    window.addEventListener( 'scroll', function() {

      requestScrollTick();
    }, false );


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

      scrollTicking = false;
      let sidebarIsFixed = false;

      let mainFromTop = mainContent.getBoundingClientRect().top;
      let bodyScroll = document.body.getBoundingClientRect().bottom;
      let footerPosition = bodyScroll - windowHeight - footerHeight;

      // Make `position: fixed` (etc.)
      // Or reverse that
      if ( mainFromTop <= 20 ) {

        // Bail if sidebar is longer than main content
        // TODO: don't just bail here: also remove .fixed as needed + maybe don't bail from all the footer logic so early...
        // TODO: actually, maybe cannot remove .fixed based on height comparison, as sticky sidebar can be "stubbied"
        // TODO: may have to rethink the logic...
        if( sidebar.offsetHeight > mainContent.offsetHeight ){
          return;
        }
console.log( sidebar.offsetHeight );
console.log( mainContent.offsetHeight );
        sidebar.classList.add( 'fixed' );
        sidebarIsFixed = true;

      }
      else {

        sidebar.classList.remove( 'fixed' );
        sidebarIsFixed = false;

      }

      // Raise bottom of sidebar when footer comes into view
      // Or reverse that
      if ( sidebarIsFixed && footerPosition <= 0 ) {

        sidebar.style.bottom = footerHeight + 'px';
        sidebar.style.marginBottom = '80px';

      }
      else {

        sidebar.style.bottom = '';
        sidebar.style.marginBottom = '';

      }

    }


  } );


} )
( jQuery );
