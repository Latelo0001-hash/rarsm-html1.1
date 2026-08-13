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
            'details_href' => 'https://wearevuka.com/mining/critical-minerals-forum/',
            'directory_href' => 'activites.html',
            'website' => 'https://wearevuka.com/mining/critical-minerals-forum/',
            'socials' => [
                ['label' => 'Site officiel', 'href' => 'https://wearevuka.com/mining/critical-minerals-forum/', 'icon' => 'fa-globe'],
                ['label' => 'Programme', 'href' => 'https://wearevuka.com/mining/critical-minerals-forum/', 'icon' => 'fa-calendar'],
                ['label' => 'Contact', 'href' => 'contact.html', 'icon' => 'fa-envelope'],
            ],
        ],
        'makutano' => [
            'slug' => 'makutano',
            'name' => 'Makutano',
            'full_name' => 'Makutano Forum',
            'summary' => "Le Makutano Forum est un rendez-vous économique annuel. En 2026, l’édition Makutano Mining est consacrée aux minerais critiques et à la souveraineté minière.",
            'details_href' => 'https://www.makutano.cd/forum',
            'directory_href' => 'activites.html',
            'website' => 'https://www.makutano.cd/forum',
            'socials' => [
                ['label' => 'Site officiel', 'href' => 'https://www.makutano.cd/forum', 'icon' => 'fa-globe'],
                ['label' => 'Agenda 2026', 'href' => 'https://makutano.events/', 'icon' => 'fa-calendar'],
                ['label' => 'Contact', 'href' => 'contact.html', 'icon' => 'fa-envelope'],
            ],
        ],
        'sarw' => [
            'slug' => 'sarw',
            'name' => 'SARW',
            'full_name' => 'Southern Africa Resource Watch',
            'summary' => "SARW porte l’Alternative Mining Indaba en RDC afin de faire entendre la voix des communautés affectées par l’exploitation minière.",
            'details_href' => 'https://www.sarwatch.co.za/',
            'directory_href' => 'activites.html',
            'website' => 'https://www.sarwatch.co.za/',
            'socials' => [
                ['label' => 'Site officiel', 'href' => 'https://www.sarwatch.co.za/', 'icon' => 'fa-globe'],
                ['label' => 'Actualité', 'href' => 'https://www.radiookapi.net/2025/11/01/actualite/economie/cloture-lubumbashi-de-la-ix-edition-de-lalternative-mining-indaba', 'icon' => 'fa-newspaper-o'],
                ['label' => 'Contact', 'href' => 'contact.html', 'icon' => 'fa-envelope'],
            ],
        ],
        'glencore-rdc' => [
            'slug' => 'glencore-rdc',
            'name' => 'Glencore RDC',
            'full_name' => 'Glencore en République démocratique du Congo',
            'summary' => "Glencore organise à Kinshasa une conférence annuelle consacrée à ses opérations, aux standards miniers et à l’impact local.",
            'details_href' => 'https://www.glencore.cd/fr/news/glencore-hosts-seventh-annual-drc-conference',
            'directory_href' => 'activites.html',
            'website' => 'https://www.glencore.cd/fr',
            'socials' => [
                ['label' => 'Site officiel', 'href' => 'https://www.glencore.cd/fr', 'icon' => 'fa-globe'],
                ['label' => 'Conférence annuelle', 'href' => 'https://www.glencore.cd/fr/news/glencore-hosts-seventh-annual-drc-conference', 'icon' => 'fa-calendar'],
                ['label' => 'Contact', 'href' => 'contact.html', 'icon' => 'fa-envelope'],
            ],
        ],
        'international-wim' => [
            'slug' => 'international-wim',
            'name' => 'IWiM',
            'full_name' => 'International Women in Mining',
            'summary' => "International Women in Mining coordonne la Journée internationale des femmes dans les mines, célébrée chaque année le 15 juin.",
            'details_href' => 'https://internationalwim.org/idwim/',
            'directory_href' => 'activites.html',
            'website' => 'https://internationalwim.org/idwim/',
            'socials' => [
                ['label' => 'Site officiel', 'href' => 'https://internationalwim.org/idwim/', 'icon' => 'fa-globe'],
                ['label' => 'Campagne IDWIM', 'href' => 'https://internationalwim.org/idwim/', 'icon' => 'fa-calendar'],
                ['label' => 'Contact', 'href' => 'contact.html', 'icon' => 'fa-envelope'],
            ],
        ],
        'cdis' => [
            'slug' => 'cdis',
            'name' => 'CDIS',
            'full_name' => 'Congolese Diaspora Impact Strategies',
            'summary' => "CDIS organise Return to Congo, une mission d’immersion et de connexion entre la diaspora, les investisseurs et les acteurs économiques congolais.",
            'details_href' => 'https://cdiscongo.com/return-to-congo/',
            'directory_href' => 'activites.html',
            'website' => 'https://cdiscongo.com/return-to-congo/',
            'socials' => [
                ['label' => 'Site officiel', 'href' => 'https://cdiscongo.com/return-to-congo/', 'icon' => 'fa-globe'],
                ['label' => 'Programme', 'href' => 'https://cdiscongo.com/return-to-congo/', 'icon' => 'fa-calendar'],
                ['label' => 'Contact', 'href' => 'contact.html', 'icon' => 'fa-envelope'],
            ],
        ],
        'mining-explore' => [
            'slug' => 'mining-explore',
            'name' => 'Mining Explore',
            'full_name' => 'Mining Explore RDC',
            'summary' => "Mining Explore rapproche les étudiants, établissements, entreprises et professionnels autour des métiers et compétences de l’industrie minière.",
            'details_href' => 'https://www.mining-explore.com/',
            'directory_href' => 'activites.html',
            'website' => 'https://www.mining-explore.com/',
            'socials' => [
                ['label' => 'Site officiel', 'href' => 'https://www.mining-explore.com/', 'icon' => 'fa-globe'],
                ['label' => 'Programme 2026', 'href' => 'https://www.mining-explore.com/', 'icon' => 'fa-calendar'],
                ['label' => 'Contact', 'href' => 'contact.html', 'icon' => 'fa-envelope'],
            ],
        ],
        'wim-drc' => [
            'slug' => 'wim-drc',
            'name' => 'WIM RDC',
            'full_name' => 'Women in Mining DRC',
            'summary' => "Women in Mining DRC développe des espaces de formation, de plaidoyer et d’action pour les femmes actives dans l’écosystème minier congolais.",
            'details_href' => 'https://wim-drc.com/',
            'directory_href' => 'activites.html',
            'website' => 'https://wim-drc.com/',
            'socials' => [
                ['label' => 'Site officiel', 'href' => 'https://wim-drc.com/', 'icon' => 'fa-globe'],
                ['label' => 'Actualités', 'href' => 'https://wim-drc.com/', 'icon' => 'fa-newspaper-o'],
                ['label' => 'Contact', 'href' => 'contact.html', 'icon' => 'fa-envelope'],
            ],
        ],
        'leadership-minier-responsable' => [
            'slug' => 'leadership-minier-responsable',
            'name' => 'PLMR',
            'full_name' => 'Comité du Prix du Leadership Minier Responsable',
            'summary' => "Cette initiative distingue les institutions et dirigeants qui contribuent à la gouvernance, à la transparence et à la responsabilité du secteur minier congolais.",
            'details_href' => 'https://lequotidienrdc.com/prix-du-leadership-minier-responsable-edition-2025-louis-watum-couronne-cami-ceec-et-fomin/',
            'directory_href' => 'activites.html',
            'website' => 'https://lequotidienrdc.com/prix-du-leadership-minier-responsable-edition-2025-louis-watum-couronne-cami-ceec-et-fomin/',
            'socials' => [
                ['label' => 'Compte rendu', 'href' => 'https://lequotidienrdc.com/prix-du-leadership-minier-responsable-edition-2025-louis-watum-couronne-cami-ceec-et-fomin/', 'icon' => 'fa-newspaper-o'],
                ['label' => 'Contact', 'href' => 'contact.html', 'icon' => 'fa-envelope'],
            ],
        ],
        'province-lualaba' => [
            'slug' => 'province-lualaba',
            'name' => 'Lualaba',
            'full_name' => 'Province du Lualaba',
            'summary' => "La Province du Lualaba a accueilli le Salon minier de 2023 au Centre de négoce de Musompo à Kolwezi.",
            'details_href' => 'https://www.provincelualaba.cd/',
            'directory_href' => 'activites.html',
            'website' => 'https://www.provincelualaba.cd/',
            'socials' => [
                ['label' => 'Site institutionnel', 'href' => 'https://www.provincelualaba.cd/', 'icon' => 'fa-globe'],
                ['label' => 'Compte rendu 2023', 'href' => 'https://actualite.cd/2023/10/08/salon-minier-lualaba-2023-le-stand-de-tfm-recoit-la-visite-de-sem-president-de-la', 'icon' => 'fa-newspaper-o'],
                ['label' => 'Contact', 'href' => 'contact.html', 'icon' => 'fa-envelope'],
            ],
        ],
        'ccps-rdc' => [
            'slug' => 'ccps-rdc',
            'name' => 'CCPS-RDC',
            'full_name' => 'Comité de crise pour la paix et la sécurité en RDC',
            'summary' => "Le CCPS-RDC, avec DYCO-RDC et les pouvoirs publics, a lancé le Forum national des coopératives minières à Kinshasa.",
            'details_href' => 'https://surveillance.cd/2026/03/20/rdc-les-cooperatives-minieres-se-dotent-dune-confederation-nationale-pour-structurer-le-secteur-artisanal/',
            'directory_href' => 'activites.html',
            'website' => 'https://surveillance.cd/2026/03/20/rdc-les-cooperatives-minieres-se-dotent-dune-confederation-nationale-pour-structurer-le-secteur-artisanal/',
            'socials' => [
                ['label' => 'Compte rendu', 'href' => 'https://surveillance.cd/2026/03/20/rdc-les-cooperatives-minieres-se-dotent-dune-confederation-nationale-pour-structurer-le-secteur-artisanal/', 'icon' => 'fa-newspaper-o'],
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
            'verification_status' => 'confirmed',
            'status_label' => 'Récurrence confirmée',
            'summary' => "Le Katanga Business Meeting est l’un des rendez-vous annuels du sud de la RDC pour les investisseurs, les décideurs publics et les entreprises actives dans les mines, l’énergie et les infrastructures.",
            'description' => "Le KBM 2026 met en relation les acteurs économiques de Kolwezi et du Lualaba avec les opérateurs miniers, les financeurs et les partenaires de sous-traitance. Il se distingue par son ancrage régional et par la place accordée aux opportunités d’affaires autour du secteur extractif. Pour les entreprises locales, le forum constitue aussi un espace utile pour présenter leurs solutions, identifier de nouveaux partenaires et mieux comprendre les besoins des grands projets régionaux.",
            'image' => 'images/activities/kbm-2026.png',
            'image_alt' => 'Katanga Business Meeting à Kolwezi',
            'image_credit' => 'Photo : Katanga Business Meeting',
            'image_credit_url' => 'https://www.kbm-rdc.com/',
            'institution' => $institutions['kbm'],
            'institution_role' => "Le KBM agit ici comme plateforme annuelle de rencontres économiques, avec un fort ancrage minier dans le sud de la RDC.",
            'highlights' => [
                'Forum économique annuel organisé à Kolwezi.',
                'Forte place accordée aux mines, à l’énergie et aux infrastructures.',
                'L’édition 2027 est déjà annoncée du 19 au 21 mai 2027.',
                'Réseautage entre investisseurs, donneurs d’ordre et entreprises locales.',
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
            'verification_status' => 'confirmed',
            'status_label' => 'Récurrence confirmée',
            'summary' => "La DRC Mining Week est le grand rendez-vous annuel du secteur minier congolais, combinant conférence, exposition, ateliers techniques et rencontres d’affaires.",
            'description' => "Cette manifestation réunit chaque année à Lubumbashi les autorités, compagnies minières, investisseurs, fournisseurs, EPC et partenaires techniques. Elle structure une part importante de l’agenda minier congolais en offrant un espace de visibilité, de négociation et de mise en réseau à l’échelle nationale et régionale. Les participants peuvent y suivre les priorités du secteur, comparer les projets en développement et découvrir des solutions techniques, financières et opérationnelles adaptées au marché congolais.",
            'image' => 'images/activities/drc-mining-week-2026.jpg',
            'image_alt' => 'DRC Mining Week à Lubumbashi',
            'image_credit' => 'Photo : Actualite.cd · DRC Mining Week 2026',
            'image_credit_url' => 'https://actualite.cd/2026/06/18/drc-mining-week-2026-mmg-kinsevere-mise-sur-la-jeunesse-congolaise-avec-son-ambitieux',
            'institution' => $institutions['drc-mining-week'],
            'institution_role' => "La DRC Mining Week sert de cadre annuel de référence pour plusieurs sous-événements spécialisés dédiés au leadership, aux investissements et au développement régional.",
            'highlights' => [
                'Grande conférence et exposition minière de la RDC.',
                'Rendez-vous majeur pour opérateurs, pouvoirs publics et investisseurs.',
                'L’édition 2027 est annoncée du 16 au 18 juin 2027 à Lubumbashi.',
                'Panels, exposition, rendez-vous d’affaires et partage d’expertise sectorielle.',
            ],
            'reference' => 'VUKA Group',
            'reference_url' => 'https://wearevuka.com/mining/drc-mining-week/',
            'recurrence_note' => 'Événement annuel. Le site officiel annonce déjà l’édition suivante du 16 au 18 juin 2027 au Pullman Lubumbashi Grand Karavia.',
        ],
        'women-mines-leadership-2026' => [
            'id' => 'women-mines-leadership-2026',
            'title' => 'Women Mines & Leadership Forum',
            'date' => '2026-06-19',
            'date_label' => '19 juin 2026 (dans la DRC Mining Week)',
            'time' => 'Programme thématique',
            'location' => 'Lubumbashi',
            'category' => 'signing',
            'verification_status' => 'confirmed',
            'status_label' => 'Récurrence confirmée',
            'summary' => "Ce rendez-vous annuel, intégré à la DRC Mining Week, met en avant les femmes dirigeantes, entrepreneures et professionnelles de l’industrie extractive.",
            'description' => "Le Women Mines & Leadership Forum donne de la visibilité aux parcours féminins dans le secteur minier et crée un espace d’échanges sur le leadership, la représentation, l’entrepreneuriat et les dynamiques d’inclusion dans l’écosystème extractif. Les discussions permettent également d’aborder l’accès aux postes de décision, le mentorat, le développement des carrières et les opportunités d’affaires portées par les femmes.",
            'image' => 'images/activities/women-mines-leadership-2026.jpg',
            'image_alt' => 'Women Mines and Leadership Forum',
            'image_credit' => 'Photo : Mining Review Africa · Women Mines & Leadership',
            'image_credit_url' => 'https://www.linkedin.com/posts/mining-review-africa_drcminingweek-womeninmining-leadershipinmining-activity-7338180590044602368-NTVI',
            'institution' => $institutions['drc-mining-week'],
            'institution_role' => "Ce forum s’inscrit dans le programme annuel de la DRC Mining Week comme espace de valorisation du leadership féminin dans l’industrie minière.",
            'highlights' => [
                'Rencontres dédiées aux femmes dirigeantes et professionnelles du secteur.',
                'Cadre de discussion sur le leadership et l’inclusion dans l’extractif.',
                'Sous-événement annuel intégré à la DRC Mining Week.',
                'Mise en valeur des parcours, des réseaux professionnels et du mentorat.',
            ],
            'reference' => 'VUKA Group',
            'reference_url' => 'https://wearevuka.com/mining/drc-mining-week/',
            'recurrence_note' => 'Rendez-vous annuel organisé dans le cadre de la DRC Mining Week.',
        ],
        'ceo-roundtable-2026' => [
            'id' => 'ceo-roundtable-2026',
            'title' => 'CEO Roundtable de la DRC Mining Week',
            'date' => '2026-06-19',
            'date_label' => '19 juin 2026 (sur invitation, dans la DRC Mining Week)',
            'time' => 'Session sur invitation',
            'location' => 'Lubumbashi',
            'category' => 'signing',
            'verification_status' => 'confirmed',
            'status_label' => 'Récurrence confirmée',
            'summary' => "La CEO Roundtable est la table ronde annuelle de la DRC Mining Week réservée aux dirigeants des entreprises minières, aux investisseurs et aux décideurs publics.",
            'description' => "Cette session fermée met l’accent sur la confiance des investisseurs, la lecture du risque et les arbitrages stratégiques des grands décideurs de l’industrie. Elle fait partie des temps forts annuels les plus sélectifs de la DRC Mining Week. Son format favorise un dialogue direct sur les contraintes opérationnelles, les perspectives d’investissement et les conditions nécessaires à une croissance durable du secteur.",
            'image' => 'images/activities/ceo-roundtable-2026.jpg',
            'image_alt' => 'CEO Roundtable de la DRC Mining Week',
            'image_credit' => 'Photo : DRC Mining Week · CEO Roundtable',
            'image_credit_url' => 'https://fr.linkedin.com/posts/drc-mining-week2_drcminingweek-drcminingweek-leadershipminier-activity-7338929440413872129-A_UE',
            'institution' => $institutions['drc-mining-week'],
            'institution_role' => "La CEO Roundtable est portée par la DRC Mining Week comme espace annuel de dialogue stratégique entre décideurs de haut niveau.",
            'highlights' => [
                'Table ronde annuelle réservée aux dirigeants.',
                'Accent sur l’investissement, le risque et la confiance du marché.',
                'Accès sur invitation uniquement.',
                'Dialogue stratégique entre dirigeants privés et décideurs publics.',
            ],
            'reference' => 'VUKA Group',
            'reference_url' => 'https://wearevuka.com/mining/drc-mining-week/',
            'recurrence_note' => 'Rendez-vous annuel organisé dans le cadre de la DRC Mining Week.',
        ],
        'regional-development-forum-2026' => [
            'id' => 'regional-development-forum-2026',
            'title' => 'Regional Development Forum',
            'date' => '2026-06-19',
            'date_label' => '19 juin 2026 (dans la DRC Mining Week)',
            'time' => 'Forum thématique',
            'location' => 'Lubumbashi',
            'category' => 'media',
            'verification_status' => 'confirmed',
            'status_label' => 'Récurrence confirmée',
            'summary' => "Le Regional Development Forum fait partie des rendez-vous récurrents de la DRC Mining Week consacrés aux infrastructures, à l’énergie, à la logistique et au développement des régions minières.",
            'description' => "Ce forum examine les conditions de développement des zones minières au-delà de la seule production minérale. Il aborde les infrastructures, l’énergie, la mobilité, la logistique et les effets territoriaux des projets extractifs. Il met ainsi en relation les besoins des provinces, des communautés et des opérateurs avec les solutions capables d’améliorer durablement l’attractivité et la connectivité des territoires miniers.",
            'image' => 'images/activities/regional-development-forum-2026.jpg',
            'image_alt' => 'Regional Development Forum',
            'image_credit' => 'Photo : Radio Okapi · DRC Mining Week',
            'image_credit_url' => 'https://www.radiookapi.net/2025/06/15/actualite/societe/drc-mining-week-plaidoyer-du-bcnudh-pour-une-exploitation-miniere-verte',
            'institution' => $institutions['drc-mining-week'],
            'institution_role' => "Ce forum complète la DRC Mining Week par une lecture annuelle des besoins de développement des régions minières.",
            'highlights' => [
                'Discussion sur les infrastructures et l’énergie des zones minières.',
                'Vision territoriale du développement régional.',
                'Sous-événement annuel intégré à la DRC Mining Week.',
                'Recherche de solutions partagées pour les territoires et communautés minières.',
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
            'verification_status' => 'confirmed',
            'status_label' => 'Récurrence confirmée',
            'summary' => "Le Value-Chain Investment Forum rassemble chaque année, dans le cadre de la DRC Mining Week, les acteurs intéressés par les investissements et la chaîne de valeur minière congolaise.",
            'description' => "Ce rendez-vous met l’accent sur les projets de transformation, de financement, de sous-traitance, de logistique et de valorisation locale. Il sert de point de rencontre pour les investisseurs qui suivent l’évolution de la chaîne de valeur minière en RDC. Le forum aide aussi à mieux lire les besoins d’investissement depuis l’extraction jusqu’à la transformation, aux services industriels et à l’accès aux marchés.",
            'image' => 'images/activities/value-chain-investment-forum-2026.jpg',
            'image_alt' => 'Value-Chain Investment Forum',
            'image_credit' => 'Photo : Mines.cd · DRC Mining Week',
            'image_credit_url' => 'https://mines.cd/rdc-popol-mabolia-prone-la-vision-dun-secteur-minier-reapproprie-integre-et-digitalise/',
            'institution' => $institutions['drc-mining-week'],
            'institution_role' => "Ce forum représente la dimension annuelle d’investissement et de chaîne de valeur de la DRC Mining Week.",
            'highlights' => [
                'Rencontres consacrées aux investissements dans la chaîne de valeur minière.',
                'Focus sur la transformation locale et les partenariats industriels.',
                'Sous-événement annuel intégré à la DRC Mining Week.',
                'Mise en relation de porteurs de projets, financeurs et partenaires techniques.',
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
            'verification_status' => 'confirmed',
            'status_label' => 'Récurrence confirmée',
            'summary' => "Ce forum annuel, anciennement connu sous le nom de DRC-Africa Battery Metals Forum, est consacré aux métaux critiques, à l’industrialisation et aux investissements en aval.",
            'description' => "Le DRC Critical Minerals & Industrialisation Forum réunit à Kolwezi les acteurs publics et privés qui travaillent sur le cobalt, le cuivre, le lithium, les métaux pour batteries, les zones économiques, la transformation locale et les infrastructures industrielles. Il s’affirme comme un rendez-vous récurrent de la stratégie d’industrialisation minière congolaise. Les échanges permettent de suivre les politiques publiques, les besoins de financement, les technologies disponibles et les partenariats nécessaires pour créer davantage de valeur en RDC.",
            'image' => 'images/activities/critical-minerals-forum-2026.jpg',
            'image_alt' => 'DRC Critical Minerals and Industrialisation Forum',
            'image_credit' => 'Photo : VUKA Group / openPR',
            'image_credit_url' => 'https://www.openpr.com/news/4390184/mobilising-momentum-for-drc-s-industrialisation-new-critical',
            'institution' => $institutions['critical-minerals-forum'],
            'institution_role' => "Ce forum constitue un cadre annuel de rencontres autour des minerais critiques, de la transformation locale et de l’industrialisation minière.",
            'highlights' => [
                'Forum annuel dédié au cobalt, au cuivre, au lithium et aux métaux pour batteries.',
                'Accent sur l’industrialisation locale et les investissements de chaîne de valeur.',
                'Organisation prévue à Kolwezi du 7 au 9 octobre 2026.',
                'Dialogue sur le financement, la technologie et les partenariats industriels.',
            ],
            'reference' => 'VUKA Group',
            'reference_url' => 'https://wearevuka.com/mining/critical-minerals-forum/',
            'recurrence_note' => 'Rendez-vous annuel spécialisé sur les minerais critiques et l’industrialisation locale. Les pages publiques consultées présentent encore une divergence sur la durée et le lieu de l’édition 2026 ; une vérification est recommandée avant déplacement.',
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
            'verification_status' => 'watch',
            'status_label' => 'Édition minière à surveiller',
            'summary' => "Le Makutano Forum est un rendez-vous économique annuel. En 2026, son édition Makutano Mining est dédiée aux minerais critiques et à la souveraineté minière.",
            'description' => "Makutano Mining 2026 correspond à la 12e édition du forum. Le programme annoncé à Kinshasa comprend des plénières, panels, rencontres bilatérales et signatures, avec un focus particulier sur les minerais critiques. L’événement reste économique dans son ADN, mais l’édition 2026 adopte un prisme minier affirmé. Cette orientation ouvre un dialogue de haut niveau sur la souveraineté, la transformation locale et la manière dont la RDC peut mieux capter la valeur de ses ressources stratégiques.",
            'image' => 'images/activities/makutano-mining-2026.jpg',
            'image_alt' => 'Makutano Mining 2026 à Kinshasa',
            'image_credit' => 'Photo : Makutano',
            'image_credit_url' => 'https://www.makutano.cd/forum',
            'institution' => $institutions['makutano'],
            'institution_role' => "Makutano apporte ici un cadre annuel de haut niveau, à l’interface entre économie, investissement et souveraineté minière.",
            'highlights' => [
                'Forum économique annuel avec édition 2026 consacrée aux minerais critiques.',
                'Quatre jours de plénières, panels et signatures bilatérales.',
                'Événement prévu à Kinshasa du 22 au 25 novembre 2026.',
                'Réflexion sur la souveraineté minière et la création de valeur nationale.',
            ],
            'reference' => 'Makutano',
            'reference_url' => 'https://www.makutano.cd/forum',
            'recurrence_note' => "Makutano est un forum annuel. L’édition 2026 est spécialement centrée sur les enjeux miniers et les minerais critiques.",
        ],
        'energy-investment-forum-2026' => [
            'id' => 'energy-investment-forum-2026',
            'title' => 'Energy Investment Forum 2026',
            'date' => '2026-06-16',
            'date_label' => '16 juin 2026',
            'time' => 'Préconférence · 11h00 à 17h00',
            'location' => 'Lubumbashi Golf Club',
            'category' => 'institution',
            'verification_status' => 'confirmed',
            'status_label' => 'Récurrence confirmée',
            'summary' => "Cette préconférence de la DRC Mining Week réunit les sociétés minières, énergéticiens, investisseurs et pouvoirs publics autour de la sécurité énergétique.",
            'description' => "Le forum examine les déficits de capacité, les infrastructures électriques prioritaires, les modèles de financement et les collaborations régionales nécessaires pour alimenter durablement les mines et l’industrialisation en RDC. Il offre aux participants une lecture concrète des besoins énergétiques du secteur et des projets susceptibles de mobiliser des capitaux, des technologies et des partenariats de long terme.",
            'image' => 'images/activities/energy-investment-forum-2026.jpg',
            'image_alt' => 'Participants de la DRC Mining Week autour des enjeux énergétiques',
            'image_credit' => 'Photo : Kamoa Copper · DRC Mining Week 2025',
            'image_credit_url' => 'https://www.kamoacopper.com/en/kamoa-copper-sa-diamond-plus-sponsor-makes-its-mark-at-drc-mining-week-2025/',
            'institution' => $institutions['drc-mining-week'],
            'institution_role' => "L’Energy Investment Forum ouvre la semaine de conférences de la DRC Mining Week et place l’énergie au cœur de la compétitivité minière.",
            'highlights' => [
                'Préconférence consacrée à la sécurité énergétique des mines.',
                'Échanges sur les infrastructures, les financements et les partenariats régionaux.',
                'Édition 2026 annoncée au Lubumbashi Golf Club.',
                'Identification de solutions énergétiques adaptées aux besoins industriels.',
            ],
            'reference' => 'Programme officiel DRC Mining Week',
            'reference_url' => 'https://wearevuka.com/mining/drc-mining-week/programme/',
            'recurrence_note' => 'Forum spécialisé présent dans les programmes successifs de la DRC Mining Week.',
        ],
        'alternative-mining-indaba-2025' => [
            'id' => 'alternative-mining-indaba-2025',
            'title' => 'DRC Alternative Mining Indaba',
            'date' => '2025-10-29',
            'date_label' => '29 au 31 octobre 2025 · dernière édition documentée',
            'calendar_dates' => rarsm_activity_range('2025-10-29', '2025-10-31'),
            'time' => 'Forum sur trois jours',
            'location' => 'Pullman Grand Karavia · Lubumbashi',
            'category' => 'media',
            'verification_status' => 'confirmed',
            'status_label' => 'Récurrence confirmée',
            'summary' => "L’Alternative Mining Indaba/RDC défend la participation des communautés et de la société civile dans la gouvernance des ressources minières.",
            'description' => "La neuvième édition a réuni les communautés affectées, les organisations de la société civile et les parties prenantes publiques autour des minerais stratégiques, de leurs enjeux géopolitiques et de leurs impacts socio-économiques locaux. Ce cadre permet de faire remonter les préoccupations liées au contenu local, aux droits communautaires, aux impacts environnementaux et au partage plus équitable des bénéfices de l’exploitation.",
            'image' => 'images/activities/alternative-mining-indaba-2025.jpg',
            'image_alt' => 'Bannière de la neuvième édition de l’Alternative Mining Indaba RDC',
            'image_credit' => 'Photo : Radio Okapi',
            'image_credit_url' => 'https://www.radiookapi.net/2025/11/01/actualite/economie/cloture-lubumbashi-de-la-ix-edition-de-lalternative-mining-indaba',
            'institution' => $institutions['sarw'],
            'institution_role' => "SARW et ses partenaires portent ce forum comme espace alternatif d’expression des communautés minières congolaises.",
            'highlights' => [
                'Neuvième édition organisée en 2025.',
                'Participation de communautés minières et d’organisations de la société civile.',
                'Aucune date nationale 2026 clairement publiée au moment de la vérification.',
                'Plaidoyer pour une gouvernance minière plus inclusive et redevable.',
            ],
            'reference' => 'Radio Okapi / SARW',
            'reference_url' => 'https://www.radiookapi.net/2025/11/01/actualite/economie/cloture-lubumbashi-de-la-ix-edition-de-lalternative-mining-indaba',
            'recurrence_note' => 'Forum national récurrent. La date affichée correspond à la dernière édition publiquement documentée.',
        ],
        'glencore-conference-2025' => [
            'id' => 'glencore-conference-2025',
            'title' => 'Conférence annuelle Glencore RDC',
            'date' => '2025-11-04',
            'date_label' => '4 novembre 2025 · dernière édition documentée',
            'time' => 'Conférence annuelle',
            'location' => 'Hilton Kinshasa',
            'category' => 'institution',
            'verification_status' => 'confirmed',
            'status_label' => 'Récurrence confirmée',
            'summary' => "Glencore RDC présente chaque année ses opérations, ses résultats, ses standards et ses engagements auprès des parties prenantes congolaises.",
            'description' => "La septième conférence annuelle a porté sur le passage des normes mondiales à l’impact local, avec la participation d’autorités, de responsables miniers, de communautés et de partenaires de développement. La rencontre fournit aux parties prenantes une vue d’ensemble des opérations et crée un espace de dialogue sur la sécurité, la responsabilité, les résultats locaux et les attentes des communautés.",
            'image' => 'images/activities/glencore-conference-2025.jpg',
            'image_alt' => 'Participants à la conférence annuelle de Glencore en RDC',
            'image_credit' => 'Photo : Actualite.cd · conférence Glencore 2024',
            'image_credit_url' => 'https://actualite.cd/index.php/2024/10/16/conference-annuelle-de-glencore-en-rdc-kinshasa-2024',
            'institution' => $institutions['glencore-rdc'],
            'institution_role' => "Glencore utilise cette conférence annuelle comme cadre de dialogue avec les institutions, les communautés et l’écosystème minier congolais.",
            'highlights' => [
                'Septième édition tenue à Kinshasa en 2025.',
                'Dialogue sur les normes, l’impact local et l’exploitation minière responsable.',
                'Date 2026 non encore annoncée publiquement lors de la vérification.',
                'Échanges directs entre l’entreprise, les autorités et les parties prenantes.',
            ],
            'reference' => 'Glencore RDC',
            'reference_url' => 'https://www.glencore.cd/fr/news/glencore-hosts-seventh-annual-drc-conference',
            'recurrence_note' => 'Conférence annuelle confirmée par sept éditions successives. La date affichée est celle de la dernière édition documentée.',
        ],
        'idwim-2026' => [
            'id' => 'idwim-2026',
            'title' => 'Journée internationale des femmes dans les mines',
            'date' => '2026-06-15',
            'date_label' => '15 juin 2026',
            'time' => 'Journée internationale',
            'location' => 'RDC · activités locales variables',
            'category' => 'signing',
            'verification_status' => 'confirmed',
            'status_label' => 'Récurrence confirmée',
            'summary' => "L’International Day of Women in Mining célèbre chaque 15 juin les contributions, les compétences et le leadership des femmes dans l’industrie minière.",
            'description' => "Cette campagne mondiale sert de point de ralliement aux organisations, entreprises et réseaux WIM. En RDC, les activités locales peuvent varier selon les villes et les organisations participantes. Cette journée peut notamment accueillir des conférences, témoignages, actions de mentorat et initiatives de sensibilisation destinées à renforcer la visibilité et la progression professionnelle des femmes.",
            'image' => 'images/activities/idwim-2026.png',
            'image_alt' => 'Visuel officiel de la campagne International Day of Women in Mining',
            'image_credit' => 'Visuel : International Women in Mining',
            'image_credit_url' => 'https://internationalwim.org/idwim/',
            'institution' => $institutions['international-wim'],
            'institution_role' => "International Women in Mining coordonne la campagne mondiale et met des ressources à la disposition des réseaux nationaux et locaux.",
            'highlights' => [
                'Célébration mondiale fixée chaque année au 15 juin.',
                'Valorisation des parcours et du leadership des femmes dans les mines.',
                'Programmation locale en RDC à confirmer auprès des réseaux participants.',
                'Occasion de mobiliser les entreprises autour d’engagements concrets pour l’inclusion.',
            ],
            'reference' => 'International Women in Mining',
            'reference_url' => 'https://internationalwim.org/idwim/',
            'recurrence_note' => 'Journée internationale célébrée annuellement le 15 juin.',
        ],
        'return-to-congo-2026' => [
            'id' => 'return-to-congo-2026',
            'title' => 'Return to Congo 2026',
            'date' => '2026-05-17',
            'date_label' => '17 au 23 mai 2026',
            'calendar_dates' => rarsm_activity_range('2026-05-17', '2026-05-23'),
            'time' => 'Mission d’immersion sur sept jours',
            'location' => 'Kolwezi et Lubumbashi',
            'category' => 'institution',
            'verification_status' => 'watch',
            'status_label' => 'Récurrence minière à confirmer',
            'summary' => "Return to Congo 2026 connecte la diaspora congolaise aux acteurs du Grand Katanga avec un accent particulier sur les mines et l’investissement.",
            'description' => "La mission est alignée sur le Katanga Business Meeting et aborde les minéraux critiques, les infrastructures, le financement, les talents et la géopolitique. Le programme Return to Congo est récurrent, mais son orientation exclusivement minière dépend de l’édition. L’immersion cherche surtout à transformer les échanges avec la diaspora en connexions professionnelles, projets d’investissement, transferts de compétences et partenariats durables.",
            'image' => 'images/activities/return-to-congo-2026.jpg',
            'image_alt' => 'Participants à une mission Return to Congo',
            'image_credit' => 'Image vidéo : CDIS · Return to Congo',
            'image_credit_url' => 'https://cdiscongo.com/return-to-congo/',
            'institution' => $institutions['cdis'],
            'institution_role' => "CDIS organise une expérience de terrain destinée à rapprocher la diaspora des opportunités économiques et des acteurs locaux.",
            'highlights' => [
                'Mission annoncée à Kolwezi et Lubumbashi.',
                'Édition 2026 spécialement axée sur le secteur minier.',
                'Alignement stratégique avec le Katanga Business Meeting.',
                'Mobilisation de la diaspora autour de l’investissement et des compétences.',
            ],
            'reference' => 'CDIS',
            'reference_url' => 'https://cdiscongo.com/return-to-congo/',
            'recurrence_note' => 'Return to Congo est une mission récurrente ; la permanence de son orientation minière reste à confirmer après 2026.',
        ],
        'mining-explore-2026' => [
            'id' => 'mining-explore-2026',
            'title' => 'Mining Explore 2026',
            'date' => '2026-08-12',
            'date_label' => '12 au 14 août 2026',
            'calendar_dates' => rarsm_activity_range('2026-08-12', '2026-08-14'),
            'time' => 'Immersion et orientation sur trois jours',
            'location' => 'Centre de négoce de Musompo · Kolwezi',
            'category' => 'media',
            'verification_status' => 'watch',
            'status_label' => 'Première édition',
            'summary' => "Mining Explore rapproche l’enseignement et l’industrie afin de préparer les jeunes aux métiers, technologies et opportunités de la chaîne de valeur minière.",
            'description' => "L’événement rassemble étudiants, établissements, entreprises minières et professionnels autour de l’orientation, de l’immersion terrain et du développement des compétences locales dans le Lualaba. Il permet aux jeunes de mieux découvrir les métiers de la chaîne minière, tandis que les entreprises peuvent présenter leurs besoins en compétences, leurs parcours de recrutement et les évolutions technologiques du secteur.",
            'image' => 'images/activities/mining-explore-2026.webp',
            'image_alt' => 'Visuel officiel Mining Explore représentant des étudiants sur un site minier',
            'image_credit' => 'Visuel : Mining Explore',
            'image_credit_url' => 'https://www.mining-explore.com/',
            'institution' => $institutions['mining-explore'],
            'institution_role' => "Mining Explore se présente comme une passerelle entre la formation académique, l’orientation professionnelle et les réalités du secteur extractif.",
            'highlights' => [
                'Première édition annoncée à Musompo.',
                'Public cible : élèves, étudiants, établissements et recruteurs.',
                'Trois journées d’immersion et de rencontres professionnelles.',
                'Rapprochement entre les programmes de formation et les besoins des employeurs.',
            ],
            'reference' => 'Mining Explore',
            'reference_url' => 'https://www.mining-explore.com/',
            'recurrence_note' => 'Première édition en 2026 ; la récurrence annuelle n’est pas encore établie.',
        ],
        'wim-haut-katanga-2026' => [
            'id' => 'wim-haut-katanga-2026',
            'title' => 'Women in Mining Haut-Katanga',
            'date' => '2026-05-13',
            'date_label' => '13 mai 2026',
            'time' => 'Forum sur une journée',
            'location' => 'Pullman Grand Karavia · Lubumbashi',
            'category' => 'signing',
            'verification_status' => 'watch',
            'status_label' => 'Première édition',
            'summary' => "Ce forum provincial met la gouvernance transformationnelle et l’inclusion des femmes au centre du dialogue dans l’industrie extractive.",
            'description' => "La première édition a réuni autorités publiques, entreprises minières et société civile pour formuler des recommandations, renforcer le leadership féminin et favoriser l’accès des femmes aux postes de décision. Le forum contribue ainsi à faire évoluer le débat vers des actions concrètes en matière de mentorat, de développement de carrière, de représentation et de participation économique.",
            'image' => 'images/activities/wim-haut-katanga-2026.jpeg',
            'image_alt' => 'Participants au forum Women in Mining Haut-Katanga à Lubumbashi',
            'image_credit' => 'Photo : Actualite.cd',
            'image_credit_url' => 'https://actualite.cd/2026/05/15/wim-haut-katanga-de-la-revendication-laction-pour-linclusion-des-femmes-dans-lindustrie',
            'institution' => $institutions['wim-drc'],
            'institution_role' => "L’antenne Haut-Katanga de Women in Mining anime ce cadre provincial de plaidoyer, de recommandations et d’action collective.",
            'highlights' => [
                'Première édition organisée à Lubumbashi en 2026.',
                'Thème : gouvernance transformationnelle et inclusion.',
                'Participation d’autorités, d’entreprises et de la société civile.',
                'Recherche de mesures concrètes pour renforcer la représentation des femmes.',
            ],
            'reference' => 'Actualite.cd',
            'reference_url' => 'https://actualite.cd/2026/05/15/wim-haut-katanga-de-la-revendication-laction-pour-linclusion-des-femmes-dans-lindustrie',
            'recurrence_note' => 'Première édition documentée en 2026 ; la prochaine édition n’est pas encore annoncée.',
        ],
        'prix-leadership-minier-2026' => [
            'id' => 'prix-leadership-minier-2026',
            'title' => 'Prix du Leadership Minier Responsable',
            'date' => '2026-02-18',
            'date_label' => '18 février 2026',
            'time' => 'Cérémonie de remise des prix',
            'location' => 'Fleuve Congo Hôtel · Kinshasa',
            'category' => 'signing',
            'verification_status' => 'watch',
            'status_label' => 'Première édition',
            'summary' => "Le Prix du Leadership Minier Responsable distingue les institutions et dirigeants qui contribuent à la bonne gouvernance et à la performance du secteur.",
            'description' => "La première cérémonie a récompensé notamment le CAMI, le CEEC et le FOMIN, avec un accent sur l’éthique, la transparence, la conformité et la contribution au développement national. Au-delà de la distinction, l’initiative cherche à valoriser les bonnes pratiques et à encourager une culture de responsabilité, de performance et de redevabilité dans l’écosystème minier.",
            'image' => 'images/activities/prix-leadership-minier-2026.jpeg',
            'image_alt' => 'Lauréats du Prix du Leadership Minier Responsable avec le ministre des Mines',
            'image_credit' => 'Photo : Le Quotidien RDC / Ministère des Mines',
            'image_credit_url' => 'https://lequotidienrdc.com/prix-du-leadership-minier-responsable-edition-2025-louis-watum-couronne-cami-ceec-et-fomin/',
            'institution' => $institutions['leadership-minier-responsable'],
            'institution_role' => "Le comité pluridisciplinaire du prix valorise l’excellence, l’éthique et la responsabilité dans la gestion du secteur minier.",
            'highlights' => [
                'Première cérémonie tenue à Kinshasa en 2026.',
                'CAMI, CEEC et FOMIN figurent parmi les lauréats.',
                'Initiative consacrée à la gouvernance et à la transparence.',
                'Valorisation des pratiques responsables et des performances institutionnelles.',
            ],
            'reference' => 'Le Quotidien RDC',
            'reference_url' => 'https://lequotidienrdc.com/prix-du-leadership-minier-responsable-edition-2025-louis-watum-couronne-cami-ceec-et-fomin/',
            'recurrence_note' => 'Première édition ; la tenue annuelle d’une prochaine cérémonie reste à confirmer.',
        ],
        'salon-minier-lualaba-2023' => [
            'id' => 'salon-minier-lualaba-2023',
            'title' => 'Salon minier du Lualaba',
            'date' => '2023-10-05',
            'date_label' => '5 au 6 octobre 2023 · dernière édition documentée',
            'calendar_dates' => rarsm_activity_range('2023-10-05', '2023-10-06'),
            'time' => 'Salon sur deux jours',
            'location' => 'Centre de négoce de Musompo · Kolwezi',
            'category' => 'launch',
            'verification_status' => 'watch',
            'status_label' => 'Continuité à confirmer',
            'summary' => "Le Salon minier du Lualaba a réuni opérateurs, institutions et exposants autour de l’exploitation responsable et du développement économique local.",
            'description' => "La première édition documentée s’est tenue au Centre de négoce de Musompo avec des stands d’entreprises minières et une visite officielle du président de la République. Le format salon permet aux opérateurs, fournisseurs et institutions de présenter leurs activités, de comparer les offres et de renforcer les relations économiques autour du bassin minier du Lualaba.",
            'image' => 'images/activities/salon-minier-lualaba-2023.jpeg',
            'image_alt' => 'Visite d’un stand au Salon minier du Lualaba 2023',
            'image_credit' => 'Photo : Actualite.cd',
            'image_credit_url' => 'https://actualite.cd/2023/10/08/salon-minier-lualaba-2023-le-stand-de-tfm-recoit-la-visite-de-sem-president-de-la',
            'institution' => $institutions['province-lualaba'],
            'institution_role' => "La Province du Lualaba a accueilli ce salon comme vitrine des acteurs et des enjeux miniers du territoire.",
            'highlights' => [
                'Première édition documentée au Centre de négoce de Musompo.',
                'Présence d’entreprises minières et d’institutions publiques.',
                'Aucune édition 2024, 2025 ou 2026 clairement documentée lors de la recherche.',
                'Espace d’exposition et de rencontres pour l’écosystème minier provincial.',
            ],
            'reference' => 'Actualite.cd',
            'reference_url' => 'https://actualite.cd/2023/10/08/salon-minier-lualaba-2023-le-stand-de-tfm-recoit-la-visite-de-sem-president-de-la',
            'recurrence_note' => 'La continuité annuelle du salon n’est pas encore démontrée par des éditions publiques successives.',
        ],
        'forum-cooperatives-minieres-2026' => [
            'id' => 'forum-cooperatives-minieres-2026',
            'title' => 'Forum national des coopératives minières',
            'date' => '2026-03-17',
            'date_label' => '17 au 19 mars 2026 · date rapportée à revérifier',
            'calendar_dates' => rarsm_activity_range('2026-03-17', '2026-03-19'),
            'time' => 'Forum national sur trois jours',
            'location' => 'CEPAS · Kinshasa',
            'category' => 'media',
            'verification_status' => 'watch',
            'status_label' => 'Première édition',
            'summary' => "Le forum rassemble les coopératives, les services publics et la société civile pour structurer, formaliser et professionnaliser l’exploitation minière artisanale.",
            'description' => "La première édition a débouché sur la création d’une confédération nationale et a abordé l’encadrement, l’accès au financement, la maîtrise du Code minier, la traçabilité et la lutte contre la fraude. Le forum sert également à faire remonter les difficultés opérationnelles des coopératives et à rapprocher leurs pratiques des exigences juridiques, sociales et commerciales du secteur formel.",
            'image' => 'images/activities/forum-cooperatives-minieres-2026.jpg',
            'image_alt' => 'Participants au premier Forum national des coopératives minières',
            'image_credit' => 'Photo : Surveillance.cd / Univers TV',
            'image_credit_url' => 'https://surveillance.cd/2026/03/20/rdc-les-cooperatives-minieres-se-dotent-dune-confederation-nationale-pour-structurer-le-secteur-artisanal/',
            'institution' => $institutions['ccps-rdc'],
            'institution_role' => "Le CCPS-RDC et DYCO-RDC ont réuni les coopératives et les pouvoirs publics afin de créer un cadre national de concertation.",
            'highlights' => [
                'Première édition nationale organisée à Kinshasa.',
                'Création d’une confédération nationale des coopératives minières.',
                'Les sources publiques présentent une divergence de mois qui doit être revérifiée.',
                'Dialogue sur la formalisation, le financement, la traçabilité et la conformité.',
            ],
            'reference' => 'Surveillance.cd',
            'reference_url' => 'https://surveillance.cd/2026/03/20/rdc-les-cooperatives-minieres-se-dotent-dune-confederation-nationale-pour-structurer-le-secteur-artisanal/',
            'recurrence_note' => 'Une source annonce une institutionnalisation annuelle, mais la prochaine édition et la date exacte doivent encore être confirmées.',
        ],
        'wim-alternatives-2024' => [
            'id' => 'wim-alternatives-2024',
            'title' => 'Conférence nationale WIM sur les alternatives à l’artisanat minier',
            'date' => '2024-04-03',
            'date_label' => '3 au 5 avril 2024 · dernière édition documentée',
            'calendar_dates' => rarsm_activity_range('2024-04-03', '2024-04-05'),
            'time' => 'Conférence nationale sur trois jours',
            'location' => 'Lubumbashi',
            'category' => 'media',
            'verification_status' => 'watch',
            'status_label' => 'Continuité à confirmer',
            'summary' => "Women in Mining DRC a consacré cette conférence aux alternatives socio-économiques à l’exploitation minière artisanale et à petite échelle.",
            'description' => "Les participantes, experts, autorités traditionnelles et partenaires ont travaillé sur la diversification des revenus, la sécurité, l’inclusion et les solutions permettant d’améliorer durablement les conditions de vie des communautés minières. L’approche met particulièrement l’accent sur l’autonomie économique, la résilience des ménages et la création d’activités capables de réduire la dépendance à l’artisanat minier.",
            'image' => 'images/activities/wim-alternatives-2024.jpg',
            'image_alt' => 'Participants à la conférence nationale Women in Mining DRC à Lubumbashi',
            'image_credit' => 'Photo : Actualite.cd',
            'image_credit_url' => 'https://actualite.cd/2024/04/03/rdc-les-alternatives-socio-economiques-lexploitation-miniere-artisanale-au-coeur-dune',
            'institution' => $institutions['wim-drc'],
            'institution_role' => "Women in Mining DRC a conçu cette conférence comme un espace national de réflexion sur les moyens de subsistance alternatifs et l’autonomisation.",
            'highlights' => [
                'Conférence nationale organisée à Lubumbashi en 2024.',
                'Réflexion sur les alternatives économiques à l’artisanat minier.',
                'Cadence annuelle non encore démontrée par des éditions successives.',
                'Accent sur la diversification des revenus et la résilience communautaire.',
            ],
            'reference' => 'Actualite.cd',
            'reference_url' => 'https://actualite.cd/2024/04/03/rdc-les-alternatives-socio-economiques-lexploitation-miniere-artisanale-au-coeur-dune',
            'recurrence_note' => 'Événement pertinent à surveiller ; sa récurrence annuelle reste à établir.',
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
