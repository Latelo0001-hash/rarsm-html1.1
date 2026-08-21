(function () {
	'use strict';

	function initMotion() {
		var reduced = window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches;
		var sectionSelector = [
			'#box_wrapper > section:not(.page_slider):not(.rarsm-mobile-hero):not(.page_footer):not(.page_copyright)',
			'#main-content > section:not(.page_slider):not(.rarsm-mobile-hero):not(#about):not(#quotes)'
		].join(',');
		var cardSelector = '.institutions-service-card, .activities-detail-main-card, .activities-detail-side-card, .rarsm-status-card, .hero-bg, .card';
		var sections = Array.prototype.slice.call(document.querySelectorAll(sectionSelector)).filter(function (section) {
			return !section.closest('.activities-main-content');
		});
		var cards = Array.prototype.slice.call(document.querySelectorAll(cardSelector));
		var aboutRows = Array.prototype.slice.call(document.querySelectorAll('#about > .container > .row'));
		var items = sections.concat(cards, aboutRows).filter(function (item, index, list) {
			return list.indexOf(item) === index && !item.closest('.page_header, .modal');
		});

		items.forEach(function (item, index) {
			item.classList.add('rarsm-motion-item');
			item.style.setProperty('--motion-delay', Math.min(index % 4, 3) * 90 + 'ms');

			if (cards.indexOf(item) !== -1) {
				item.classList.add('motion-scale');
			} else if (aboutRows.indexOf(item) !== -1) {
				item.classList.add(aboutRows.indexOf(item) % 2 ? 'motion-from-right' : 'motion-from-left');
			} else {
				item.classList.add(index % 2 ? 'motion-from-right' : 'motion-from-left');
			}
		});

		if (reduced || !window.IntersectionObserver) {
			items.forEach(function (item) { item.classList.add('motion-visible'); });
			return;
		}

		var observer = new IntersectionObserver(function (entries) {
			entries.forEach(function (entry) {
				if (entry.isIntersecting) {
					entry.target.classList.add('motion-visible');
					observer.unobserve(entry.target);
				}
			});
		}, { threshold: 0.08, rootMargin: '0px 0px -5% 0px' });

		items.forEach(function (item) { observer.observe(item); });
	}

	if (document.readyState === 'loading') {
		document.addEventListener('DOMContentLoaded', initMotion, { once: true });
	} else {
		initMotion();
	}
}());
