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
			launch: "Forum",
			institution: "Institution",
			signing: "Terrain",
			media: "Média"
		},
		en: {
			launch: "Forum",
			institution: "Institution",
			signing: "Field",
			media: "Media"
		}
	};

	var uiLabels = {
		fr: {
			clickHint: "Cliquez pour afficher les détails",
			noActivityTitle: "Aucune activité",
			noActivityBody: "Aucune activité n'est programmée pour ce mois pour l'instant. Utilisez les boutons du calendrier pour naviguer vers un autre mois ou consultez la liste annuelle des activités.",
			viewDetails: "Voir le détail",
			eventWord: "activité(s)"
		},
		en: {
			clickHint: "Click to view details",
			noActivityTitle: "No activity",
			noActivityBody: "No activity is scheduled for this month yet. Use the calendar buttons to move to another month or browse the annual list of activities.",
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

	var events = [
		{
			id: "forum-annuel-secteur-minier-2026-01-22",
			title: "Forum annuel de rentrée du secteur minier",
			titleEn: "Annual mining sector opening forum",
			date: "2026-01-22",
			time: "09:30",
			location: "Kinshasa",
			category: "launch",
			summary: "Ouverture de l'année avec une lecture partagée des priorités, réformes et grands rendez-vous du secteur minier.",
			description: "Ce forum réunit les acteurs institutionnels, techniques et économiques afin de poser les principaux axes de travail de l'année et de coordonner les temps forts à venir.",
			primaryHref: "institutions.php",
			primaryLabel: "Voir les institutions",
			secondaryHref: "contact.html",
			secondaryLabel: "Proposer une activité"
		},
		{
			id: "atelier-cadastre-titres-2026-02-12",
			title: "Atelier institutionnel sur le cadastre et les titres",
			titleEn: "Institutional workshop on cadastre and titles",
			date: "2026-02-12",
			time: "10:00",
			location: "Kinshasa",
			category: "institution",
			summary: "Séance de travail consacrée au suivi des titres, à la coordination administrative et aux obligations documentaires.",
			description: "L'atelier met l'accent sur la bonne circulation de l'information entre institutions concernées, sur la fiabilité des procédures et sur la lisibilité des dossiers pour les opérateurs.",
			primaryHref: "institutions.php",
			primaryLabel: "Identifier les acteurs",
			secondaryHref: "contact.html",
			secondaryLabel: "Demander un rendez-vous"
		},
		{
			id: "mission-tracabilite-flux-2026-03-19",
			title: "Mission de terrain sur la traçabilité des flux miniers",
			titleEn: "Field mission on mining flow traceability",
			date: "2026-03-19",
			time: "08:30",
			location: "Kolwezi",
			category: "signing",
			summary: "Déplacement opérationnel consacré au suivi des circuits, au contrôle des remontées d'information et à l'observation des pratiques de terrain.",
			description: "Cette mission vise à documenter les réalités locales, à renforcer la traçabilité des substances minérales et à alimenter les échanges entre les structures techniques et les décideurs.",
			primaryHref: "contact.html",
			primaryLabel: "Signaler un besoin terrain",
			secondaryHref: "institutions.php",
			secondaryLabel: "Voir les institutions"
		},
		{
			id: "briefing-media-reglementation-2026-04-09",
			title: "Briefing média sur la réglementation minière",
			titleEn: "Media briefing on mining regulations",
			date: "2026-04-09",
			time: "11:00",
			location: "Studio média - Kinshasa",
			category: "media",
			summary: "Point d'information destiné au grand public et aux professionnels sur les sujets réglementaires qui structurent l'actualité minière.",
			description: "Le briefing permet de restituer de manière claire les enjeux de gouvernance, les évolutions réglementaires et les questions qui appellent une meilleure pédagogie sectorielle.",
			primaryHref: "contact.html",
			primaryLabel: "Contacter l'équipe",
			secondaryHref: "institutions.php",
			secondaryLabel: "Consulter les institutions"
		},
		{
			id: "dialogue-operateurs-services-2026-05-21",
			title: "Dialogue entre opérateurs et services techniques",
			titleEn: "Dialogue between operators and technical services",
			date: "2026-05-21",
			time: "14:00",
			location: "Kinshasa",
			category: "institution",
			summary: "Rencontre de coordination autour des procédures, de la conformité et des difficultés opérationnelles observées sur le terrain.",
			description: "Le dialogue vise à rapprocher les attentes des opérateurs et les exigences des structures techniques pour améliorer la fluidité des échanges et la compréhension des obligations.",
			primaryHref: "institutions.php",
			primaryLabel: "Voir les institutions",
			secondaryHref: "contact.html",
			secondaryLabel: "Demander une rencontre"
		},
		{
			id: "journee-technique-artisanale-2026-06-18",
			title: "Journée technique sur l'exploitation artisanale",
			titleEn: "Technical day on artisanal mining",
			date: "2026-06-18",
			time: "09:00",
			location: "Lubumbashi",
			category: "launch",
			summary: "Temps fort consacré à l'encadrement, aux bonnes pratiques et aux besoins d'accompagnement des acteurs de l'exploitation artisanale.",
			description: "La journée rassemble experts, encadreurs et représentants institutionnels afin de partager des repères concrets sur la sécurité, la conformité et l'organisation des filières artisanales.",
			primaryHref: "contact.html",
			primaryLabel: "Demander les détails",
			secondaryHref: "institutions.php",
			secondaryLabel: "Voir les institutions"
		},
		{
			id: "rencontre-conformite-provinciale-2026-07-24",
			title: "Rencontre provinciale sur la conformité minière",
			titleEn: "Provincial meeting on mining compliance",
			date: "2026-07-24",
			time: "10:30",
			location: "Likasi",
			category: "institution",
			summary: "Échanges ciblés sur le respect des obligations, la qualité des dossiers et la coordination entre acteurs locaux du secteur.",
			description: "Cette rencontre permet d'identifier les points de vigilance en province, de partager les attentes des institutions et d'orienter les opérateurs vers de meilleures pratiques de conformité.",
			primaryHref: "contact.html",
			primaryLabel: "Recevoir les détails",
			secondaryHref: "institutions.php",
			secondaryLabel: "Voir les institutions"
		},
		{
			id: "mission-approvisionnement-responsable-2026-08-14",
			title: "Mission sur l'approvisionnement responsable",
			titleEn: "Mission on responsible sourcing",
			date: "2026-08-14",
			time: "08:00",
			location: "Goma",
			category: "signing",
			summary: "Déploiement terrain autour de la chaîne d'approvisionnement, de la remontée d'informations et des mécanismes de suivi.",
			description: "La mission documente les exigences d'approvisionnement responsable, la circulation des données utiles et les enjeux de coordination entre les structures impliquées.",
			primaryHref: "contact.html",
			primaryLabel: "Proposer une mission",
			secondaryHref: "institutions.php",
			secondaryLabel: "Consulter les institutions"
		},
		{
			id: "forum-investisseurs-gouvernance-2026-09-11",
			title: "Forum investisseurs et gouvernance minière",
			titleEn: "Investors and mining governance forum",
			date: "2026-09-11",
			time: "09:30",
			location: "Kinshasa",
			category: "launch",
			summary: "Temps d'échange sur l'environnement réglementaire, la sécurité juridique et les attentes des partenaires publics et privés.",
			description: "Ce forum propose une lecture croisée des enjeux d'investissement, de gouvernance et de conformité afin de favoriser des décisions mieux informées dans le secteur minier.",
			primaryHref: "contact.html",
			primaryLabel: "Demander une invitation",
			secondaryHref: "institutions.php",
			secondaryLabel: "Voir les institutions"
		},
		{
			id: "point-presse-certification-exportation-2026-10-16",
			title: "Point presse sur la certification et l'exportation",
			titleEn: "Press briefing on certification and exports",
			date: "2026-10-16",
			time: "11:30",
			location: "Kinshasa",
			category: "media",
			summary: "Prise de parole publique sur les mécanismes de certification, de traçabilité et de contrôle des flux à l'export.",
			description: "Le point presse éclaire les professionnels et le public sur les exigences de certification, les enjeux de transparence et les bonnes pratiques attendues à l'exportation.",
			primaryHref: "contact.html",
			primaryLabel: "Contacter l'équipe",
			secondaryHref: "institutions.php",
			secondaryLabel: "Consulter les institutions"
		},
		{
			id: "atelier-controle-fiscalite-2026-11-20",
			title: "Atelier interinstitutionnel sur le contrôle et la fiscalité",
			titleEn: "Inter-institutional workshop on oversight and taxation",
			date: "2026-11-20",
			time: "09:45",
			location: "Kinshasa",
			category: "institution",
			summary: "Session de travail sur le suivi des obligations, les mécanismes de contrôle et la lisibilité des procédures fiscales.",
			description: "L'atelier réunit plusieurs structures concernées pour renforcer la coordination, réduire les zones d'interprétation et améliorer la qualité des échanges avec les opérateurs.",
			primaryHref: "institutions.php",
			primaryLabel: "Voir les institutions",
			secondaryHref: "contact.html",
			secondaryLabel: "Proposer une collaboration"
		},
		{
			id: "bilan-annuel-secteur-minier-2026-12-10",
			title: "Bilan annuel et perspectives du secteur minier",
			titleEn: "Annual review and outlook for the mining sector",
			date: "2026-12-10",
			time: "10:00",
			location: "Kinshasa",
			category: "launch",
			summary: "Clôture de l'année avec un retour sur les activités réalisées, les enjeux persistants et les priorités à venir.",
			description: "Ce rendez-vous de fin d'année permet de faire la synthèse des temps forts du calendrier, de partager les enseignements utiles et de préparer les orientations de l'année suivante.",
			primaryHref: "contact.html",
			primaryLabel: "Partager une activité",
			secondaryHref: "institutions.php",
			secondaryLabel: "Voir les institutions"
		}
	];

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

	function getEventDetailUrl(eventId) {
		return "activites-details.php?event=" + encodeURIComponent(eventId);
	}

	function sortEvents(a, b) {
		if (a.date !== b.date) {
			return a.date.localeCompare(b.date);
		}
		return a.time.localeCompare(b.time);
	}

	var sortedEvents = events.slice().sort(sortEvents);
	var eventsByDate = {};

	sortedEvents.forEach(function (event) {
		if (!eventsByDate[event.date]) {
			eventsByDate[event.date] = [];
		}
		eventsByDate[event.date].push(event);
	});

	var minYear = parseLocalDate(sortedEvents[0].date).getFullYear();
	var maxYear = parseLocalDate(sortedEvents[sortedEvents.length - 1].date).getFullYear();
	var today = new Date();
	today.setHours(12, 0, 0, 0);
	var todayKey = dateKeyFromParts(today.getFullYear(), today.getMonth(), today.getDate());

	function getUpcomingEvent() {
		for (var i = 0; i < sortedEvents.length; i += 1) {
			if (parseLocalDate(sortedEvents[i].date) >= today) {
				return sortedEvents[i];
			}
		}
		return sortedEvents[0];
	}

	function getEventById(eventId) {
		for (var i = 0; i < sortedEvents.length; i += 1) {
			if (sortedEvents[i].id === eventId) {
				return sortedEvents[i];
			}
		}
		return null;
	}

	var hashEvent = getEventById(window.location.hash.replace("#", ""));
	var fallbackEvent = hashEvent || getUpcomingEvent();
	var canUseToday = today.getFullYear() >= minYear && today.getFullYear() <= maxYear;
	var currentDate = canUseToday ? today : parseLocalDate(fallbackEvent.date);
	var currentYear = currentDate.getFullYear();
	var currentMonth = currentDate.getMonth();
	var selectedDateKey = hashEvent ? hashEvent.date : (canUseToday && eventsByDate[todayKey] && eventsByDate[todayKey].length ? todayKey : fallbackEvent.date);
	var selectedEventId = hashEvent ? hashEvent.id : (eventsByDate[selectedDateKey] && eventsByDate[selectedDateKey][0] ? eventsByDate[selectedDateKey][0].id : null);

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

	function getMonthEvents(year, monthIndex) {
		return sortedEvents.filter(function (event) {
			var eventDate = parseLocalDate(event.date);
			return eventDate.getFullYear() === year && eventDate.getMonth() === monthIndex;
		});
	}

	function setView(year, monthIndex, autoSelect) {
		currentYear = year;
		currentMonth = monthIndex;

		if (autoSelect) {
			var monthEvents = getMonthEvents(year, monthIndex);
			if (monthEvents.length) {
				selectedDateKey = monthEvents[0].date;
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
			metaRow.innerHTML = "<span>" + sentenceCase(formatHumanDate(event.date)) + "</span><span>" + event.time + "</span><span>" + event.location + "</span>";

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
				sentenceCase(formatHumanDate(event.date)) +
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
