<?php
declare(strict_types=1);

function rarsm_activity_categories(): array
{
    return [
        'launch' => ['label' => 'Grand forum', 'class' => 'event-launch'],
        'institution' => ['label' => 'Investissement', 'class' => 'event-institution'],
        'signing' => ['label' => 'Leadership', 'class' => 'event-signing'],
        'media' => ['label' => 'Développement', 'class' => 'event-media'],
    ];
}

function rarsm_activity_range(string $start, string $end): array
{
    $dates = [];
    $cursor = DateTimeImmutable::createFromFormat('!Y-m-d', $start);
    $last = DateTimeImmutable::createFromFormat('!Y-m-d', $end);

    if (!$cursor instanceof DateTimeImmutable || !$last instanceof DateTimeImmutable || $cursor > $last) {
        return [$start];
    }

    while ($cursor <= $last) {
        $dates[] = $cursor->format('Y-m-d');
        $cursor = $cursor->modify('+1 day');
    }

    return $dates;
}

function rarsm_activity_institutions(): array
{
    return [
        'drc-mining-week' => [
            'slug' => 'drc-mining-week',
            'name' => 'DRC Mining Week',
            'full_name' => 'DRC Mining Week · organisé par VUKA Group',
            'summary' => "Grande conférence et exposition minière de la RDC, la DRC Mining Week réunit chaque année opérateurs, décideurs publics, investisseurs, fournisseurs et partenaires techniques à Lubumbashi.",
            'details_href' => 'https://wearevuka.com/mining/drc-mining-week/',
            'directory_href' => 'activites.html',
            'website' => 'https://wearevuka.com/mining/drc-mining-week/',
            'socials' => [
                ['label' => 'Site officiel', 'href' => 'https://wearevuka.com/mining/drc-mining-week/', 'icon' => 'fa-globe'],
                ['label' => 'Programme', 'href' => 'https://wearevuka.com/mining/drc-mining-week/', 'icon' => 'fa-calendar'],
                ['label' => 'Contact', 'href' => 'contact.html', 'icon' => 'fa-envelope'],
            ],
        ],
        'kbm' => [
            'slug' => 'kbm',
            'name' => 'KBM',
            'full_name' => 'Katanga Business Meeting',
            'summary' => "Le Katanga Business Meeting est un forum économique annuel de Kolwezi qui accorde une place importante aux mines, à l’énergie, aux infrastructures et à la sous-traitance.",
            'details_href' => 'https://www.kbm-rdc.com/en',
            'directory_href' => 'activites.html',
            'website' => 'https://www.kbm-rdc.com/en',
            'socials' => [
                ['label' => 'Site officiel', 'href' => 'https://www.kbm-rdc.com/en', 'icon' => 'fa-globe'],
                ['label' => 'Programme', 'href' => 'https://www.kbm-rdc.com/en', 'icon' => 'fa-calendar'],
                ['label' => 'Contact', 'href' => 'contact.html', 'icon' => 'fa-envelope'],
            ],
        ],
        'critical-minerals-forum' => [
            'slug' => 'critical-minerals-forum',
            'name' => 'Critical Minerals Forum',
            'full_name' => 'DRC Critical Minerals & Industrialisation Forum',
            'summary' => "Ce forum annuel de Kolwezi est consacré au cobalt, au cuivre, au lithium, aux métaux pour batteries et à l’industrialisation locale autour des chaînes de valeur minières.",
            'details_href' => 'https://wearevuka.com/critical-minerals-forum-our-purpose/',
            'directory_href' => 'activites.html',
            'website' => 'https://wearevuka.com/critical-minerals-forum-our-purpose/',
            'socials' => [
                ['label' => 'Site officiel', 'href' => 'https://wearevuka.com/critical-minerals-forum-our-purpose/', 'icon' => 'fa-globe'],
                ['label' => 'Programme', 'href' => 'https://wearevuka.com/critical-minerals-forum-our-purpose/', 'icon' => 'fa-calendar'],
                ['label' => 'Contact', 'href' => 'contact.html', 'icon' => 'fa-envelope'],
            ],
        ],
        'makutano' => [
            'slug' => 'makutano',
            'name' => 'Makutano',
            'full_name' => 'Makutano Forum',
            'summary' => "Le Makutano Forum est un rendez-vous économique annuel. En 2026, l’édition Makutano Mining est consacrée aux minerais critiques et à la souveraineté minière.",
            'details_href' => 'https://www.makutano.cd/en/agenda-2026',
            'directory_href' => 'activites.html',
            'website' => 'https://www.makutano.cd/en/agenda-2026',
            'socials' => [
                ['label' => 'Site officiel', 'href' => 'https://www.makutano.cd/en/agenda-2026', 'icon' => 'fa-globe'],
                ['label' => 'Agenda 2026', 'href' => 'https://www.makutano.cd/en/agenda-2026', 'icon' => 'fa-calendar'],
                ['label' => 'Contact', 'href' => 'contact.html', 'icon' => 'fa-envelope'],
            ],
        ],
    ];
}

