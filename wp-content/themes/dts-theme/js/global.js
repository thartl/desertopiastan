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


    let documentHeight = document.body.clientHeight;
    let windowHeight = window.innerHeight;

    let mainContent = document.getElementById( 'genesis-content' );

    let sidebar = document.getElementById( 'genesis-sidebar-primary' );

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

      let mainFromTop = mainContent.getBoundingClientRect().top;
      let bodyScroll = document.body.getBoundingClientRect().bottom;
      let footerPosition = bodyScroll - windowHeight - footerHeight;

      if ( mainFromTop <= 16 ) {

        sidebar.classList.add( 'fixed' );

      }
      else {

        sidebar.classList.remove( 'fixed' );

      }


      if ( footerPosition <= 0 ) {

        sidebar.style.bottom = footerHeight + 'px';

      }
      else {

        sidebar.style.bottom = '';

      }

    }


  } );


} )
( jQuery );
