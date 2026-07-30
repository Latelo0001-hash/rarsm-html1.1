(function () {
	var calendarDays = document.getElementById("activities-calendar-days");
	var calendarWeekdays = document.getElementById("activities-calendar-weekdays");
	var monthSelect = document.getElementById("activities-month-select");
	var yearSelect = document.getElementById("activities-year-select");
	var currentTitle = document.getElementById("activities-current-title");
	var detailTitle = document.getElementById("activities-detail-title");
	var detailContainer = document.getElementById("activities-event-detail");
	var eventList = document.getElementById("activities-event-list");
	var navButtons = document.querySelectorAll("[data-calendar-nav]");

	if (!calendarDays || !calendarWeekdays || !monthSelect || !yearSelect || !currentTitle) {
		return;
	}

	function getLanguage() {
		if (window.RARSM_I18N && typeof window.RARSM_I18N.getLanguage === "function") {
			return window.RARSM_I18N.getLanguage();
		}

		return "fr";
	}

	var monthNamesByLanguage = {
		fr: [
			"Janvier",
			"Février",
			"Mars",
			"Avril",
			"Mai",
			"Juin",
			"Juillet",
			"Août",
			"Septembre",
			"Octobre",
			"Novembre",
			"Décembre"
		],
		en: [
			"January",
			"February",
			"March",
			"April",
			"May",
			"June",
			"July",
			"August",
			"September",
			"October",
			"November",
			"December"
		]
	};

	var weekdayNamesByLanguage = {
		fr: ["Lun", "Mar", "Mer", "Jeu", "Ven", "Sam", "Dim"],
		en: ["Mon", "Tue", "Wed", "Thu", "Fri", "Sat", "Sun"]
	};

	var categoryMeta = {
		launch: { className: "event-launch" },
		institution: { className: "event-institution" },
		signing: { className: "event-signing" },
		media: { className: "event-media" }
	};

	var categoryLabels = {
		fr: {
			launch: "Grand forum",
			institution: "Investissement",
			signing: "Leadership",
			media: "Développement"
		},
		en: {
			launch: "Major forum",
			institution: "Investment",
			signing: "Leadership",
			media: "Development"
		}
	};

	var uiLabels = {
		fr: {
			clickHint: "Cliquez pour afficher les détails",
			noActivityTitle: "Aucun événement",
			noActivityBody: "Aucun rendez-vous annuel n'est programmé pour ce mois pour l'instant. Utilisez les boutons du calendrier pour naviguer vers un autre mois.",
			viewDetails: "Voir le détail",
			eventWord: "événement(s)"
		},
		en: {
			clickHint: "Click to view details",
			noActivityTitle: "No event",
			noActivityBody: "No annual mining event is scheduled for this month yet. Use the calendar buttons to move to another month.",
			viewDetails: "View details",
			eventWord: "event(s)"
		}
	};

	function getMonthNames() {
		return monthNamesByLanguage[getLanguage()] || monthNamesByLanguage.fr;
	}

	function getWeekdayNames() {
		return weekdayNamesByLanguage[getLanguage()] || weekdayNamesByLanguage.fr;
	}

	function getUiLabel(key) {
		var language = getLanguage();
		var dictionary = uiLabels[language] || uiLabels.fr;
		return dictionary[key] || "";
	}

	function getCategoryMeta(category) {
		var language = getLanguage();
		return {
			label: (categoryLabels[language] && categoryLabels[language][category]) || (categoryLabels.fr[category] || ""),
			className: categoryMeta[category].className
		};
	}

	function eventTitle(event) {
		return getLanguage() === "en" && event.titleEn ? event.titleEn : event.title;
	}

	function parseLocalDate(dateString) {
		var parts = dateString.split("-");
		return new Date(Number(parts[0]), Number(parts[1]) - 1, Number(parts[2]), 12, 0, 0, 0);
	}

	function dateKeyFromParts(year, monthIndex, day) {
		return [
			String(year),
			String(monthIndex + 1).padStart(2, "0"),
			String(day).padStart(2, "0")
		].join("-");
	}

	function formatHumanDate(dateString) {
		var date = parseLocalDate(dateString);
		return date.toLocaleDateString(getLanguage() === "en" ? "en-US" : "fr-FR", {
			weekday: "long",
			day: "numeric",
			month: "long",
			year: "numeric"
		});
	}

	function sentenceCase(value) {
		if (!value) {
			return "";
		}
		return value.charAt(0).toUpperCase() + value.slice(1);
	}

	function expandDateRange(startDate, endDate) {
		var dates = [];
		var cursor = parseLocalDate(startDate);
		var last = parseLocalDate(endDate);

		while (cursor <= last) {
			dates.push(dateKeyFromParts(cursor.getFullYear(), cursor.getMonth(), cursor.getDate()));
			cursor.setDate(cursor.getDate() + 1);
		}

		return dates;
	}

	function getEventCalendarDates(event) {
		if (Array.isArray(event.calendarDates) && event.calendarDates.length) {
			return event.calendarDates.slice();
		}

		return [event.date];
	}

	function getEventPrimaryDate(event) {
		return getEventCalendarDates(event)[0];
	}

	function formatEventDisplayDate(event) {
		if (getLanguage() === "en" && event.dateLabelEn) {
			return event.dateLabelEn;
		}

		if (event.dateLabel) {
			return event.dateLabel;
		}

		return sentenceCase(formatHumanDate(event.date));
	}

	function getEventDetailUrl(eventId) {
		return "activites-details.php?event=" + encodeURIComponent(eventId);
	}

	var links = {
		contact: "contact.html",
		drcMiningWeek: "https://wearevuka.com/mining/drc-mining-week/",
		kbm: "https://www.kbm-rdc.com/en",
		criticalMinerals: "https://wearevuka.com/critical-minerals-forum-our-purpose/",
		makutano: "https://www.makutano.cd/en/agenda-2026"
	};

	var events = [
		{
			id: "kbm-2026",
			title: "Katanga Business Meeting 2026",
			titleEn: "Katanga Business Meeting 2026",
			date: "2026-05-21",
			dateLabel: "21 au 22 mai 2026",
			dateLabelEn: "May 21-22, 2026",
			calendarDates: expandDateRange("2026-05-21", "2026-05-22"),
			time: "Forum sur deux jours",
			location: "Kolwezi",
			category: "launch",
			summary: "Forum économique annuel de Kolwezi avec une forte composante minière, énergétique et infrastructurelle.",
			description: "Le Katanga Business Meeting connecte les acteurs économiques du Lualaba avec les investisseurs et les entreprises actives dans les mines et les services liés.",
			primaryHref: links.kbm,
			primaryLabel: "Voir la source officielle",
			secondaryHref: links.contact,
			secondaryLabel: "Contacter l'équipe"
		},
		{
			id: "drc-mining-week-2026",
			title: "DRC Mining Week 2026",
			titleEn: "DRC Mining Week 2026",
			date: "2026-06-17",
			dateLabel: "17 au 19 juin 2026",
			dateLabelEn: "June 17-19, 2026",
			calendarDates: expandDateRange("2026-06-17", "2026-06-19"),
			time: "Conférence, exposition et ateliers",
			location: "Pullman Lubumbashi Grand Karavia",
			category: "launch",
			summary: "Grand rendez-vous annuel du secteur minier congolais, réunissant opérateurs, pouvoirs publics, investisseurs et fournisseurs.",
			description: "La DRC Mining Week combine exposition, panels, réseautage et forums spécialisés autour de la chaîne de valeur minière en RDC.",
			primaryHref: links.drcMiningWeek,
			primaryLabel: "Voir la source officielle",
			secondaryHref: links.contact,
			secondaryLabel: "Contacter l'équipe"
		},
		{
			id: "women-mines-leadership-2026",
			title: "Women Mines & Leadership Forum",
			titleEn: "Women Mines & Leadership Forum",
			date: "2026-06-17",
			dateLabel: "Juin 2026 (dans la DRC Mining Week)",
			dateLabelEn: "June 2026 (within DRC Mining Week)",
			time: "Programme thématique",
			location: "Lubumbashi",
			category: "signing",
			summary: "Rencontre annuelle consacrée aux femmes dirigeantes, entrepreneures et professionnelles du secteur extractif.",
			description: "Ce temps fort met en avant le leadership féminin, l'inclusion et les réseaux de décision dans l'industrie minière.",
			primaryHref: links.drcMiningWeek,
			primaryLabel: "Voir la source officielle",
			secondaryHref: links.contact,
			secondaryLabel: "Contacter l'équipe"
		},
		{
			id: "ceo-roundtable-2026",
			title: "CEO Roundtable de la DRC Mining Week",
			titleEn: "DRC Mining Week CEO Roundtable",
			date: "2026-06-17",
			dateLabel: "Juin 2026 (sur invitation, dans la DRC Mining Week)",
			dateLabelEn: "June 2026 (invitation only, within DRC Mining Week)",
			time: "Session sur invitation",
			location: "Lubumbashi",
			category: "signing",
			summary: "Table ronde annuelle réservée aux dirigeants miniers, investisseurs et décideurs publics.",
			description: "La CEO Roundtable concentre les échanges de haut niveau sur les arbitrages stratégiques, l'investissement et la gouvernance du secteur.",
			primaryHref: links.drcMiningWeek,
			primaryLabel: "Voir la source officielle",
			secondaryHref: links.contact,
			secondaryLabel: "Contacter l'équipe"
		},
		{
			id: "regional-development-forum-2026",
			title: "Regional Development Forum",
			titleEn: "Regional Development Forum",
			date: "2026-06-18",
			dateLabel: "Juin 2026 (dans la DRC Mining Week)",
			dateLabelEn: "June 2026 (within DRC Mining Week)",
			time: "Forum thématique",
			location: "Lubumbashi",
			category: "media",
			summary: "Forum annuel sur l'énergie, la logistique, les infrastructures et le développement des régions minières.",
			description: "Ce forum met en débat les conditions de développement durable et territorial autour des projets miniers.",
			primaryHref: links.drcMiningWeek,
			primaryLabel: "Voir la source officielle",
			secondaryHref: links.contact,
			secondaryLabel: "Contacter l'équipe"
		},
		{
			id: "value-chain-investment-forum-2026",
			title: "Value-Chain Investment Forum",
			titleEn: "Value-Chain Investment Forum",
			date: "2026-06-18",
			dateLabel: "Juin 2026 (dans la DRC Mining Week)",
			dateLabelEn: "June 2026 (within DRC Mining Week)",
			time: "Forum d'investissement",
			location: "Lubumbashi",
			category: "institution",
			summary: "Rencontre annuelle consacrée aux investissements et à la chaîne de valeur minière congolaise.",
			description: "Ce forum connecte les investisseurs aux opportunités de transformation, de sous-traitance et de valorisation locale.",
			primaryHref: links.drcMiningWeek,
			primaryLabel: "Voir la source officielle",
			secondaryHref: links.contact,
			secondaryLabel: "Contacter l'équipe"
		},
		{
			id: "critical-minerals-forum-2026",
			title: "DRC Critical Minerals & Industrialisation Forum 2026",
			titleEn: "DRC Critical Minerals & Industrialisation Forum 2026",
			date: "2026-10-07",
			dateLabel: "7 au 9 octobre 2026",
			dateLabelEn: "October 7-9, 2026",
			calendarDates: expandDateRange("2026-10-07", "2026-10-09"),
			time: "Forum sur trois jours",
			location: "Kolwezi",
			category: "institution",
			summary: "Forum annuel consacré aux minerais critiques, aux métaux pour batteries et à l'industrialisation locale.",
			description: "Ce rendez-vous met l'accent sur le cobalt, le cuivre, le lithium et sur les investissements en transformation locale.",
			primaryHref: links.criticalMinerals,
			primaryLabel: "Voir la source officielle",
			secondaryHref: links.contact,
			secondaryLabel: "Contacter l'équipe"
		},
		{
			id: "makutano-mining-2026",
			title: "Makutano Mining 2026",
			titleEn: "Makutano Mining 2026",
			date: "2026-11-22",
			dateLabel: "22 au 25 novembre 2026",
			dateLabelEn: "November 22-25, 2026",
			calendarDates: expandDateRange("2026-11-22", "2026-11-25"),
			time: "Forum sur quatre jours",
			location: "Kinshasa",
			category: "launch",
			summary: "Édition annuelle du Makutano Forum orientée en 2026 vers les minerais critiques et la souveraineté minière.",
			description: "Makutano Mining 2026 porte un focus sectoriel fort sur les enjeux miniers dans le cadre d'un forum économique annuel de haut niveau.",
			primaryHref: links.makutano,
			primaryLabel: "Voir la source officielle",
			secondaryHref: links.contact,
			secondaryLabel: "Contacter l'équipe"
		}
	];

	function sortEvents(left, right) {
		if (left.date !== right.date) {
			return left.date.localeCompare(right.date);
		}

		if (left.time !== right.time) {
			return String(left.time).localeCompare(String(right.time));
		}

		return left.id.localeCompare(right.id);
	}

	var sortedEvents = events.slice().sort(sortEvents);
	var eventsByDate = {};

	sortedEvents.forEach(function (event) {
		getEventCalendarDates(event).forEach(function (dateKey) {
			if (!eventsByDate[dateKey]) {
				eventsByDate[dateKey] = [];
			}
			eventsByDate[dateKey].push(event);
		});
	});

	var minYear = parseLocalDate(sortedEvents[0].date).getFullYear();
	var maxYear = parseLocalDate(sortedEvents[sortedEvents.length - 1].date).getFullYear();
	var today = new Date();
	today.setHours(12, 0, 0, 0);
	var todayKey = dateKeyFromParts(today.getFullYear(), today.getMonth(), today.getDate());

	function getEventById(eventId) {
		for (var i = 0; i < sortedEvents.length; i += 1) {
			if (sortedEvents[i].id === eventId) {
				return sortedEvents[i];
			}
		}
		return null;
	}

	function eventOccursInMonth(event, year, monthIndex) {
		return getEventCalendarDates(event).some(function (dateKey) {
			var eventDate = parseLocalDate(dateKey);
			return eventDate.getFullYear() === year && eventDate.getMonth() === monthIndex;
		});
	}

	function getMonthEventsFor(year, monthIndex) {
		return sortedEvents.filter(function (event) {
			return eventOccursInMonth(event, year, monthIndex);
		});
	}

	function getUpcomingEvent() {
		for (var i = 0; i < sortedEvents.length; i += 1) {
			if (parseLocalDate(getEventPrimaryDate(sortedEvents[i])) >= today) {
				return sortedEvents[i];
			}
		}
		return sortedEvents[0];
	}

	var hashEvent = getEventById(window.location.hash.replace("#", ""));
	var fallbackEvent = hashEvent || getUpcomingEvent();
	var todayMonthEvents = getMonthEventsFor(today.getFullYear(), today.getMonth());
	var currentDate = hashEvent
		? parseLocalDate(getEventPrimaryDate(hashEvent))
		: (todayMonthEvents.length ? today : parseLocalDate(getEventPrimaryDate(fallbackEvent)));
	var currentYear = currentDate.getFullYear();
	var currentMonth = currentDate.getMonth();
	var currentMonthEvents = getMonthEventsFor(currentYear, currentMonth);
	var fallbackDateKey = getEventPrimaryDate(fallbackEvent);
	var selectedDateKey = hashEvent
		? getEventPrimaryDate(hashEvent)
		: (eventsByDate[todayKey] && eventsByDate[todayKey].length
			? todayKey
			: (currentMonthEvents.length ? getEventPrimaryDate(currentMonthEvents[0]) : fallbackDateKey));
	var selectedEventId = hashEvent
		? hashEvent.id
		: (eventsByDate[selectedDateKey] && eventsByDate[selectedDateKey][0]
			? eventsByDate[selectedDateKey][0].id
			: fallbackEvent.id);

	function renderWeekdays() {
		var weekdayNames = getWeekdayNames();
		calendarWeekdays.innerHTML = "";
		weekdayNames.forEach(function (weekday) {
			var item = document.createElement("div");
			item.className = "activities-calendar-weekday";
			item.textContent = weekday;
			calendarWeekdays.appendChild(item);
		});
	}

	function renderSelectOptions() {
		var monthNames = getMonthNames();
		monthSelect.innerHTML = "";
		yearSelect.innerHTML = "";

		monthNames.forEach(function (monthName, index) {
			var option = document.createElement("option");
			option.value = String(index);
			option.textContent = monthName;
			monthSelect.appendChild(option);
		});

		for (var year = minYear; year <= maxYear; year += 1) {
			var yearOption = document.createElement("option");
			yearOption.value = String(year);
			yearOption.textContent = String(year);
			yearSelect.appendChild(yearOption);
		}
	}

	function syncSelectors() {
		var monthNames = getMonthNames();
		monthSelect.value = String(currentMonth);
		yearSelect.value = String(currentYear);
		currentTitle.textContent = monthNames[currentMonth] + " " + currentYear;
	}

	function setView(year, monthIndex, autoSelect) {
		currentYear = year;
		currentMonth = monthIndex;

		if (autoSelect) {
			var monthEvents = getMonthEventsFor(year, monthIndex);
			if (monthEvents.length) {
				selectedDateKey = getEventPrimaryDate(monthEvents[0]);
				selectedEventId = monthEvents[0].id;
			} else {
				selectedDateKey = null;
				selectedEventId = null;
			}
		}

		renderCalendar();
		if (detailContainer && detailTitle) {
			renderDetail();
		}
		if (eventList) {
			renderEventList();
		}
	}

	function updateHash(eventId) {
		if (!eventId) {
			return;
		}
		if (window.history && window.history.replaceState) {
			window.history.replaceState(null, "", "#" + eventId);
			return;
		}
		window.location.hash = eventId;
	}

	function selectDate(dateKey, preferredEventId, preserveView) {
		var selectedDate = parseLocalDate(dateKey);
		selectedDateKey = dateKey;
		selectedEventId = preferredEventId || (eventsByDate[dateKey] && eventsByDate[dateKey][0] ? eventsByDate[dateKey][0].id : null);

		if (!preserveView) {
			currentYear = selectedDate.getFullYear();
			currentMonth = selectedDate.getMonth();
		}

		renderCalendar();
		if (detailContainer && detailTitle) {
			renderDetail();
		}
		if (eventList) {
			renderEventList();
		}
		updateHash(selectedEventId);
	}

	function buildDayCell(dayNumber, dateKey, isCurrentMonth, rowIndex, colIndex) {
		var eventsForDay = eventsByDate[dateKey] || [];
		var cell = document.createElement("div");
		cell.className = "activities-calendar-cell";
		var isToday = dateKey === todayKey;

		if (!isCurrentMonth) {
			cell.className += " is-outside";
		}

		if (isToday) {
			cell.className += " is-today";
		}

		if (!eventsForDay.length) {
			var emptyState = document.createElement("div");
			emptyState.className = "activities-day-empty";
			emptyState.innerHTML = '<span class="activities-day-number">' + dayNumber + "</span>";
			cell.appendChild(emptyState);
			return cell;
		}

		var primaryCategory = getCategoryMeta(eventsForDay[0].category);
		cell.className += " has-event " + primaryCategory.className;
		cell.className += rowIndex >= 4 ? " tooltip-up" : " tooltip-down";

		if (colIndex <= 1) {
			cell.className += " tooltip-left";
		} else if (colIndex >= 5) {
			cell.className += " tooltip-right";
		} else {
			cell.className += " tooltip-center";
		}

		if (selectedDateKey === dateKey) {
			cell.className += " is-selected";
		}

		var button = document.createElement("button");
		button.type = "button";
		button.className = "activities-day-button";
		button.setAttribute("aria-label", sentenceCase(formatHumanDate(dateKey)) + ", " + eventsForDay.length + " " + getUiLabel("eventWord"));

		var top = document.createElement("span");
		top.className = "activities-day-top";

		var number = document.createElement("span");
		number.className = "activities-day-number";
		number.textContent = String(dayNumber);
		top.appendChild(number);

		button.appendChild(top);

		var marker = document.createElement("span");
		marker.className = "activities-day-marker";

		var dot = document.createElement("span");
		dot.className = "activities-day-dot " + primaryCategory.className;
		marker.appendChild(dot);

		if (eventsForDay.length > 1) {
			var count = document.createElement("span");
			count.className = "activities-day-marker-count";
			count.textContent = String(eventsForDay.length);
			marker.appendChild(count);
		}

		button.appendChild(marker);

		var tooltip = document.createElement("span");
		tooltip.className = "activities-day-tooltip";

		eventsForDay.forEach(function (event) {
			var title = document.createElement("span");
			title.className = "activities-day-tooltip-title";
			title.textContent = eventTitle(event);
			tooltip.appendChild(title);
		});

		var hint = document.createElement("span");
		hint.className = "activities-day-tooltip-hint";
		hint.textContent = getUiLabel("clickHint");
		tooltip.appendChild(hint);

		button.appendChild(tooltip);

		button.addEventListener("click", function () {
			window.location.href = getEventDetailUrl(eventsForDay[0].id);
		});

		cell.appendChild(button);
		return cell;
	}

	function renderCalendar() {
		syncSelectors();
		calendarDays.innerHTML = "";

		var firstOfMonth = new Date(currentYear, currentMonth, 1, 12, 0, 0, 0);
		var startOffset = (firstOfMonth.getDay() + 6) % 7;
		var daysInMonth = new Date(currentYear, currentMonth + 1, 0).getDate();
		var daysInPreviousMonth = new Date(currentYear, currentMonth, 0).getDate();
		var totalCells = 42;

		for (var cellIndex = 0; cellIndex < totalCells; cellIndex += 1) {
			var dayNumber;
			var dateKey;
			var isCurrentMonth = true;

			if (cellIndex < startOffset) {
				dayNumber = daysInPreviousMonth - startOffset + cellIndex + 1;
				var previousMonthDate = new Date(currentYear, currentMonth - 1, dayNumber, 12, 0, 0, 0);
				dateKey = dateKeyFromParts(previousMonthDate.getFullYear(), previousMonthDate.getMonth(), previousMonthDate.getDate());
				isCurrentMonth = false;
			} else if (cellIndex >= startOffset + daysInMonth) {
				dayNumber = cellIndex - startOffset - daysInMonth + 1;
				var nextMonthDate = new Date(currentYear, currentMonth + 1, dayNumber, 12, 0, 0, 0);
				dateKey = dateKeyFromParts(nextMonthDate.getFullYear(), nextMonthDate.getMonth(), nextMonthDate.getDate());
				isCurrentMonth = false;
			} else {
				dayNumber = cellIndex - startOffset + 1;
				dateKey = dateKeyFromParts(currentYear, currentMonth, dayNumber);
			}

			calendarDays.appendChild(
				buildDayCell(
					dayNumber,
					dateKey,
					isCurrentMonth,
					Math.floor(cellIndex / 7),
					cellIndex % 7
				)
			);
		}
	}

	function renderDetail() {
		if (!detailContainer || !detailTitle) {
			return;
		}

		detailContainer.innerHTML = "";

		if (!selectedDateKey || !eventsByDate[selectedDateKey] || !eventsByDate[selectedDateKey].length) {
			detailTitle.textContent = getUiLabel("noActivityTitle");
			detailContainer.innerHTML = '<div class="activities-event-empty"><p>' + getUiLabel("noActivityBody") + '</p></div>';
			return;
		}

		detailTitle.textContent = sentenceCase(formatHumanDate(selectedDateKey));

		eventsByDate[selectedDateKey].forEach(function (event) {
			var meta = getCategoryMeta(event.category);
			var detailUrl = getEventDetailUrl(event.id);
			var card = document.createElement("article");
			card.className = "activities-detail-card";

			if (selectedEventId === event.id) {
				card.className += " is-active";
			}

			var header = document.createElement("div");
			header.className = "activities-detail-header";

			var title = document.createElement("h4");
			title.innerHTML = '<a href="' + detailUrl + '">' + eventTitle(event) + '</a>';
			header.appendChild(title);

			var pill = document.createElement("span");
			pill.className = "activities-detail-pill " + meta.className;
			pill.textContent = meta.label;
			header.appendChild(pill);

			var summary = document.createElement("p");
			summary.className = "activities-detail-summary";
			summary.textContent = event.summary;

			var metaRow = document.createElement("div");
			metaRow.className = "activities-detail-meta";
			metaRow.innerHTML = "<span>" + formatEventDisplayDate(event) + "</span><span>" + event.time + "</span><span>" + event.location + "</span>";

			var description = document.createElement("p");
			description.className = "activities-detail-description";
			description.textContent = event.description;

			var actions = document.createElement("div");
			actions.className = "activities-detail-actions";
			actions.innerHTML =
				'<a href="' + detailUrl + '" class="btn btn-maincolor">' + getUiLabel("viewDetails") + '</a>' +
				'<a href="' + event.primaryHref + '" class="btn btn-outline-maincolor">' + event.primaryLabel + '</a>' +
				'<a href="' + event.secondaryHref + '" class="btn btn-outline-maincolor">' + event.secondaryLabel + "</a>";

			card.appendChild(header);
			card.appendChild(summary);
			card.appendChild(metaRow);
			card.appendChild(description);
			card.appendChild(actions);
			detailContainer.appendChild(card);
		});
	}

	function renderEventList() {
		if (!eventList) {
			return;
		}

		eventList.innerHTML = "";

		sortedEvents.forEach(function (event) {
			var meta = getCategoryMeta(event.category);
			var item = document.createElement("button");
			item.type = "button";
			item.className = "activities-event-list-button";

			if (selectedEventId === event.id) {
				item.className += " is-active";
			}

			item.innerHTML =
				'<span class="activities-event-list-date">' +
				formatEventDisplayDate(event) +
				"</span>" +
				'<strong class="activities-event-list-title">' +
				eventTitle(event) +
				"</strong>" +
				'<span class="activities-event-list-meta">' +
				event.time +
				" • " +
				event.location +
				" • " +
				meta.label +
				"</span>";

			item.addEventListener("click", function () {
				window.location.href = getEventDetailUrl(event.id);
			});

			eventList.appendChild(item);
		});
	}

	monthSelect.addEventListener("change", function () {
		setView(Number(yearSelect.value), Number(monthSelect.value), true);
	});

	yearSelect.addEventListener("change", function () {
		setView(Number(yearSelect.value), Number(monthSelect.value), true);
	});

	navButtons.forEach(function (button) {
		button.addEventListener("click", function () {
			var action = button.getAttribute("data-calendar-nav");
			var viewDate = new Date(currentYear, currentMonth, 1, 12, 0, 0, 0);

			if (action === "prev-month") {
				viewDate.setMonth(viewDate.getMonth() - 1);
			}

			if (action === "next-month") {
				viewDate.setMonth(viewDate.getMonth() + 1);
			}

			if (action === "prev-year") {
				viewDate.setFullYear(viewDate.getFullYear() - 1);
			}

			if (action === "next-year") {
				viewDate.setFullYear(viewDate.getFullYear() + 1);
			}

			if (viewDate.getFullYear() < minYear || viewDate.getFullYear() > maxYear) {
				return;
			}

			setView(viewDate.getFullYear(), viewDate.getMonth(), true);
		});
	});

	function renderAll() {
		renderWeekdays();
		renderSelectOptions();
		renderCalendar();
		if (detailContainer && detailTitle) {
			renderDetail();
		}
		if (eventList) {
			renderEventList();
		}
	}

	window.addEventListener("rarsm:languagechange", function () {
		renderAll();
	});

	renderAll();
})();