function rarsm_activity_records(): array
{
    $institutions = rarsm_activity_institutions();

    return [
        'kbm-2026' => [
            'id' => 'kbm-2026',
            'title' => 'Katanga Business Meeting 2026',
            'date' => '2026-05-21',
            'date_label' => '21 au 22 mai 2026',
            'calendar_dates' => rarsm_activity_range('2026-05-21', '2026-05-22'),
            'time' => 'Forum sur deux jours',
            'location' => 'Kolwezi',
            'category' => 'launch',
            'summary' => "Le Katanga Business Meeting est l’un des rendez-vous annuels du sud de la RDC pour les investisseurs, les décideurs publics et les entreprises actives dans les mines, l’énergie et les infrastructures.",
            'description' => "Le KBM 2026 met en relation les acteurs économiques de Kolwezi et du Lualaba avec les opérateurs miniers, les financeurs et les partenaires de sous-traitance. Il se distingue par son ancrage régional et par la place accordée aux opportunités d’affaires autour du secteur extractif.",
            'image' => 'images/rarsm-generated/cadre-institutionnel.png',
            'image_alt' => 'Katanga Business Meeting à Kolwezi',
            'institution' => $institutions['kbm'],
            'institution_role' => "Le KBM agit ici comme plateforme annuelle de rencontres économiques, avec un fort ancrage minier dans le sud de la RDC.",
            'highlights' => [
                'Forum économique annuel organisé à Kolwezi.',
                'Forte place accordée aux mines, à l’énergie et aux infrastructures.',
                'L’édition 2027 est déjà annoncée du 19 au 21 mai 2027.',
            ],
            'reference' => 'KBM RDC',
            'reference_url' => 'https://www.kbm-rdc.com/en',
            'recurrence_note' => 'Événement annuel. Le site officiel annonce déjà son retour du 19 au 21 mai 2027 à Kolwezi.',
        ],
        'drc-mining-week-2026' => [
            'id' => 'drc-mining-week-2026',
            'title' => 'DRC Mining Week 2026',
            'date' => '2026-06-17',
            'date_label' => '17 au 19 juin 2026',
            'calendar_dates' => rarsm_activity_range('2026-06-17', '2026-06-19'),
            'time' => 'Conférence, exposition et ateliers',
            'location' => 'Pullman Lubumbashi Grand Karavia',
            'category' => 'launch',
            'summary' => "La DRC Mining Week est le grand rendez-vous annuel du secteur minier congolais, combinant conférence, exposition, ateliers techniques et rencontres d’affaires.",
            'description' => "Cette manifestation réunit chaque année à Lubumbashi les autorités, compagnies minières, investisseurs, fournisseurs, EPC et partenaires techniques. Elle structure une part importante de l’agenda minier congolais en offrant un espace de visibilité, de négociation et de mise en réseau à l’échelle nationale et régionale.",
            'image' => 'images/rarsm-generated/hero-presentation-rarsm.png',
            'image_alt' => 'DRC Mining Week à Lubumbashi',
            'institution' => $institutions['drc-mining-week'],
            'institution_role' => "La DRC Mining Week sert de cadre annuel de référence pour plusieurs sous-événements spécialisés dédiés au leadership, aux investissements et au développement régional.",
            'highlights' => [
                'Grande conférence et exposition minière de la RDC.',
                'Rendez-vous majeur pour opérateurs, pouvoirs publics et investisseurs.',
                'L’édition 2027 est annoncée du 16 au 18 juin 2027 à Lubumbashi.',
            ],
            'reference' => 'VUKA Group',
            'reference_url' => 'https://wearevuka.com/mining/drc-mining-week/',
            'recurrence_note' => 'Événement annuel. Le site officiel annonce déjà l’édition suivante du 16 au 18 juin 2027 au Pullman Lubumbashi Grand Karavia.',
        ],
        'women-mines-leadership-2026' => [
            'id' => 'women-mines-leadership-2026',
            'title' => 'Women Mines & Leadership Forum',
            'date' => '2026-06-17',
            'date_label' => 'Juin 2026 (dans la DRC Mining Week)',
            'time' => 'Programme thématique',
            'location' => 'Lubumbashi',
            'category' => 'signing',
            'summary' => "Ce rendez-vous annuel, intégré à la DRC Mining Week, met en avant les femmes dirigeantes, entrepreneures et professionnelles de l’industrie extractive.",
            'description' => "Le Women Mines & Leadership Forum donne de la visibilité aux parcours féminins dans le secteur minier et crée un espace d’échanges sur le leadership, la représentation, l’entrepreneuriat et les dynamiques d’inclusion dans l’écosystème extractif.",
            'image' => 'images/rarsm-generated/approvisionnement-responsable.png',
            'image_alt' => 'Women Mines and Leadership Forum',
            'institution' => $institutions['drc-mining-week'],
            'institution_role' => "Ce forum s’inscrit dans le programme annuel de la DRC Mining Week comme espace de valorisation du leadership féminin dans l’industrie minière.",
            'highlights' => [
                'Rencontres dédiées aux femmes dirigeantes et professionnelles du secteur.',
                'Cadre de discussion sur le leadership et l’inclusion dans l’extractif.',
                'Sous-événement annuel intégré à la DRC Mining Week.',
            ],
            'reference' => 'VUKA Group',
            'reference_url' => 'https://wearevuka.com/mining/drc-mining-week/',
            'recurrence_note' => 'Rendez-vous annuel organisé dans le cadre de la DRC Mining Week.',
        ],
        'ceo-roundtable-2026' => [
            'id' => 'ceo-roundtable-2026',
            'title' => 'CEO Roundtable de la DRC Mining Week',
            'date' => '2026-06-17',
            'date_label' => 'Juin 2026 (sur invitation, dans la DRC Mining Week)',
            'time' => 'Session sur invitation',
            'location' => 'Lubumbashi',
            'category' => 'signing',
            'summary' => "La CEO Roundtable est la table ronde annuelle de la DRC Mining Week réservée aux dirigeants des entreprises minières, aux investisseurs et aux décideurs publics.",
            'description' => "Cette session fermée met l’accent sur la confiance des investisseurs, la lecture du risque et les arbitrages stratégiques des grands décideurs de l’industrie. Elle fait partie des temps forts annuels les plus sélectifs de la DRC Mining Week.",
            'image' => 'images/rarsm-generated/cadre-institutionnel.png',
            'image_alt' => 'CEO Roundtable de la DRC Mining Week',
            'institution' => $institutions['drc-mining-week'],
            'institution_role' => "La CEO Roundtable est portée par la DRC Mining Week comme espace annuel de dialogue stratégique entre décideurs de haut niveau.",
            'highlights' => [
                'Table ronde annuelle réservée aux dirigeants.',
                'Accent sur l’investissement, le risque et la confiance du marché.',
                'Accès sur invitation uniquement.',
            ],
            'reference' => 'VUKA Group',
            'reference_url' => 'https://wearevuka.com/mining/drc-mining-week/',
            'recurrence_note' => 'Rendez-vous annuel organisé dans le cadre de la DRC Mining Week.',
        ],
        'regional-development-forum-2026' => [
            'id' => 'regional-development-forum-2026',
            'title' => 'Regional Development Forum',
            'date' => '2026-06-18',
            'date_label' => 'Juin 2026 (dans la DRC Mining Week)',
            'time' => 'Forum thématique',
            'location' => 'Lubumbashi',
            'category' => 'media',
            'summary' => "Le Regional Development Forum fait partie des rendez-vous récurrents de la DRC Mining Week consacrés aux infrastructures, à l’énergie, à la logistique et au développement des régions minières.",
            'description' => "Ce forum examine les conditions de développement des zones minières au-delà de la seule production minérale. Il aborde les infrastructures, l’énergie, la mobilité, la logistique et les effets territoriaux des projets extractifs.",
            'image' => 'images/rarsm-generated/section-book-introduction-contexte.png',
            'image_alt' => 'Regional Development Forum',
            'institution' => $institutions['drc-mining-week'],
            'institution_role' => "Ce forum complète la DRC Mining Week par une lecture annuelle des besoins de développement des régions minières.",
            'highlights' => [
                'Discussion sur les infrastructures et l’énergie des zones minières.',
                'Vision territoriale du développement régional.',
                'Sous-événement annuel intégré à la DRC Mining Week.',
            ],
            'reference' => 'VUKA Group',
            'reference_url' => 'https://wearevuka.com/mining/drc-mining-week/',
            'recurrence_note' => 'Rendez-vous annuel organisé dans le cadre de la DRC Mining Week.',
        ],
        'value-chain-investment-forum-2026' => [
            'id' => 'value-chain-investment-forum-2026',
            'title' => 'Value-Chain Investment Forum',
            'date' => '2026-06-18',
            'date_label' => 'Juin 2026 (dans la DRC Mining Week)',
            'time' => 'Forum d’investissement',
            'location' => 'Lubumbashi',
            'category' => 'institution',
            'summary' => "Le Value-Chain Investment Forum rassemble chaque année, dans le cadre de la DRC Mining Week, les acteurs intéressés par les investissements et la chaîne de valeur minière congolaise.",
            'description' => "Ce rendez-vous met l’accent sur les projets de transformation, de financement, de sous-traitance, de logistique et de valorisation locale. Il sert de point de rencontre pour les investisseurs qui suivent l’évolution de la chaîne de valeur minière en RDC.",
            'image' => 'images/rarsm-generated/tracabilite-certification.png',
            'image_alt' => 'Value-Chain Investment Forum',
            'institution' => $institutions['drc-mining-week'],
            'institution_role' => "Ce forum représente la dimension annuelle d’investissement et de chaîne de valeur de la DRC Mining Week.",
            'highlights' => [
                'Rencontres consacrées aux investissements dans la chaîne de valeur minière.',
                'Focus sur la transformation locale et les partenariats industriels.',
                'Sous-événement annuel intégré à la DRC Mining Week.',
            ],
            'reference' => 'VUKA Group',
            'reference_url' => 'https://wearevuka.com/mining/drc-mining-week/',
            'recurrence_note' => 'Rendez-vous annuel organisé dans le cadre de la DRC Mining Week.',
        ],
        'critical-minerals-forum-2026' => [
            'id' => 'critical-minerals-forum-2026',
            'title' => 'DRC Critical Minerals & Industrialisation Forum 2026',
            'date' => '2026-10-07',
            'date_label' => '7 au 9 octobre 2026',
            'calendar_dates' => rarsm_activity_range('2026-10-07', '2026-10-09'),
            'time' => 'Forum sur trois jours',
            'location' => 'Kolwezi',
            'category' => 'institution',
            'summary' => "Ce forum annuel, anciennement connu sous le nom de DRC-Africa Battery Metals Forum, est consacré aux métaux critiques, à l’industrialisation et aux investissements en aval.",
            'description' => "Le DRC Critical Minerals & Industrialisation Forum réunit à Kolwezi les acteurs publics et privés qui travaillent sur le cobalt, le cuivre, le lithium, les métaux pour batteries, les zones économiques, la transformation locale et les infrastructures industrielles. Il s’affirme comme un rendez-vous récurrent de la stratégie d’industrialisation minière congolaise.",
            'image' => 'images/rarsm-generated/approvisionnement-responsable.png',
            'image_alt' => 'DRC Critical Minerals and Industrialisation Forum',
            'institution' => $institutions['critical-minerals-forum'],
            'institution_role' => "Ce forum constitue un cadre annuel de rencontres autour des minerais critiques, de la transformation locale et de l’industrialisation minière.",
            'highlights' => [
                'Forum annuel dédié au cobalt, au cuivre, au lithium et aux métaux pour batteries.',
                'Accent sur l’industrialisation locale et les investissements de chaîne de valeur.',
                'Organisation prévue à Kolwezi du 7 au 9 octobre 2026.',
            ],
            'reference' => 'VUKA Group',
            'reference_url' => 'https://wearevuka.com/critical-minerals-forum-our-purpose/',
            'recurrence_note' => 'Rendez-vous annuel spécialisé sur les minerais critiques et l’industrialisation locale.',
        ],
        'makutano-mining-2026' => [
            'id' => 'makutano-mining-2026',
            'title' => 'Makutano Mining 2026',
            'date' => '2026-11-22',
            'date_label' => '22 au 25 novembre 2026',
            'calendar_dates' => rarsm_activity_range('2026-11-22', '2026-11-25'),
            'time' => 'Forum sur quatre jours',
            'location' => 'Kinshasa · Fleuve Congo Hôtel & Pullman',
            'category' => 'launch',
            'summary' => "Le Makutano Forum est un rendez-vous économique annuel. En 2026, son édition Makutano Mining est dédiée aux minerais critiques et à la souveraineté minière.",
            'description' => "Makutano Mining 2026 correspond à la 12e édition du forum. Le programme annoncé à Kinshasa comprend des plénières, panels, rencontres bilatérales et signatures, avec un focus particulier sur les minerais critiques. L’événement reste économique dans son ADN, mais l’édition 2026 adopte un prisme minier affirmé.",
            'image' => 'images/rarsm-generated/hero-presentation-rarsm.png',
            'image_alt' => 'Makutano Mining 2026 à Kinshasa',
            'institution' => $institutions['makutano'],
            'institution_role' => "Makutano apporte ici un cadre annuel de haut niveau, à l’interface entre économie, investissement et souveraineté minière.",
            'highlights' => [
                'Forum économique annuel avec édition 2026 consacrée aux minerais critiques.',
                'Quatre jours de plénières, panels et signatures bilatérales.',
                'Événement prévu à Kinshasa du 22 au 25 novembre 2026.',
            ],
            'reference' => 'Makutano',
            'reference_url' => 'https://www.makutano.cd/en/agenda-2026',
            'recurrence_note' => "Makutano est un forum annuel. L’édition 2026 est spécialement centrée sur les enjeux miniers et les minerais critiques.",
        ],
    ];
}

