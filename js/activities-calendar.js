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

	if (!calendarDays || !calendarWeekdays || !monthSelect || !yearSelect || !detailContainer || !eventList) {
		return;
	}

	var monthNames = [
		"Janvier",
		"Fevrier",
		"Mars",
		"Avril",
		"Mai",
		"Juin",
		"Juillet",
		"Aout",
		"Septembre",
		"Octobre",
		"Novembre",
		"Decembre"
	];

	var weekdayNames = ["Lun", "Mar", "Mer", "Jeu", "Ven", "Sam", "Dim"];

	var categoryMeta = {
		launch: { label: "Lancement", className: "event-launch" },
		institution: { label: "Institutions", className: "event-institution" },
		signing: { label: "Dedicace", className: "event-signing" },
		media: { label: "Media", className: "event-media" }
	};

	var events = [
		{
			id: "conference-presse-rarsm-2026-07-04",
			title: "Conference de presse de lancement",
			date: "2026-07-04",
			time: "10:00",
			location: "Kinshasa",
			category: "launch",
			summary: "Presentation officielle du RARSM devant la presse, les partenaires et les premiers lecteurs.",
			description: "Cette activite lance publiquement le recueil avec une presentation de sa valeur pratique, un temps d'echange et un point de presse.",
			primaryHref: "contact.html",
			primaryLabel: "Demander une invitation",
			secondaryHref: "pricing.html",
			secondaryLabel: "Commander le livre"
		},
		{
			id: "rencontre-institutions-2026-07-09",
			title: "Rencontre technique avec les institutions",
			date: "2026-07-09",
			time: "14:30",
			location: "Kinshasa",
			category: "institution",
			summary: "Session dediee aux usages du recueil dans les administrations et structures du secteur minier.",
			description: "L'auteur presente les textes cles, la logique du classement et les apports concrets du RARSM pour les equipes juridiques et techniques.",
			primaryHref: "contact.html",
			primaryLabel: "Prendre contact",
			secondaryHref: "pricing.html#institutions",
			secondaryLabel: "Offre institutions"
		},
		{
			id: "dedicace-lecteurs-2026-07-16",
			title: "Seance de dedicace avec les lecteurs",
			date: "2026-07-16",
			time: "16:00",
			location: "Kinshasa",
			category: "signing",
			summary: "Rencontre directe avec les lecteurs pour signature, presentation et achat sur place.",
			description: "Un moment de proximite pour echanger autour du contenu du livre, prendre des photos et reserver plusieurs exemplaires.",
			primaryHref: "pricing.html",
			primaryLabel: "Voir les formats",
			secondaryHref: "contact.html",
			secondaryLabel: "Poser une question"
		},
		{
			id: "interview-speciale-2026-07-23",
			title: "Interview speciale sur le secteur minier",
			date: "2026-07-23",
			time: "11:00",
			location: "Studio media - Kinshasa",
			category: "media",
			summary: "Entretien consacre aux enjeux reglementaires et a la place du recueil dans la pratique professionnelle.",
			description: "Cette prise de parole revient sur les grandes familles d'actes reglementaires et sur les besoins de clarte du terrain.",
			primaryHref: "contact.html",
			primaryLabel: "Soliciter une interview",
			secondaryHref: "pricing.html",
			secondaryLabel: "Acheter le livre"
		},
		{
			id: "table-ronde-rarsm-2026-07-28",
			title: "Table ronde professionnelle",
			date: "2026-07-28",
			time: "15:00",
			location: "Kinshasa",
			category: "institution",
			summary: "Dialogue entre institutions, operateurs et lecteurs autour de l'actualite reglementaire.",
			description: "La table ronde met en perspective les besoins de conformite, de traçabilite et de bonne gouvernance dans le secteur minier.",
			primaryHref: "contact.html",
			primaryLabel: "Participer",
			secondaryHref: "pricing.html#institutions",
			secondaryLabel: "Commande institutionnelle"
		},
		{
			id: "conference-universitaire-2026-08-05",
			title: "Conference universitaire",
			date: "2026-08-05",
			time: "09:30",
			location: "Lubumbashi",
			category: "launch",
			summary: "Presentation du RARSM devant un public academique et professionnel.",
			description: "Cette conference insiste sur les usages pedagogiques du recueil pour les etudiants, enseignants et chercheurs en droit minier.",
			primaryHref: "contact.html",
			primaryLabel: "Inviter l'auteur",
			secondaryHref: "pricing.html",
			secondaryLabel: "Commander"
		},
		{
			id: "rencontre-partenaires-2026-08-19",
			title: "Rencontre avec partenaires et investisseurs",
			date: "2026-08-19",
			time: "13:00",
			location: "Kinshasa",
			category: "institution",
			summary: "Echange sur la securisation reglementaire et la valeur du recueil pour la decision.",
			description: "Une session plus strategique consacree a la lecture des obligations, des procedures et des enjeux de gouvernance sectorielle.",
			primaryHref: "pricing.html#institutions",
			primaryLabel: "Commander pour une equipe",
			secondaryHref: "contact.html",
			secondaryLabel: "Demander un rendez-vous"
		},
		{
			id: "presentation-lubumbashi-2026-09-03",
			title: "Presentation publique a Lubumbashi",
			date: "2026-09-03",
			time: "17:00",
			location: "Lubumbashi",
			category: "signing",
			summary: "Presentation suivie d'une vente directe et d'une signature d'exemplaires.",
			description: "Cette activite rapproche le livre des praticiens locaux et permet de repondre aux questions sur son contenu et son usage.",
			primaryHref: "pricing.html",
			primaryLabel: "Voir les prix",
			secondaryHref: "contact.html",
			secondaryLabel: "Recevoir les details"
		},
		{
			id: "masterclass-conformite-2026-09-24",
			title: "Masterclass conformite miniere",
			date: "2026-09-24",
			time: "10:30",
			location: "Kinshasa",
			category: "media",
			summary: "Session approfondie sur la lecture pratique des actes reglementaires du secteur minier.",
			description: "La masterclass donne des reperes pour mieux utiliser le recueil dans l'analyse, la conformite et la preparation des dossiers.",
			primaryHref: "contact.html",
			primaryLabel: "Reserver une place",
			secondaryHref: "pricing.html",
			secondaryLabel: "Acheter un exemplaire"
		},
		{
			id: "forum-rarsm-2027-01-15",
			title: "Forum de debut d'annee autour du RARSM",
			date: "2027-01-15",
			time: "09:00",
			location: "Kinshasa",
			category: "launch",
			summary: "Rencontre de debut d'annee pour faire le point sur les activites et l'usage du recueil.",
			description: "Le forum ouvre la nouvelle annee de communication du projet avec un agenda de rencontres, presentations et partenariats.",
			primaryHref: "contact.html",
			primaryLabel: "Ecrire a l'equipe",
			secondaryHref: "pricing.html#institutions",
			secondaryLabel: "Voir les commandes groupees"
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
		return date.toLocaleDateString("fr-FR", {
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
	var selectedDateKey = hashEvent ? hashEvent.date : (canUseToday ? todayKey : fallbackEvent.date);
	var selectedEventId = hashEvent ? hashEvent.id : (eventsByDate[selectedDateKey] && eventsByDate[selectedDateKey][0] ? eventsByDate[selectedDateKey][0].id : null);

	function renderWeekdays() {
		calendarWeekdays.innerHTML = "";
		weekdayNames.forEach(function (weekday) {
			var item = document.createElement("div");
			item.className = "activities-calendar-weekday";
			item.textContent = weekday;
			calendarWeekdays.appendChild(item);
		});
	}

	function renderSelectOptions() {
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
		renderDetail();
		renderEventList();
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
		renderDetail();
		renderEventList();
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

		var primaryCategory = categoryMeta[eventsForDay[0].category];
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
		button.setAttribute("aria-label", sentenceCase(formatHumanDate(dateKey)) + ", " + eventsForDay.length + " activite(s)");

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
			title.textContent = event.title;
			tooltip.appendChild(title);
		});

		var hint = document.createElement("span");
		hint.className = "activities-day-tooltip-hint";
		hint.textContent = "Cliquez pour afficher les details";
		tooltip.appendChild(hint);

		button.appendChild(tooltip);

		button.addEventListener("click", function () {
			selectDate(dateKey, eventsForDay[0].id, true);
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
		detailContainer.innerHTML = "";

		if (!selectedDateKey || !eventsByDate[selectedDateKey] || !eventsByDate[selectedDateKey].length) {
			detailTitle.textContent = "Aucune activité";
			detailContainer.innerHTML = '<div class="activities-event-empty"><p>Aucune activite n\'est programmee pour ce mois pour l\'instant. Utilisez les boutons du calendrier pour naviguer vers un autre mois ou consultez la liste des prochaines activites.</p></div>';
			return;
		}

		detailTitle.textContent = sentenceCase(formatHumanDate(selectedDateKey));

		eventsByDate[selectedDateKey].forEach(function (event) {
			var meta = categoryMeta[event.category];
			var card = document.createElement("article");
			card.className = "activities-detail-card";

			if (selectedEventId === event.id) {
				card.className += " is-active";
			}

			var header = document.createElement("div");
			header.className = "activities-detail-header";

			var title = document.createElement("h4");
			title.textContent = event.title;
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
			actions.innerHTML = '<a href="' + event.primaryHref + '" class="btn btn-maincolor">' + event.primaryLabel + '</a><a href="' + event.secondaryHref + '" class="btn btn-outline-maincolor">' + event.secondaryLabel + "</a>";

			card.appendChild(header);
			card.appendChild(summary);
			card.appendChild(metaRow);
			card.appendChild(description);
			card.appendChild(actions);
			detailContainer.appendChild(card);
		});
	}

	function renderEventList() {
		eventList.innerHTML = "";

		sortedEvents.forEach(function (event) {
			var meta = categoryMeta[event.category];
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
				event.title +
				"</strong>" +
				'<span class="activities-event-list-meta">' +
				event.time +
				" • " +
				event.location +
				" • " +
				meta.label +
				"</span>";

			item.addEventListener("click", function () {
				selectDate(event.date, event.id, false);
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

	renderWeekdays();
	renderSelectOptions();
	renderCalendar();
	renderDetail();
	renderEventList();
})();
