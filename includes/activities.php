<?php
declare(strict_types=1);

function rarsm_activity_categories(): array
{
    return [
        'launch' => ['label' => 'Forum', 'class' => 'event-launch'],
        'institution' => ['label' => 'Institution', 'class' => 'event-institution'],
        'signing' => ['label' => 'Terrain', 'class' => 'event-signing'],
        'media' => ['label' => 'Média', 'class' => 'event-media'],
    ];
}

function rarsm_activity_institutions(): array
{
    return [
        'ctcpm' => [
            'slug' => 'ctcpm',
            'name' => 'CTCPM',
            'full_name' => 'Cellule Technique de Coordination et de Planification Minière - CTCPM',
            'summary' => "Organe technique de conseil, d'études et de planification rattaché au ministère des Mines.",
            'details_href' => 'istitutions-details.php?institution=ctcpm',
            'directory_href' => 'institutions.php#institutions-mines',
            'website' => 'https://www.ctcpm.cd/',
            'socials' => [
                ['label' => 'LinkedIn', 'href' => 'https://www.linkedin.com/company/ctcpm-mines-rdc', 'icon' => 'fa-linkedin'],
                ['label' => 'Site officiel', 'href' => 'https://www.ctcpm.cd/', 'icon' => 'fa-globe'],
            ],
        ],
        'cami' => [
            'slug' => 'cami',
            'name' => 'CAMI',
            'full_name' => 'Cadastre Minier - CAMI',
            'summary' => "Établissement public chargé de la gestion du domaine minier et des titres miniers en RDC.",
            'details_href' => 'istitutions-details.php?institution=cami',
            'directory_href' => 'institutions.php#institutions-mines',
            'website' => 'https://cami.cd/',
            'socials' => [
                ['label' => 'LinkedIn', 'href' => 'https://www.linkedin.com/company/cadastre-minier-cami-rdc/?viewAsMember=true', 'icon' => 'fa-linkedin'],
                ['label' => 'Site officiel', 'href' => 'https://cami.cd/', 'icon' => 'fa-globe'],
            ],
        ],
        'ceec' => [
            'slug' => 'ceec',
            'name' => 'CEEC',
            'full_name' => "Centre d'Expertise, d'Évaluation et de Certification - CEEC",
            'summary' => "Autorité technique de certification, d'évaluation et de traçabilité des substances minérales concernées.",
            'details_href' => 'istitutions-details.php?institution=ceec',
            'directory_href' => 'institutions.php#institutions-mines',
            'website' => 'https://ceec.cd/',
            'socials' => [
                ['label' => 'LinkedIn', 'href' => 'https://www.linkedin.com/company/ceec-rdc', 'icon' => 'fa-linkedin'],
                ['label' => 'Site officiel', 'href' => 'https://ceec.cd/', 'icon' => 'fa-globe'],
            ],
        ],
        'saemape' => [
            'slug' => 'saemape',
            'name' => 'SAEMAPE',
            'full_name' => "Service d'Assistance et d'Encadrement de l'Exploitation Minière Artisanale et à Petite Échelle - SAEMAPE",
            'summary' => "Service public chargé de l'encadrement et de la professionnalisation de l'exploitation artisanale.",
            'details_href' => 'istitutions-details.php?institution=saemape',
            'directory_href' => 'institutions.php#institutions-mines',
            'website' => 'https://saemape.cd/',
            'socials' => [
                ['label' => 'LinkedIn', 'href' => 'https://cd.linkedin.com/company/saemape', 'icon' => 'fa-linkedin'],
                ['label' => 'Site officiel', 'href' => 'https://saemape.cd/', 'icon' => 'fa-globe'],
            ],
        ],
        'sgnc' => [
            'slug' => 'sgnc',
            'name' => 'SGNC',
            'full_name' => 'Service Géologique National du Congo - SGNC',
            'summary' => "Service spécialisé chargé de la connaissance géologique, scientifique et cartographique du sous-sol congolais.",
            'details_href' => 'istitutions-details.php?institution=sgnc',
            'directory_href' => 'institutions.php#institutions-mines',
            'website' => 'https://sgnc.cd/',
            'socials' => [
                ['label' => 'LinkedIn', 'href' => 'https://www.linkedin.com/company/sgncrdcongo', 'icon' => 'fa-linkedin'],
                ['label' => 'Site officiel', 'href' => 'https://sgnc.cd/', 'icon' => 'fa-globe'],
            ],
        ],
    ];
}