function rarsm_get_activity(?string $id): ?array
{
    if ($id === null || $id === '') {
        return null;
    }

    $activities = rarsm_activity_records();

    return $activities[$id] ?? null;
}

function rarsm_default_activity(): array
{
    $activities = array_values(rarsm_activity_records());
    usort($activities, 'rarsm_compare_activity_dates');

    $today = new DateTimeImmutable('today');

    foreach ($activities as $activity) {
        $activityDate = DateTimeImmutable::createFromFormat('!Y-m-d', $activity['date']);
        if ($activityDate instanceof DateTimeImmutable && $activityDate >= $today) {
            return $activity;
        }
    }

    return $activities[0];
}

function rarsm_related_activities(string $currentId, int $limit = 4): array
{
    $activities = array_values(rarsm_activity_records());
    usort($activities, 'rarsm_compare_activity_dates');

    $related = [];
    foreach ($activities as $activity) {
        if ($activity['id'] === $currentId) {
            continue;
        }

        $related[] = $activity;
        if (count($related) >= $limit) {
            break;
        }
    }

    return $related;
}

function rarsm_compare_activity_dates(array $left, array $right): int
{
    $leftKey = $left['date'] . '|' . ($left['time'] ?? '') . '|' . $left['id'];
    $rightKey = $right['date'] . '|' . ($right['time'] ?? '') . '|' . $right['id'];

    return strcmp($leftKey, $rightKey);
}

