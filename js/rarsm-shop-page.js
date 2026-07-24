(function () {
	'use strict';

	function getVisibleItems(slider) {
		var width = window.innerWidth || document.documentElement.clientWidth || 1440;

		if (width < 768) {
			return parseInt(slider.getAttribute('data-visible-mobile') || '1', 10);
		}

		if (width < 1200) {
			return parseInt(slider.getAttribute('data-visible-tablet') || slider.getAttribute('data-visible-desktop') || '1', 10);
		}

		return parseInt(slider.getAttribute('data-visible-desktop') || '1', 10);
	}

	function initInlineSlider(slider) {
		var viewport = slider.querySelector('.rarsm-inline-carousel-viewport');
		var track = slider.querySelector('.rarsm-inline-carousel-track');
		var slides = Array.prototype.slice.call(slider.querySelectorAll('.rarsm-inline-carousel-slide'));
		var dotsContainer = slider.querySelector('.rarsm-inline-carousel-dots');
		var canHover = window.matchMedia ? window.matchMedia('(hover: hover)').matches : false;
		var resizeTimer = null;
		var autoplayTimer = null;
		var index = 0;
		var visibleItems = 1;

		if (!viewport || !track || !slides.length) {
			return;
		}

		slider.classList.add('is-ready');

		function getPageCount() {
			return Math.max(1, slides.length - visibleItems + 1);
		}

		function clampIndex(nextIndex) {
			var maxIndex = getPageCount() - 1;

			if (maxIndex <= 0) {
				return 0;
			}

			if (nextIndex < 0) {
				return maxIndex;
			}

			if (nextIndex > maxIndex) {
				return 0;
			}

			return nextIndex;
		}

		function updateDots() {
			if (!dotsContainer) {
				return;
			}

			var dots = dotsContainer.querySelectorAll('.rarsm-inline-carousel-dot');

			Array.prototype.forEach.call(dots, function (dot, dotIndex) {
				var isActive = dotIndex === index;
				dot.classList.toggle('is-active', isActive);
				dot.setAttribute('aria-pressed', isActive ? 'true' : 'false');
			});
		}

		function buildDots() {
			if (!dotsContainer) {
				return;
			}

			var pageCount = getPageCount();
			var fragment = document.createDocumentFragment();
			var dotIndex = 0;

			dotsContainer.innerHTML = '';
			dotsContainer.hidden = pageCount <= 1;

			for (dotIndex = 0; dotIndex < pageCount; dotIndex += 1) {
				var dot = document.createElement('span');

				dot.className = 'rarsm-inline-carousel-dot';
				dot.setAttribute('role', 'button');
				dot.setAttribute('tabindex', '0');
				dot.setAttribute('aria-label', 'Afficher l’élément ' + (dotIndex + 1));
				dot.setAttribute('aria-pressed', dotIndex === index ? 'true' : 'false');
				dot.setAttribute('data-index', String(dotIndex));

				dot.addEventListener('click', function (event) {
					var requestedIndex = parseInt(event.currentTarget.getAttribute('data-index') || '0', 10);
					goTo(requestedIndex, true);
				});

				dot.addEventListener('keydown', function (event) {
					if (event.key === 'Enter' || event.key === ' ') {
						event.preventDefault();
						var requestedIndex = parseInt(event.currentTarget.getAttribute('data-index') || '0', 10);
						goTo(requestedIndex, true);
					}
				});

				fragment.appendChild(dot);
			}

			dotsContainer.appendChild(fragment);
			updateDots();
		}

		function applyVisibleItems() {
			visibleItems = Math.max(1, getVisibleItems(slider));
			slider.style.setProperty('--visible-items', String(visibleItems));
			index = Math.min(index, getPageCount() - 1);
			buildDots();
		}

		function goTo(nextIndex, animate) {
			var activeSlide = null;
			var translateX = 0;

			index = clampIndex(nextIndex);
			activeSlide = slides[index];

			if (!activeSlide) {
				return;
			}

			translateX = activeSlide.offsetLeft;

			if (animate === false) {
				track.style.transition = 'none';
				track.style.transform = 'translate3d(' + (-translateX) + 'px, 0, 0)';
				window.requestAnimationFrame(function () {
					track.style.transition = 'transform 0.45s ease';
				});
			} else {
				track.style.transform = 'translate3d(' + (-translateX) + 'px, 0, 0)';
			}

			updateDots();
			restartAutoplay();
		}

		function stopAutoplay() {
			if (autoplayTimer) {
				window.clearTimeout(autoplayTimer);
				autoplayTimer = null;
			}
		}

		function restartAutoplay() {
			var autoplayDelay = parseInt(slider.getAttribute('data-autoplay') || '0', 10);

			stopAutoplay();

			if (autoplayDelay > 0 && getPageCount() > 1) {
				autoplayTimer = window.setTimeout(function () {
					goTo(index + 1, true);
				}, autoplayDelay);
			}
		}

		if (canHover) {
			slider.addEventListener('mouseenter', stopAutoplay);
			slider.addEventListener('mouseleave', restartAutoplay);
		}

		slider.addEventListener('focusin', stopAutoplay);
		slider.addEventListener('focusout', restartAutoplay);

		function syncSliderPosition() {
			if (resizeTimer) {
				window.clearTimeout(resizeTimer);
			}

			resizeTimer = window.setTimeout(function () {
				applyVisibleItems();
				goTo(index, false);
			}, 160);
		}

		window.addEventListener('resize', syncSliderPosition);
		window.addEventListener('orientationchange', syncSliderPosition);
		window.addEventListener('load', function () {
			applyVisibleItems();
			goTo(index, false);
		});
		document.addEventListener('visibilitychange', function () {
			if (document.hidden) {
				stopAutoplay();
				return;
			}

			applyVisibleItems();
			goTo(index, false);
		});

		applyVisibleItems();
		goTo(0, false);
		restartAutoplay();
	}

	document.addEventListener('DOMContentLoaded', function () {
		if (!document.body.classList.contains('rarsm-shop-page')) {
			return;
		}

		Array.prototype.forEach.call(document.querySelectorAll('[data-rarsm-slider]'), function (slider) {
			initInlineSlider(slider);
		});
	});
}());
