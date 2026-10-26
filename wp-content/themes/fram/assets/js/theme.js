/**
 * Front-end behaviour that CSS alone cannot do: scroll states for the header,
 * the CTA panel and the back-to-top button, plus reveal-on-scroll.
 */
( () => {
	const root = document.documentElement;
	const header = document.querySelector( '.is-style-site-header' );
	// Keeps the header's height in the page flow while the header is fixed, so nothing below moves.
	const headerWrap = header ? header.parentElement : null;
	const cta = document.querySelector( '.fram-cta' );

	// The header scrolls away with the page. Once out of view it turns fixed (hidden above the
	// viewport) and slides in after this many more pixels.
	const HEADER_SHOW_AFTER = 150;

	let headerHeight = 0;
	let ticking = false;
	let resized = true;

	// Height of the header in the page flow: measured without the scrolled state, and switched
	// back to that state without a transition.
	const measureHeader = () => {
		const state = header.className;
		header.style.transition = 'none';
		header.classList.remove( 'is-scrolled', 'is-shown', 'is-animated' );
		headerHeight = header.offsetHeight;
		headerWrap.style.height = `${ headerHeight }px`;
		header.className = state;
		header.getBoundingClientRect(); // Applies the state before the transition is restored.
		header.style.transition = '';
	};

	const update = () => {
		ticking = false;
		const y = window.scrollY;

		if ( header ) {
			if ( resized ) {
				measureHeader();
			}

			const wasFixed = header.classList.contains( 'is-scrolled' );
			const fixed = y > headerHeight;
			header.classList.toggle( 'is-scrolled', fixed );
			header.classList.toggle( 'is-shown', y > headerHeight + HEADER_SHOW_AFTER );
			// Animate between hidden and shown only, not the switch to fixed.
			header.classList.toggle( 'is-animated', wasFixed && fixed );
		}
		resized = false;

		// The fixed CTA panel shrinks to its hexagons once the page has scrolled past its top edge.
		if ( cta ) {
			cta.classList.toggle( 'is-collapsed', y > cta.getBoundingClientRect().top );
		}

		root.classList.toggle( 'fram-scrolled', y > 400 );
	};

	const onScroll = () => {
		if ( ! ticking ) {
			ticking = true;
			window.requestAnimationFrame( update );
		}
	};

	const onResize = () => {
		resized = true;
		onScroll();
	};

	window.addEventListener( 'scroll', onScroll, { passive: true } );
	window.addEventListener( 'resize', onResize, { passive: true } );
	// Web fonts and images can change the header height after this script runs.
	window.addEventListener( 'load', onResize );
	update();

	// Sections with the "reveal" class fade in when they enter the viewport.
	const revealItems = document.querySelectorAll( '.reveal' );
	if ( ! revealItems.length ) {
		return;
	}

	if ( ! ( 'IntersectionObserver' in window ) || window.matchMedia( '(prefers-reduced-motion: reduce)' ).matches ) {
		revealItems.forEach( ( item ) => item.classList.add( 'is-revealed' ) );
		return;
	}

	const observer = new IntersectionObserver(
		( entries ) => {
			entries.forEach( ( entry ) => {
				if ( entry.isIntersecting ) {
					entry.target.classList.add( 'is-revealed' );
					observer.unobserve( entry.target );
				}
			} );
		},
		{ rootMargin: '0px 0px -10% 0px' }
	);

	revealItems.forEach( ( item ) => observer.observe( item ) );
} )();