function rarsm_activity_date_label(string $date): string
{
    $dateObject = DateTimeImmutable::createFromFormat('!Y-m-d', $date);
    if (!$dateObject instanceof DateTimeImmutable) {
        return $date;
    }

    $weekdays = [
        'Monday' => 'lundi',
        'Tuesday' => 'mardi',
        'Wednesday' => 'mercredi',
        'Thursday' => 'jeudi',
        'Friday' => 'vendredi',
        'Saturday' => 'samedi',
        'Sunday' => 'dimanche',
    ];

    $months = [
        1 => 'janvier',
        2 => 'février',
        3 => 'mars',
        4 => 'avril',
        5 => 'mai',
        6 => 'juin',
        7 => 'juillet',
        8 => 'août',
        9 => 'septembre',
        10 => 'octobre',
        11 => 'novembre',
        12 => 'décembre',
    ];

    $weekday = $weekdays[$dateObject->format('l')] ?? $dateObject->format('l');
    $month = $months[(int) $dateObject->format('n')] ?? $dateObject->format('m');

    return $weekday . ' ' . $dateObject->format('j') . ' ' . $month . ' ' . $dateObject->format('Y');
}

function rarsm_activity_display_date(array $activity): string
{
    if (!empty($activity['date_label'])) {
        return (string) $activity['date_label'];
    }

    return rarsm_activity_date_label((string) $activity['date']);
}

function rarsm_activity_initials(string $label): string
{
    $clean = preg_replace('/[^A-Za-z0-9 ]+/', ' ', $label) ?? $label;
    $parts = preg_split('/\s+/', trim($clean)) ?: [];
    $stopWords = ['de', 'des', 'du', 'la', 'le', 'les', 'et', 'd', 'l'];
    $initials = '';

    foreach ($parts as $part) {
        if ($part === '') {
            continue;
        }

        if (in_array(strtolower($part), $stopWords, true)) {
            continue;
        }

        $initials .= strtoupper(substr($part, 0, 1));

        if (strlen($initials) >= 4) {
            break;
        }
    }

    if ($initials === '') {
        $initials = strtoupper(substr(preg_replace('/\s+/', '', (string) $clean), 0, 3));
    }

    return $initials;
}