function rarsm_activity_records(): array
{
    $institutions = rarsm_activity_institutions();

    return [
        'forum-annuel-secteur-minier-2026-01-22' => [
            'id' => 'forum-annuel-secteur-minier-2026-01-22',
            'title' => 'Forum annuel de rentrée du secteur minier',
            'date' => '2026-01-22',
            'time' => '09:30',
            'location' => 'Kinshasa',
            'category' => 'launch',
            'summary' => "Ouverture de l'année avec une lecture partagée des priorités, réformes et grands rendez-vous du secteur minier.",
            'description' => "Ce forum réunit les acteurs institutionnels, techniques et économiques afin de poser les principaux axes de travail de l'année et de coordonner les temps forts à venir.",
            'image' => 'images/rarsm-generated/cadre-institutionnel.png',
            'image_alt' => 'Forum annuel du secteur minier',
            'institution' => $institutions['ctcpm'],
            'institution_role' => "La CTCPM accompagne cette activité par son rôle de coordination, d'analyse et de planification sectorielle.",
            'highlights' => [
                "Présentation des grands enjeux de l'année minière.",
                'Lecture des priorités institutionnelles et techniques.',
                'Coordination des prochains rendez-vous sectoriels.',
            ],
        ],
        'atelier-cadastre-titres-2026-02-12' => [
            'id' => 'atelier-cadastre-titres-2026-02-12',
            'title' => 'Atelier institutionnel sur le cadastre et les titres',
            'date' => '2026-02-12',
            'time' => '10:00',
            'location' => 'Kinshasa',
            'category' => 'institution',
            'summary' => "Séance de travail consacrée au suivi des titres, à la coordination administrative et aux obligations documentaires.",
            'description' => "L'atelier met l'accent sur la bonne circulation de l'information entre institutions concernées, sur la fiabilité des procédures et sur la lisibilité des dossiers pour les opérateurs.",
            'image' => 'images/rarsm-generated/cadre-institutionnel.png',
            'image_alt' => 'Atelier sur le cadastre minier',
            'institution' => $institutions['cami'],
            'institution_role' => "Le CAMI est directement concerné par la gestion des droits miniers, l'enregistrement des demandes et la prévention des chevauchements.",
            'highlights' => [
                'Clarification des circuits de traitement des dossiers.',
                'Rappel des exigences documentaires liées aux titres.',
                'Mise en cohérence entre données cadastrales et pratiques de terrain.',
            ],
        ],
        'mission-tracabilite-flux-2026-03-19' => [
            'id' => 'mission-tracabilite-flux-2026-03-19',
            'title' => 'Mission de terrain sur la traçabilité des flux miniers',
            'date' => '2026-03-19',
            'time' => '08:30',
            'location' => 'Kolwezi',
            'category' => 'signing',
            'summary' => "Déplacement opérationnel consacré au suivi des circuits, au contrôle des remontées d'information et à l'observation des pratiques de terrain.",
            'description' => "Cette mission vise à documenter les réalités locales, à renforcer la traçabilité des substances minérales et à alimenter les échanges entre les structures techniques et les décideurs.",
            'image' => 'images/rarsm-generated/tracabilite-certification.png',
            'image_alt' => 'Mission de terrain sur la traçabilité',
            'institution' => $institutions['ceec'],
            'institution_role' => "Le CEEC intervient ici par sa mission de certification, d'évaluation et de sécurisation des informations liées aux substances minérales.",
            'highlights' => [
                'Observation des circuits de collecte et de suivi.',
                'Analyse des besoins en fiabilité documentaire.',
                'Restitution des constats aux structures concernées.',
            ],
        ],
        'briefing-media-reglementation-2026-04-09' => [
            'id' => 'briefing-media-reglementation-2026-04-09',
            'title' => 'Briefing média sur la réglementation minière',
            'date' => '2026-04-09',
            'time' => '11:00',
            'location' => 'Studio média - Kinshasa',
            'category' => 'media',
            'summary' => "Point d'information destiné au grand public et aux professionnels sur les sujets réglementaires qui structurent l'actualité minière.",
            'description' => "Le briefing permet de restituer de manière claire les enjeux de gouvernance, les évolutions réglementaires et les questions qui appellent une meilleure pédagogie sectorielle.",
            'image' => 'images/rarsm-generated/about-rarsm.png',
            'image_alt' => 'Briefing média du secteur minier',
            'institution' => $institutions['sgnc'],
            'institution_role' => "Le SGNC contribue à la diffusion d'une information technique claire lorsque les sujets touchent à la connaissance géologique et à la lecture du sous-sol.",
            'highlights' => [
                'Explication pédagogique des sujets réglementaires.',
                'Mise en contexte des enjeux sectoriels pour le public.',
                'Valorisation des données et repères utiles à la compréhension.',
            ],
        ],
        'dialogue-operateurs-services-2026-05-21' => [
            'id' => 'dialogue-operateurs-services-2026-05-21',
            'title' => 'Dialogue entre opérateurs et services techniques',
            'date' => '2026-05-21',
            'time' => '14:00',
            'location' => 'Kinshasa',
            'category' => 'institution',
            'summary' => "Rencontre de coordination autour des procédures, de la conformité et des difficultés opérationnelles observées sur le terrain.",
            'description' => "Le dialogue vise à rapprocher les attentes des opérateurs et les exigences des structures techniques pour améliorer la fluidité des échanges et la compréhension des obligations.",
            'image' => 'images/rarsm-generated/suivi-droits-filieres.png',
            'image_alt' => 'Dialogue entre opérateurs et services techniques',
            'institution' => $institutions['ctcpm'],
            'institution_role' => "La CTCPM apporte sa capacité d'analyse transversale pour transformer les constats opérationnels en pistes d'amélioration sectorielle.",
            'highlights' => [
                'Remontée des difficultés rencontrées par les opérateurs.',
                'Clarification des attentes des services techniques.',
                'Identification de solutions pratiques à court terme.',
            ],
        ],
        'journee-technique-artisanale-2026-06-18' => [
            'id' => 'journee-technique-artisanale-2026-06-18',
            'title' => "Journée technique sur l'exploitation artisanale",
            'date' => '2026-06-18',
            'time' => '09:00',
            'location' => 'Lubumbashi',
            'category' => 'launch',
            'summary' => "Temps fort consacré à l'encadrement, aux bonnes pratiques et aux besoins d'accompagnement des acteurs de l'exploitation artisanale.",
            'description' => "La journée rassemble experts, encadreurs et représentants institutionnels afin de partager des repères concrets sur la sécurité, la conformité et l'organisation des filières artisanales.",
            'image' => 'images/rarsm-generated/section-book-introduction-contexte.png',
            'image_alt' => "Journée technique sur l'exploitation artisanale",
            'institution' => $institutions['saemape'],
            'institution_role' => "Le SAEMAPE est au cœur de cette activité grâce à son mandat d'encadrement et de professionnalisation de l'exploitation artisanale.",
            'highlights' => [
                'Sensibilisation aux bonnes pratiques et à la sécurité.',
                'Mise en avant des exigences de formalisation.',
                'Échanges avec les encadreurs et coopératives concernées.',
            ],
        ],
        'rencontre-conformite-provinciale-2026-07-24' => [
            'id' => 'rencontre-conformite-provinciale-2026-07-24',
            'title' => 'Rencontre provinciale sur la conformité minière',
            'date' => '2026-07-24',
            'time' => '10:30',
            'location' => 'Likasi',
            'category' => 'institution',
            'summary' => "Échanges ciblés sur le respect des obligations, la qualité des dossiers et la coordination entre acteurs locaux du secteur.",
            'description' => "Cette rencontre permet d'identifier les points de vigilance en province, de partager les attentes des institutions et d'orienter les opérateurs vers de meilleures pratiques de conformité.",
            'image' => 'images/rarsm-generated/tracabilite-certification.png',
            'image_alt' => 'Rencontre provinciale sur la conformité minière',
            'institution' => $institutions['ceec'],
            'institution_role' => "Le CEEC y apporte son regard sur la conformité documentaire, la certification et la fiabilité des chaînes de valeur minières.",
            'highlights' => [
                'Analyse des points de conformité les plus sensibles.',
                'Dialogue direct avec les acteurs provinciaux.',
                'Diffusion de repères pratiques pour améliorer les dossiers.',
            ],
        ],
        'mission-approvisionnement-responsable-2026-08-14' => [
            'id' => 'mission-approvisionnement-responsable-2026-08-14',
            'title' => "Mission sur l'approvisionnement responsable",
            'date' => '2026-08-14',
            'time' => '08:00',
            'location' => 'Goma',
            'category' => 'signing',
            'summary' => "Déploiement terrain autour de la chaîne d'approvisionnement, de la remontée d'informations et des mécanismes de suivi.",
            'description' => "La mission documente les exigences d'approvisionnement responsable, la circulation des données utiles et les enjeux de coordination entre les structures impliquées.",
            'image' => 'images/rarsm-generated/approvisionnement-responsable.png',
            'image_alt' => "Mission sur l'approvisionnement responsable",
            'institution' => $institutions['ceec'],
            'institution_role' => "Le CEEC est particulièrement concerné lorsqu'il s'agit de certification, de diligence documentaire et de suivi des flux.",
            'highlights' => [
                "Lecture des exigences d'approvisionnement responsable.",
                'Vérification de la qualité des informations remontées.',
                'Coordination entre terrain, contrôle et certification.',
            ],
        ],
        'forum-investisseurs-gouvernance-2026-09-11' => [
            'id' => 'forum-investisseurs-gouvernance-2026-09-11',
            'title' => 'Forum investisseurs et gouvernance minière',
            'date' => '2026-09-11',
            'time' => '09:30',
            'location' => 'Kinshasa',
            'category' => 'launch',
            'summary' => "Temps d'échange sur l'environnement réglementaire, la sécurité juridique et les attentes des partenaires publics et privés.",
            'description' => "Ce forum propose une lecture croisée des enjeux d'investissement, de gouvernance et de conformité afin de favoriser des décisions mieux informées dans le secteur minier.",
            'image' => 'images/rarsm-generated/cadre-institutionnel.png',
            'image_alt' => 'Forum investisseurs et gouvernance minière',
            'institution' => $institutions['ctcpm'],
            'institution_role' => "La CTCPM joue ici un rôle clé d'information stratégique et de mise en perspective des orientations sectorielles.",
            'highlights' => [
                'Lecture stratégique du cadre réglementaire.',
                'Dialogue entre acteurs publics et investisseurs.',
                'Focus sur la sécurité juridique et la gouvernance.',
            ],
        ],
        'point-presse-certification-exportation-2026-10-16' => [
            'id' => 'point-presse-certification-exportation-2026-10-16',
            'title' => "Point presse sur la certification et l'exportation",
            'date' => '2026-10-16',
            'time' => '11:30',
            'location' => 'Kinshasa',
            'category' => 'media',
            'summary' => "Prise de parole publique sur les mécanismes de certification, de traçabilité et de contrôle des flux à l'export.",
            'description' => "Le point presse éclaire les professionnels et le public sur les exigences de certification, les enjeux de transparence et les bonnes pratiques attendues à l'exportation.",
            'image' => 'images/rarsm-generated/exportation-fraude.png',
            'image_alt' => "Point presse sur la certification et l'exportation",
            'institution' => $institutions['ceec'],
            'institution_role' => "Le CEEC intervient directement sur les problématiques de certification, d'origine et de conformité liées aux exportations.",
            'highlights' => [
                'Décryptage des mécanismes de certification.',
                'Rappel des exigences de traçabilité à l’export.',
                'Information publique sur les attentes de conformité.',
            ],
        ],
        'atelier-controle-fiscalite-2026-11-20' => [
            'id' => 'atelier-controle-fiscalite-2026-11-20',
            'title' => 'Atelier interinstitutionnel sur le contrôle et la fiscalité',
            'date' => '2026-11-20',
            'time' => '09:45',
            'location' => 'Kinshasa',
            'category' => 'institution',
            'summary' => "Session de travail sur le suivi des obligations, les mécanismes de contrôle et la lisibilité des procédures fiscales.",
            'description' => "L'atelier réunit plusieurs structures concernées pour renforcer la coordination, réduire les zones d'interprétation et améliorer la qualité des échanges avec les opérateurs.",
            'image' => 'images/rarsm-generated/suivi-droits-filieres.png',
            'image_alt' => 'Atelier sur le contrôle et la fiscalité',
            'institution' => $institutions['ctcpm'],
            'institution_role' => "La CTCPM intervient ici comme structure d'appui analytique et de coordination entre plusieurs administrations concernées.",
            'highlights' => [
                'Lecture commune des obligations sectorielles.',
                'Réduction des divergences d’interprétation.',
                'Renforcement de la coordination entre services concernés.',
            ],
        ],
        'bilan-annuel-secteur-minier-2026-12-10' => [
            'id' => 'bilan-annuel-secteur-minier-2026-12-10',
            'title' => 'Bilan annuel et perspectives du secteur minier',
            'date' => '2026-12-10',
            'time' => '10:00',
            'location' => 'Kinshasa',
            'category' => 'launch',
            'summary' => "Clôture de l'année avec un retour sur les activités réalisées, les enjeux persistants et les priorités à venir.",
            'description' => "Ce rendez-vous de fin d'année permet de faire la synthèse des temps forts du calendrier, de partager les enseignements utiles et de préparer les orientations de l'année suivante.",
            'image' => 'images/rarsm-generated/hero-presentation-rarsm.png',
            'image_alt' => 'Bilan annuel du secteur minier',
            'institution' => $institutions['ctcpm'],
            'institution_role' => "La CTCPM est naturellement associée à ce bilan par sa capacité à consolider les tendances, constats et perspectives sectorielles.",
            'highlights' => [
                "Synthèse des grandes activités de l'année.",
                'Retour sur les leçons utiles pour les acteurs du secteur.',
                'Préparation des priorités pour le prochain calendrier annuel.',
            ],
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
    $leftKey = $left['date'] . ' ' . $left['time'];
    $rightKey = $right['date'] . ' ' . $right['time'];

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

    return $initials !== '' ? $initials : strtoupper(substr(str_replace(' ', '', $clean), 0, 3));
}
