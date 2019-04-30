function jump( target, options ) {
  var
      start = window.pageYOffset,
      opt = {
        duration: options.duration,
        offset: options.offset || 0,
        easing: options.easing || easeInOutQuad,
      },

      distance = typeof target === 'string'
          ? opt.offset + document.querySelector( target ).getBoundingClientRect().top
          : target,

      duration = opt.duration,
      timeStart, timeElapsed, duration_modulated
  ;

  // Increase duration with distance. 850ms is minimum duration.
  duration_modulated = 850 + ( ( ( Math.abs( distance ) ) / 7000 ) * duration );

  duration_modulated = duration_modulated > 2600 ? 2600 : duration_modulated;

  requestAnimationFrame( function( time ) {
    timeStart = time;
    loop( time );
  } );

  function loop( time ) {
    timeElapsed = time - timeStart;

    window.scrollTo( 0, opt.easing( timeElapsed, start, distance, duration_modulated ) );

    if ( timeElapsed < duration_modulated ) {
      requestAnimationFrame( loop );
    }
    else {
      end();
    }
  }

  function end() {
    window.scrollTo( 0, start + distance );

  }

  // Robert Penner's easeInOutQuad - http://robertpenner.com/easing/
  function easeInOutQuad( t, b, c, d ) {
    t /= d / 2;
    if ( t < 1 ) {
      return c / 2 * t * t + b;
    }
    t--;
    return -c / 2 * ( t * ( t - 2 ) - 1 ) + b;
  }

}