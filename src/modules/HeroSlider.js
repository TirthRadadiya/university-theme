import Glide from "@glidejs/glide";

class HeroSlider {
	constructor() {
		if ( document.querySelector( '.hero-slider' ) ) {
			// Count how many slides there are.
			const dotCount = document.querySelectorAll( '.hero-slider__slide' ).length;

			// Generate the HTML for the navigation dots.
			let dotHTML = '';
			for ( let i = 0; i < dotCount; i++ ) {
				dotHTML += `<button class="slider__bullet glide__bullet" data-glide-dir="=${i}"></button>`;
			}

			// Add the dots HTML to the DOM.
			document.querySelector( '.glide__bullets' ).insertAdjacentHTML( 'beforeend', dotHTML );

			// Initialize the glide / slider script.
			const glide = new Glide( '.hero-slider', {
				type: 'carousel',
				perView: 1,
				autoplay: 3000
			} );

			glide.mount();
		}
	}
}

export default HeroSlider;
