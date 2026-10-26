/**
 * Group styles "Slider" and "Slider (dots)" (inc/slider.php): a Slick carousel over the
 * Group's blocks, one slide per block. "Slider": crossfade with round arrows; "Slider (dots)":
 * sliding with dots. Autoplay unless reduced motion is on. A single slide stays static.
 */
( ( $ ) => {
	const reducedMotion = window.matchMedia( '(prefers-reduced-motion: reduce)' ).matches;

	// Slick only inserts arrows given as an HTML string; the DOM escapes the label.
	const arrow = ( dir, label ) =>
		$( '<button type="button">' ).addClass( `fram-slider__arrow fram-slider__arrow--${ dir }` ).attr( 'aria-label', label ).prop( 'outerHTML' );

	$( '.wp-block-group.is-style-slider, .wp-block-group.is-style-slider-dots' )
		.filter( function () {
			return this.children.length > 1;
		} )
		.each( function () {
			const slider = $( this );

			const options = slider.hasClass( 'is-style-slider-dots' )
				? { dots: true, arrows: false, speed: 600, autoplaySpeed: 5000 }
				: {
						fade: true,
						speed: 1000,
						autoplaySpeed: 9000,
						pauseOnHover: false,
						prevArrow: arrow( 'prev', slider.data( 'labelPrev' ) ),
						nextArrow: arrow( 'next', slider.data( 'labelNext' ) ),
				  };

			slider.slick( {
				// Slick 1.8 wraps every slide in two extra divs unless rows is 0.
				rows: 0,
				autoplay: ! reducedMotion,
				...options,
			} );

			// Slick labels the dots "1 of 3" in English in initADA(), again after every slide change.
			const labelDots = () =>
				slider.find( '.slick-dots button' ).each( function ( i ) {
					this.setAttribute( 'aria-label', slider.data( 'labelDot' ).replace( '%d', i + 1 ) );
				} );
			const slick = slider.slick( 'getSlick' );
			const initADA = slick.initADA;
			slick.initADA = function () {
				initADA.call( this );
				labelDots();
			};
			labelDots();
		} );
} )( jQuery );
