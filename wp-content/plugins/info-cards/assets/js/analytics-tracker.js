(function () {
	'use strict';

	if (typeof window === 'undefined' || !window.infocardsAnalytics) {
		return;
	}

	const config = window.infocardsAnalytics;
	const trackedClicks = new Set();

	document.addEventListener('click', function (event) {
		const target = event.target;
		if (!target) return;

		// Strictly target Info Cards plugin blocks (covers all 9 blocks of Info Cards)
		const blockContainer = target.closest(
			'[class*="wp-block-info-cards"], [class*="wp-block-icb"], .icb-cards, .fold-out-reveal-cards, .animated-gradient-cards, .expandable-card-slider, .expanding-flex-cards, .magnifying-glass-cards, .news-cards, .nice-box, .player-cards'
		);

		if (!blockContainer) {
			return;
		}

		// Click must be on a link or button or card item inside an Info Cards block
		const linkElement = target.closest('a, button, [role="button"], .icb-card, .info-card-item');
		if (!linkElement && target === blockContainer) {
			return;
		}

		const scope = blockContainer;

		// Extract card title from the card item or closest heading/title element
		let title = '';
		const cardItem = target.closest('[class*="card"], [class*="item"], [class*="box"], [class*="slide"], article') || scope;

		const titleElement = cardItem.querySelector(
			'[class*="title"], [class*="heading"], h1, h2, h3, h4, h5, h6, strong, b, .card-title, .info-card-title'
		);

		if (titleElement && titleElement.textContent) {
			title = titleElement.textContent.trim();
		} else if (linkElement && linkElement.textContent) {
			title = linkElement.textContent.trim();
		} else if (cardItem.getAttribute('aria-label')) {
			title = cardItem.getAttribute('aria-label').trim();
		} else if (cardItem.getAttribute('title')) {
			title = cardItem.getAttribute('title').trim();
		}

		if (!title) return;

		// Trim title to 200 characters max
		title = title.substring(0, 200).trim();

		// Prevent duplicate submission in same page view within 2 seconds
		const dedupeKey = title + '|' + (linkElement ? linkElement.href || '' : '');
		if (trackedClicks.has(dedupeKey)) {
			return;
		}
		trackedClicks.add(dedupeKey);
		setTimeout(function () {
			trackedClicks.delete(dedupeKey);
		}, 2000);

		// Prepare tracking payload
		const payload = new URLSearchParams();
		payload.append('action', config.action || 'infocards_track_click');
		payload.append('nonce', config.nonce || '');
		payload.append('title', title);
		payload.append('source_url', window.location.href);

		// Send beacon or async fetch for real-time reliable tracking
		if (navigator.sendBeacon) {
			navigator.sendBeacon(config.ajaxurl, payload);
		} else if (window.fetch) {
			window.fetch(config.ajaxurl, {
				method: 'POST',
				body: payload,
				keepalive: true,
				headers: {
					'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8',
				},
			}).catch(function () {});
		} else {
			const xhr = new XMLHttpRequest();
			xhr.open('POST', config.ajaxurl, true);
			xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=UTF-8');
			xhr.send(payload.toString());
		}
	}, true);
})();
