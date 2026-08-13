<?php
if (isset($_GET['debug']) && $_GET['debug'] === '1') {
	ini_set('display_errors', '1');
	ini_set('display_startup_errors', '1');
	error_reporting(E_ALL);
}

function rarsm_e($value)
{
	return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}

function rarsm_initials($label)
{
	$clean = preg_replace('/[^A-Za-z0-9 ]+/', ' ', (string) $label);
	$parts = preg_split('/\s+/', trim((string) $clean)) ?: [];
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

function rarsm_public_file_exists($relativePath)
{
	return $relativePath !== '' && is_file(__DIR__ . '/' . ltrim((string) $relativePath, '/'));
}

function rarsm_string_length($value)
{
	return function_exists('mb_strlen') ? mb_strlen((string) $value) : strlen((string) $value);
}

function rarsm_render_paragraphs($text, $targetLength = 420)
{
	$normalized = trim((string) $text);

	if ($normalized === '') {
		return '';
	}

	$normalized = preg_replace('/\R{3,}/u', "\n\n", $normalized);
	$blocks = preg_split('/\n\s*\n/u', (string) $normalized) ?: [];
	$paragraphs = [];

	foreach ($blocks as $block) {
		$block = trim(preg_replace('/\s+/u', ' ', (string) $block));

		if ($block === '') {
			continue;
		}

		if (rarsm_string_length($block) <= $targetLength + 80) {
			$paragraphs[] = $block;
			continue;
		}

		$sentences = preg_split('/(?<=[\.\!\?])\s+(?=[A-ZÀ-ÖØ-Ý])/u', $block) ?: [$block];
		$currentParagraph = '';

		foreach ($sentences as $sentence) {
			$sentence = trim((string) $sentence);

			if ($sentence === '') {
				continue;
			}

			$nextParagraph = $currentParagraph === '' ? $sentence : $currentParagraph . ' ' . $sentence;

			if ($currentParagraph !== '' && rarsm_string_length($nextParagraph) > $targetLength) {
				$paragraphs[] = $currentParagraph;
				$currentParagraph = $sentence;
				continue;
			}

			$currentParagraph = $nextParagraph;
		}

		if ($currentParagraph !== '') {
			$paragraphs[] = $currentParagraph;
		}
	}

	if (empty($paragraphs)) {
		return '<p>' . rarsm_e($normalized) . '</p>';
	}

	$html = '';

	foreach ($paragraphs as $paragraph) {
		$html .= '<p>' . rarsm_e($paragraph) . '</p>';
	}

	return $html;
}

function rarsm_quote_source_url($slug, array $institution)
{
	if (!empty($institution['quote_source_url'])) {
		return (string) $institution['quote_source_url'];
	}

	$articleUrls = [
		'igm' => 'https://depeche.cd/2025/04/04/rdc-linspecteur-general-des-mines-et-sa-delegation-en-formation-a-fribourg-une-immersion-enrichissante/',
		'ctcpm' => 'https://acp.cd/business/mines-la-nouvelle-equipe-dirigeante-dun-service-technique-determinee-a-relever-les-defis/',
		'fomin' => 'https://www.forumdesas.cd/fomin-le-duo-motemona-kalaa-mpinga-promet-une-gestion-orientee-vers-linteret-des-generations',
		'ceec' => 'https://acp.cd/economie/lualaba-inauguration-du-laboratoire-chimique-du-centre-dexpertise-des-substances-minerales/',
		'sgnc' => 'https://acp.cd/economie/service-geologique-national-en-rdc-lapprobation-dun-financement-pour-intensifier-la-recherche-saluee/',
		'cami' => 'https://acp.cd/economie/cadastre-minier-la-conference-budgetaire-un-indicateur-pour-la-gestion-miniere-en-rdc-directeur-general/',
		'cnlfm' => 'https://zoom-eco.net/autres-actualites/rdc-le-ministre-des-mines-installe-le-nouveau-comite-anti-fraude-miniere/',
		'arecoms' => 'https://acp.cd/economie/mines-lexportation-du-cobalt-suspendue-temporairement-durant-quatre-mois-en-rdc/',
		'ministere-finances' => 'https://actualite.cd/2026/07/22/senat-le-ministre-des-finances-doudou-fwamba-defend-une-reforme-fiscale-pour-renforcer',
		'dgi' => 'https://acp.cd/economie/direction-generale-des-impots-la-formation-parmi-les-secrets-de-laccroissement-des-recettes-2/',
		'dgda' => 'https://acp.cd/economie/la-mise-en-oeuvre-du-statut-doperateur-economique-au-centre-dune-matinee-dinformation/',
		'dgrad' => 'https://acp.cd/economie/direction-generale-des-recettes-administratives-les-agents-encourages-a-accroitre-les-recettes-financieres/',
		'ministere-transports' => 'https://acp.cd/economie/aerogare-moderne-de-boende-le-vice-premier-ministre-bemba-evalue-lavancement-des-travaux-photo-darchive/',
		'ogefrem' => 'https://hautespersonnalites.cd/ucca-logefrem-sollicite-le-report-du-forum-international-de-kinshasa/',
		'lmc' => 'https://hautespersonnalites.cd/',
		'ministere-recherche' => 'https://acp.cd/nation/la-rdc-a-lere-de-la-professionnalisation-de-la-valorisation-scientifique-ministre-de-lenseignement-superieur/',
		'ministere-commerce-exterieur' => 'https://acp.cd/nation/commerce-exterieur-en-rdc-les-services-sous-tutelle-appeles-a-innover-davantage-julien-paluku/',
		'ministere-environnement' => 'https://acp.cd/',
		'egc' => 'https://www.business-humanrights.org/en/latest-news/rdc-pour-la-premi%C3%A8re-fois-le-pays-produit-du-cobalt-artisanal-%C3%A9quitable/',
		'itie-rdc' => 'https://mines.cd/lubumbashi-la-gouvernance-miniere-au-coeur-dun-atelier-de-haut-niveau-de-la-tripartite-itie-rdc-idak-et-chambre-des-mines-fec/',
	];

	if (isset($articleUrls[$slug])) {
		return $articleUrls[$slug];
	}

	$sourceHomepages = [
		'ACP' => 'https://acp.cd/',
		'Actualite.cd' => 'https://actualite.cd/',
		'Business & Human Rights Resource Centre' => 'https://www.business-humanrights.org/',
		'Dépêche.cd' => 'https://depeche.cd/',
		'Forum des As' => 'https://www.forumdesas.cd/',
		'Hautes Personnalités' => 'https://hautespersonnalites.cd/',
		'Mines.cd' => 'https://mines.cd/',
		'Zoom Eco' => 'https://zoom-eco.net/',
	];

	$source = isset($institution['quote_source']) ? trim((string) $institution['quote_source']) : '';

	return isset($sourceHomepages[$source]) ? $sourceHomepages[$source] : '';
}

$institutions = [
	'igm' => [
		'name' => "Inspection Générale des Mines - IGM",
		'sector' => "Mines",
		'summary' => "Service public d'inspection, de contrôle et d'audit des activités minières et des carrières en RDC.",
		'details' => "L'Inspection Générale des Mines est un service public place sous l'autorité du ministère des Mines. Elle assure l'inspection, le contrôle et l'audit des activités minières et des carrières sur l'ensemble du territoire national. Elle veille au respect du Code minier, du Règlement minier et des autres textes applicables, tout en renforçant la traçabilité des minerais et la lutte contre la fraude, la contrebande et le commerce illicite des produits miniers.",
		'website' => '',
		'website_label' => '',
	],
	'ctcpm' => [
		'name' => "Cellule Technique de Coordination et de Planification Miniere - CTCPM",
		'sector' => "Mines",
		'summary' => "Organe technique de conseil, d'études, de coordination et de planification rattaché au ministère des Mines.",
		'details' => "La CTCPM assiste le ministre dans la conception et la mise en œuvre de la politique minière nationale. Elle analyse les données économiques, techniques, juridiques et statistiques relatives au secteur minier, centralise certaines informations sur la production et les exportations, et formule des avis sur les projets miniers, les stratégies sectorielles et les réformes réglementaires.",
		'website' => 'https://www.ctcpm.cd/',
		'website_label' => 'ctcpm.cd',
	],
	'fomin' => [
		'name' => "Fonds Minier pour les Generations Futures - FOMIN",
		'sector' => "Mines",
		'summary' => "Etablissement public chargé de constituer des richesses financières et materielles au profit de l'après-mine.",
		'details' => "Le FOMIN a pour mission de preserver une partie des revenus tires des ressources minière non renouvelables au profit des generations futures. Il gere notamment une quotite de la redevance minière selon des principes de sécurité, de rentabilite, de transparence et de responsabilite, et participe a la perennisation de la recherche geologique et minière.",
		'website' => 'https://www.fomin.cd/',
		'website_label' => 'fomin.cd',
	],
	'ceec' => [
		'name' => "Centre d'Expertise, d'Evaluation et de Certification - CEEC",
		'sector' => "Mines",
		'summary' => "Autorite chargée de l'expertise, de l'évaluation et de la certification de certaines substances minérales.",
		'details' => "Le CEEC intervient principalement dans les filières des substances minérales precieuses, semi-precieuses et stratégiques. Il analyse la qualité, la teneur, la quantité et la valeur marchande des produits miniers, delivre des certificats de conformité, d'origine et de traçabilité, et contribue a la lutte contre la sous-évaluation, la fraude et l'exportation illégale des minerais.",
		'website' => 'https://ceec.cd/',
		'website_label' => 'ceec.cd',
	],
	'sgnc' => [
		'name' => "Service Geologique National du Congo - SGNC",
		'sector' => "Mines",
		'summary' => "Etablissement public de référence chargé de la connaissance scientifique du sol et du sous-sol congolais.",
		'details' => "Le SGNC acquiert, centralise, traite, conserve et diffusé les données géologiques et minières nationales. Il réalise des travaux de cartographie geologique, geophysique, geochimique et hydrogeologique, identifie les formations géologiques, évalue le potentiel mineral du pays et etudie aussi les risques naturels liés a la géologie.",
		'website' => 'https://sgnc.cd/',
		'website_label' => 'sgnc.cd',
	],
	'cami' => [
		'name' => "Cadastre Minier - CAMI",
		'sector' => "Mines",
		'summary' => "Etablissement public chargé de la gestion du domaine minier ainsi que des droits miniers et de carrières.",
		'details' => "Le CAMI reçoit, enregistre et traite les demandes d'octroi, de renouvellement, de transformation, d'extension ou de transfert des droits miniers. Il vérifie la disponibilité des périmètres, tient les registres officiels des permis et etablit les cartes cadastrales qui permettent de prévenir les chevauchements et les conflits entre titres.",
		'website' => 'https://cami.cd/',
		'website_label' => 'cami.cd',
	],
	'saemape' => [
		'name' => "Service d'Assistance et d'Encadrement de l'Exploitation minière artisanale et à petite échelle - SAEMAPE",
		'sector' => "Mines",
		'summary' => "Service public chargé de l'assistance et de l'encadrement des exploitants miniers artisanaux et des cooperatives.",
		'details' => "Le SAEMAPE favorise la formalisation et la professionnalisation de l'exploitation minière artisanale. Il apporte une assistance technique, administrative et manageriale, sensibilise aux règles de sécurité, de protection de l'environnement et de traçabilité, et contribue a la lutte contre le travail des enfants, les accidents et les pratiques minières dangereuses.",
		'website' => 'https://saemape.cd/',
		'website_label' => 'saemape.cd',
	],
	'arecoms' => [
		'name' => "Autorite de Regulation et de Contrôle des Marches des Substances Minerales Strategiques - ARECOMS",
		'sector' => "Mines",
		'summary' => "Autorite publique chargée de la régulation et du contrôle des marches des substances minérales stratégiques.",
		'details' => "L'ARECOMS veille a l'organisation, a l'assainissement et a la stabilité des marches des substances minérales declarees stratégiques en RDC, notamment le cobalt, le germanium et la colombo-tantalite. Elle contrôle les activités de production, d'achat, de traitement, de commercialisation et d'exportation relevant de son mandat, et propose au Gouvernement des mesures de sauvegarde et des réformes sectorielles.",
		'website' => '',
		'website_label' => '',
	],
	'cnlfm' => [
		'name' => "Commission Nationale de Lutte contre la Fraude Miniere - CNLFM",
		'sector' => "Mines",
		'summary' => "Mécanisme de coordination entre plusieurs services publics impliques dans la lutte contre la fraude et la contrebande minières.",
		'details' => "La CNLFM favorise la collaboration entre les Mines, l'Intérieur, la Justice, la Défense, les régies financières et les organismes techniques. Elle organise l'échange d'informations, les missions conjointes de contrôle et les actions de terrain contre l'exploitation clandestine, le transport irrégulier, la dissimulation et l'exportation frauduleuse des produits miniers.",
		'website' => 'https://ceec.cd/fraude-miniere',
		'website_label' => 'Plus d\'informations',
	],
	'ministere-finances' => [
		'name' => "Ministère des Finances",
		'sector' => "Finances",
		'summary' => "Organe chargé de la gestion generale des finances publiques et de la mobilisation des recettes du pouvoir central.",
		'details' => "Le ministère des Finances conçoit et met en oeuvre la politique fiscale, financière, comptable et budgétaire de l'Etat. Dans le secteur minier, il veille a la perception des impôts, droits de douane, taxes, redevances et autres recettes revenant au pouvoir central, tout en suivant la trésorerie, la dette publique et les réformes de transparence et de digitalisation.",
		'website' => 'https://finances.gouv.cd/',
		'website_label' => 'finances.gouv.cd',
	],
	'dgi' => [
		'name' => "Direction Générale des Impots - DGI",
		'sector' => "Finances",
		'summary' => "Regie financière chargée de l'assiette, du contrôle, du recouvrement et du contentieux des impôts du pouvoir central.",
		'details' => "La DGI identifie les contribuables, reçoit les declarations fiscales et contrôle leur exactitude. Elle recouvre notamment les impôts sur les bénéfices, les revenus, les remunerations et la TVA selon la législation applicable, et intervient dans le secteur minier pour suivre les obligations fiscales des sociétés, sous-traitants, fournisseurs et travailleurs concernes.",
		'website' => 'https://dgi.gouv.cd/',
		'website_label' => 'dgi.gouv.cd',
	],
	'dgda' => [
		'name' => "Direction Générale des Douanes et Accises - DGDA",
		'sector' => "Finances",
		'summary' => "Regie financière chargée de l'application de la législation douaniere et accisienne sur toute l'etendue du territoire national.",
		'details' => "La DGDA percoit les droits, taxes et redevances dus a l'importation, a l'exportation, au transit et a l'entreposage des marchandises. Dans le secteur minier, elle vérifie les formalites douanieres relatives aux équipements importes et aux produits miniers exportes, tout en participant a la lutte contre la fraude, la contrebande et la fausse declaration.",
		'website' => 'https://douane.gouv.cd/',
		'website_label' => 'douane.gouv.cd',
	],
	'dgrad' => [
		'name' => "Direction Générale des Recettes Administratives, Judiciaires, Domaniales et de Participations - DGRAD",
		'sector' => "Finances",
		'summary' => "Service public chargé de l'ordonnancement et du recouvrement des recettes non fiscales du pouvoir central.",
		'details' => "La DGRAD vérifie les operations des services d'assiette, etablit les titres de perception et assure le recouvrement des recettes non fiscales provenant notamment des mines, de l'environnement, du commerce, des transports et des affaires foncieres. Sa digitalisation vise a renforcer la traçabilité et a limiter les paiements irréguliers.",
		'website' => 'https://dgrad.gouv.cd/',
		'website_label' => 'dgrad.gouv.cd',
	],
	'recettes-provinciales' => [
		'name' => "Directions provinciales des recettes",
		'sector' => "Finances",
		'summary' => "Regies financières instituees par les provinces pour mobiliser les revenus relevant de leurs competences.",
		'details' => "Leur appellation et leur organisation varient selon les provinces, mais leur mission reste d'identifier les contribuables, d'ordonner, de recouvrer et de contrôler les impôts, taxes, droits et redevances provinciaux. Dans les provinces minières, elles jouent un rôle important dans le suivi des opérateurs et des activités économiques liees aux mines.",
		'website' => '',
		'website_label' => '',
	],
	'ministere-transports' => [
		'name' => "Ministère des Transports, Voies de Communication et Desenclavement",
		'sector' => "Transports",
		'summary' => "Ministère chargé de la politique nationale des transports et des corridors logistiques en RDC.",
		'details' => "Ce ministère prépare les normes, stratégies et programmes relatifs aux transports routier, ferroviaire, fluvial, lacustre, maritime et aérien. Dans le secteur minier, il intervient dans l'organisation des corridors servant a acheminer les minerais et les équipements, et son action influence directement les coûts et la compétitivité des exportations minière congolaises.",
		'website' => 'https://transports.gouv.cd/',
		'website_label' => 'transports.gouv.cd',
	],
	'ogefrem' => [
		'name' => "Office de Gestion du Fret Multimodal - OGEFREM",
		'sector' => "Transports",
		'summary' => "Etablissement public chargé de l'encadrement et de la régulation du fret congolais.",
		'details' => "L'OGEFREM defend les interets des chargeurs congolais, suit les cargaisons importees ou exportees par les differents corridors desservant la RDC et gere des documents de suivi du fret. Il collecte et analyse des données sur les volumes, les itineraires et les coûts logistiques, ce qui en fait un acteur important pour le suivi des minerais exportes et des équipements importes.",
		'website' => 'https://ogefrem.cd/',
		'website_label' => 'ogefrem.cd',
	],
	'lmc' => [
		'name' => "Lignes Maritimes Congolaises - LMC",
		'sector' => "Transports",
		'summary' => "Armement maritime national de la RDC, intervenant dans l'organisation du transport maritime et multimodal.",
		'details' => "La LMC assure a la RDC l'exercice et la jouissance de ses droits de trafic maritime. Elle peut transporter des marchandises par ses propres navires, des navires affrétés ou des accords avec d'autres transporteurs. Son rôle est stratégique pour un pays dépendant fortement des ports et corridors régionaux pour son commerce extérieur, y compris les produits miniers.",
		'website' => 'https://lmc.cd/',
		'website_label' => 'lmc.cd',
	],
	'ministere-recherche' => [
		'name' => "Ministère chargé de la Recherche scientifique et de l'Innovation technologique",
		'sector' => "Recherche",
		'summary' => "Ministère chargé de la politique nationale de recherche, d'innovation et de développement technologique.",
		'details' => "Ce ministère oriente les programmes scientifiques vers les besoins économiques, industriels, environnementaux et sociaux du pays. Il supervise les centres, instituts et organismes publics de recherche, encourage la formation des chercheurs et accompagne les institutions travaillant sur la géologie, la métallurgie, l'environnement et l'énergie dans le champ minier.",
		'website' => 'https://minesursi.gouv.cd/',
		'website_label' => 'minesursi.gouv.cd',
	],
	'cnpri' => [
		'name' => "Comite National de Protection contre les Rayonnements Ionisants - CNPRI",
		'sector' => "Recherche",
		'summary' => "Autorite réglementaire nationale chargée de la surete nucleaire, de la sécurité radiologique et de la radioprotection.",
		'details' => "Le CNPRI veille a la protection des travailleurs, des patients, du public, des biens et de l'environnement. Il contrôle les activités qui utilisent, detiennent, transportent, importent ou exportent des sources radioactives. Dans le secteur minier, son intervention est importante lorsque des minerais contiennent des substances radioactives ou lorsque des appareils utilisant des rayonnements sont employes.",
		'website' => 'https://cnpri.cd/',
		'website_label' => 'cnpri.cd',
	],
	'cgea' => [
		'name' => "Commissariat General a l'Energie Atomique - CGEA",
		'sector' => "Recherche",
		'summary' => "Institution congolaise chargée de promouvoir et coordonnér les recherches liees a l'utilisation pacifique de l'énergie atomique.",
		'details' => "Le CGEA developpe des programmes scientifiques et technologiques dans plusieurs domaines, notamment la physique, la chimie, l'agronomie, les sciences de la vie et les techniques appliquees. Dans le secteur minier, ses techniques peuvent être utilisees pour caracteriser des minerais, etudier des matériaux et detecter certains elements.",
		'website' => 'https://cgea-rdc.org/',
		'website_label' => 'cgea-rdc.org',
	],
	'crgm' => [
		'name' => "Centre de Recherches Geologiques et Minieres - CRGM",
		'sector' => "Recherche",
		'summary' => "Etablissement public a caractere scientifique et technique specialise dans les sciences de la Terre.",
		'details' => "Le CRGM conçoit et execute des projets destines a améliorer la connaissance du sol et du sous-sol de la RDC. Il réalise des travaux de prospection geologique et minière, produit des cartes géologiques, gitologiques, hydrogéologiques et geotechniques, et etudie aussi les risques géologiques ainsi que les effets environnementaux des activités minières.",
		'website' => '',
		'website_label' => '',
	],
	'ministere-commerce-exterieur' => [
		'name' => "Ministère du Commerce extérieur",
		'sector' => "Commerce",
		'summary' => "Ministère chargé de la politique nationale relative aux importations, exportations, transit et relations commerciales internationales.",
		'details' => "Le ministère du Commerce extérieur élabore les orientations destinees a promouvoir les produits congolais sur les marches étrangers, négocie et suit les accords commerciaux et supervise ou collabore avec des organismes comme l'OCC. Dans le secteur minier, il encadre les aspects commerciaux des exportations de minerais et de produits transformés.",
		'website' => '',
		'website_label' => '',
	],
	'occ' => [
		'name' => "Office Congolais de Contrôle - OCC",
		'sector' => "Commerce",
		'summary' => "Etablissement public chargé du contrôle de la qualité, de la conformité et de certaines analyses techniques.",
		'details' => "L'OCC contrôle les marchandises et produits importes, exportes ou fabriques localement. Ses missions portent sur la qualité, la quantité, la conformité, le prix et le respect des normes applicables. Dans le domaine minier, il peut intervenir dans le contrôle de certains produits, équipements et operations liés au commerce extérieur.",
		'website' => 'https://www.occ.cd/',
		'website_label' => 'occ.cd',
	],
	'ministere-environnement' => [
		'name' => "Ministère de l'Environnement, Développement durable et Nouvelle Économie du climat",
		'sector' => "Environnement",
		'summary' => "Organe gouvernemental chargé de definir et mettre en oeuvre la politique nationale en matière d'environnement.",
		'details' => "Ce ministère veille a la protection des écosystèmes, de la biodiversité, des forêts, des ressources naturelles et du cadre de vie des populations. Dans le domaine minier, il intervient dans la prévention et la gestion des pollutions de l'air, de l'eau et des sols, et suit l'application des conventions environnementales ratifiées par la RDC.",
		'website' => 'https://medd.gouv.cd/',
		'website_label' => 'medd.gouv.cd',
	],
	'ace' => [
		'name' => "Agence Congolaise de l'Environnement - ACE",
		'sector' => "Environnement",
		'summary' => "Etablissement public chargé de l'évaluation environnementale et sociale des projets de développement en RDC.",
		'details' => "L'ACE examine les etudes d'impact environnemental et social, vérifie les rapports des promoteurs et bureaux d'études agrees, formule des avis de conformité et peut demander des corrections ou des mesures d'atténuation. Dans le secteur minier, elle évalue notamment les risques liés aux rejets, aux dechets, a la consommation d'eau, aux deplacements de populations et a la fermeture des sites.",
		'website' => 'https://medd.gouv.cd/ace/',
		'website_label' => 'medd.gouv.cd/ace',
	],
	'egc' => [
		'name' => "Entreprise Générale du Cobalt - EGC",
		'sector' => "Mines",
		'summary' => "Filiale de la Gecamines chargée d'encadrer l'achat, le traitement et la commercialisation du cobalt artisanal.",
		'details' => "L'EGC a été crééee pour intègrer la production artisanale de cobalt dans une chaîne d'approvisionnement officielle, contrôlee et transparente. Elle collabore avec les cooperatives, les services publics, les acheteurs et les partenaires techniques, et vise a réduire le travail des enfants, les violations des droits humains, les circuits clandestins et les pertes de recettes publiques.",
		'website' => 'https://egcobalt.cd/',
		'website_label' => 'egcobalt.cd',
	],
	'itie-rdc' => [
		'name' => "Initiative pour la Transparence dans les Industries Extractives - ITIE-RDC",
		'sector' => "Mines",
		'summary' => "Mécanisme national de mise en œuvre de l'Initiative pour la Transparence dans les Industries Extractives.",
		'details' => "L'ITIE-RDC réunit des représentants de l'Etat, des entreprises extractives et de la société civile pour promouvoir la transparence et la bonne gouvernance des revenus des secteurs minier, pétrolier et gazier. Elle collecte, analyse et publie des données sur la production, les exportations, les paiements des entreprises et les recettes perçues par l'Etat, ce qui nourrit le débat public et oriente les réformes.",
		'website' => 'https://www.itierdc.net/',
		'website_label' => 'itierdc.net',
	],
];

$institutionContent = require __DIR__ . '/includes/institutions-content.php';

foreach ($institutionContent['profiles'] as $slug => $profile) {
	if (!isset($institutions[$slug])) {
		continue;
	}

	$institutions[$slug] = array_merge($institutions[$slug], $profile);
}

foreach ($institutionContent['updates'] as $slug => $update) {
	if (!isset($institutions[$slug])) {
		continue;
	}

	$institutions[$slug] = array_merge($institutions[$slug], $update);
}

$sectorNotes = [
	'Mines' => "Cette famille d’institutions couvre l’accès aux titres, le contrôle, la certification, la traçabilité et l’encadrement du secteur. Leur lecture dans le RARSM aide à comprendre toute la chaîne administrative et technique de l’activité minière.",
	'Finances' => "Ces institutions structurent la collecte des recettes publiques, la fiscalité et le contrôle des flux financiers liés aux activités minières. Elles sont essentielles pour comprendre comment les revenus du secteur sont transformés en ressources publiques.",
	'Transports' => "Le secteur des transports et de la logistique est déterminant pour les coûts, les délais et la compétitivité des exportations minières. Il relie les sites de production aux corridors, ports et points de sortie du pays.",
	'Recherche' => "Les institutions de recherche et d’innovation fournissent l’appui scientifique, technique et analytique utile à la géologie, aux matériaux, à la radioprotection et à la connaissance du sous-sol.",
	'Commerce' => "Ce bloc relie le secteur minier aux flux d’import-export, aux contrôles de qualité et à la compétitivité des produits sur les marchés nationaux et internationaux.",
	'Environnement' => "Ces institutions interviennent dans l’évaluation des impacts, la prévention des pollutions et la protection des écosystèmes et des communautés autour des projets extractifs.",
	'Autres' => "Ces organismes jouent un rôle transversal dans la formalisation, la transparence et la gouvernance des chaînes de valeur extractives, en particulier pour le cobalt artisanal et la publication des données sectorielles.",
];

$requestedSlug = isset($_GET['institution']) ? strtolower(preg_replace('/[^a-z0-9\-]/', '', (string) $_GET['institution'])) : 'igm';
$notFound = !isset($institutions[$requestedSlug]);
$selectedSlug = $notFound ? 'igm' : $requestedSlug;
$selected = $institutions[$selectedSlug];
$selectedSectorNote = isset($sectorNotes[$selected['sector']]) ? $sectorNotes[$selected['sector']] : "Cette institution participe à la lecture pratique de l’écosystème minier, administratif et réglementaire couvert par le recueil.";
$selectedLogoPath = isset($selected['logo_path']) && rarsm_public_file_exists($selected['logo_path']) ? $selected['logo_path'] : '';
$selectedLogoInitials = !empty($selected['logo_initials'])
	? (string) $selected['logo_initials']
	: rarsm_initials($selected['name']);
$selectedLeaderPhoto = isset($selected['leader_photo']) && rarsm_public_file_exists($selected['leader_photo']) ? $selected['leader_photo'] : '';
$selectedLeaderPhotoPosition = isset($selected['leader_photo_position']) && preg_match('/^\d{1,3}%\s+\d{1,3}%$/', (string) $selected['leader_photo_position'])
	? (string) $selected['leader_photo_position']
	: '50% 25%';
$selectedLeaderPhotoScale = isset($selected['leader_photo_scale']) ? (float) $selected['leader_photo_scale'] : 1.0;
$selectedLeaderPhotoScale = max(1.0, min(1.75, $selectedLeaderPhotoScale));
$selectedQuoteSourceUrl = rarsm_quote_source_url($selectedSlug, $selected);
$pageTitle = 'RARSM | ' . $selected['name'];

$suggestions = [];

foreach ($institutions as $slug => $institution) {
	if ($slug === $selectedSlug) {
		continue;
	}

	if ($institution['sector'] === $selected['sector']) {
		$suggestions[$slug] = $institution;
	}
}

foreach ($institutions as $slug => $institution) {
	if (count($suggestions) >= 5) {
		break;
	}

	if ($slug === $selectedSlug || isset($suggestions[$slug])) {
		continue;
	}

	$suggestions[$slug] = $institution;
}
?>
<!DOCTYPE html>
<html class="no-js" lang="fr">
<head>
	<title><?php echo rarsm_e($pageTitle); ?></title>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="description" content="<?php echo rarsm_e($selected['summary']); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<meta name="format-detection" content="telephone=no">
	<link rel="icon" href="favicon.png?v=20260702-favicon" type="image/png">
	<link rel="shortcut icon" href="favicon.png?v=20260702-favicon" type="image/png">
	<link rel="apple-touch-icon" href="favicon.png?v=20260702-favicon">
	<link rel="stylesheet" href="css/site.css?v=20260813-rarsm-v21">
	<script src="js/vendor/modernizr-2.6.2.min.js"></script>
</head>
<body>
	<div class="preloader">
		<div class="preloader_image pulse"></div>
	</div>

	<div class="modal fade popupLogin" id="popupLogin" tabindex="-1" role="dialog" aria-hidden="true">
		<div class="modal-dialog modal-dialog-centered" role="document">
			<div class="modal-content ls border-r-def overflow-visible s-overlay s-mobile-overlay">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
				<div class="modal-body">
					<div class="container">
						<div class="row">
							<div class="col-12">
								<h4 class="mb-4">Se connecter</h4>
								<form class="form-registration c-mb-40 c-gutter-20" method="post" action="actions/login.php">
									<input type="hidden" name="redirect" value="shop-cart.php">
									<div class="row mb-4">
										<div class="col-sm-12">
											<div class="form-group">
												<input type="text" name="name" class="form-control" required placeholder="Identifiant" aria-required="true">
											</div>
										</div>
										<div class="col-sm-12">
											<div class="form-group">
												<input type="password" name="password" class="form-control" placeholder="Mot de passe" aria-required="true" required>
											</div>
										</div>
									</div>
									<a class="registerRedirect" data-dismiss="modal" data-target="#popupRegistr" data-toggle="modal" href="#">Pas encore membre ? Inscrivez-vous</a>
									<div class="modal-form-actions">
										<button type="button" class="btn btn-outline-maincolor" data-dismiss="modal">Annuler</button>
										<button type="submit" class="btn btn-maincolor">Se connecter</button>
									</div>
								</form>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

	<div class="modal fade popupRegistr" id="popupRegistr" tabindex="-1" role="dialog" aria-hidden="true">
		<div class="modal-dialog modal-dialog-centered" role="document">
			<div class="modal-content ls border-r-def overflow-visible s-overlay s-mobile-overlay">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
				<div class="modal-body">
					<div class="container">
						<div class="row">
							<div class="col-12">
								<h4 class="mb-4">Inscription</h4>
								<form class="form-registration c-mb-40 c-gutter-40" method="post" action="actions/register.php">
									<input type="hidden" name="redirect" value="shop-cart.php">
									<div class="row">
										<div class="col-12">
											<div class="form-group">
												<input type="text" name="name" class="form-control" required placeholder="Identifiant" aria-required="true">
											</div>
										</div>
										<div class="col-12">
											<div class="form-group">
												<input type="password" name="password" class="form-control" placeholder="Mot de passe" aria-required="true" required>
											</div>
										</div>
										<div class="col-12">
											<div class="form-group">
												<input type="email" name="email" class="form-control" placeholder="Email" required aria-required="true">
											</div>
										</div>
										<div class="col-12">
											<div class="form-group">
												<input type="password" name="confirmPassword" class="form-control" placeholder="Confirmer le mot de passe" required aria-required="true">
											</div>
										</div>
										<div class="col-12">
											<div class="form-group checkbox-group">
												<input type="checkbox" id="agreed" name="agreed" value="agreed" required aria-required="true"><label for="agreed">J'accepte les conditions</label>
											</div>
										</div>
									</div>
									<div class="modal-form-actions">
										<button type="button" class="btn btn-outline-maincolor" data-dismiss="modal">Annuler</button>
										<button type="submit" class="btn btn-maincolor">Créer un compte</button>
									</div>
								</form>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

	<div id="canvas">
		<div id="box_wrapper">
			<header class="page_header ls s-overlay s-py-10">
				<div class="container-fluid">
					<div class="row align-items-center">
						<div class="col-xl-2 col-lg-3 col-11">
							<a href="index.html" class="logo">
								<img src="logo/rarsm-logo-wordmark-color.png" alt="RARSM">
							</a>
						</div>
						<div class="col-xl-8 col-lg-6 col-1">
							<nav class="top-nav">
								<ul class="nav sf-menu">
									<li><a href="index.html">Accueil</a></li>
									<li><a href="book.html">Ouvrage</a></li>
									<li><a href="author.html">Équipe</a></li>
									<li><a href="pricing.html">Shop</a></li>
									<li class="active"><a href="institutions.php">Institutions</a></li>
									<li><a href="activites.html">Activités</a></li>
									<li><a href="contact.html">Contact</a></li>
									<li class="menu-auth-item menu-auth-login">
										<a data-toggle="modal" href="#popupLogin">Se connecter</a>
									</li>
									<li class="menu-auth-item menu-auth-register">
										<a data-toggle="modal" href="#popupRegistr">S'inscrire</a>
									</li>
									<li class="menu-cart-mobile dropdown d-lg-none">
										<button aria-expanded="false" aria-haspopup="true" aria-label="Ouvrir le panier" class="dropdown-toggle dropdown-shopping-cart" data-toggle="dropdown" id="dropdown-shopping-cart-mobile" type="button">
											<i aria-hidden="true" class="fa fa-shopping-basket"></i>
											<span class="badge bg-maincolor">0</span>
											<span class="cart-total">$0.00</span>
										</button>
										<div aria-labelledby="dropdown-shopping-cart-mobile" class="dropdown-menu ls">
											<div class="widget woocommerce widget_shopping_cart">
												<div class="widget_shopping_cart_content">
													<ul class="woocommerce-mini-cart cart_list product_list_widget">
														<li class="woocommerce-mini-cart-item mini_cart_item">
															<a aria-label="Retirer cet article" class="remove" data-product_id="rarsm-book" data-product_sku="RARSM-PRINT" href="#">×</a>
															<a href="shop-cart.php"><img alt="Ouvrage RARSM" src="images/view-rarsm.JPG"></a>
															<a href="shop-cart.php">RARSM - Edition papier</a>
															<span class="quantity">0 ×
																<span class="woocommerce-Price-amount amount">
																	<span class="woocommerce-Price-currencySymbol">$</span>
																	0.00
																</span>
															</span>
														</li>
													</ul>
													<p class="woocommerce-mini-cart__total total">
														<strong>Sous-total :</strong>
														<span class="woocommerce-Price-amount amount">
															<span class="woocommerce-Price-currencySymbol">$</span>
															0.00
														</span>
													</p>
													<p class="woocommerce-mini-cart__buttons buttons">
														<a class="button wc-forward" href="shop-cart.php">Voir le panier</a>
														<a class="button checkout wc-forward" href="shop-checkout.php">Passer à la commande</a>
													</p>
												</div>
											</div>
										</div>
									</li>
									<li class="menu-language-mobile d-lg-none">
										<div class="language-switcher js-language-switcher">
											<button type="button" class="language-toggle js-language-toggle" aria-expanded="false" aria-label="Choix de la langue actuelle">Fr</button>
											<div class="language-menu" role="menu" aria-label="Choix de la langue">
												<button type="button" class="language-option js-language-option is-active" data-language="fr" role="menuitem">Fr</button>
												<button type="button" class="language-option js-language-option" data-language="en" role="menuitem">En</button>
											</div>
										</div>
									</li>
								</ul>
							</nav>
						</div>
						<div class="col-xl-2 col-lg-3 text-right d-none d-lg-block">
							<div class="header-utilities">
								<div class="dropdown">
									<a class="dropdown-toggle dropdown-shopping-cart" href="#" role="button" id="dropdown-shopping-cart" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" aria-label="Ouvrir le panier">
										<i class="fa fa-shopping-basket" aria-hidden="true"></i>
										<span class="badge bg-maincolor">0</span>
										<span class="cart-total">$0.00</span>
									</a>
									<div class="dropdown-menu dropdown-menu-right ls" aria-labelledby="dropdown-shopping-cart">
										<div class="widget woocommerce widget_shopping_cart">
											<div class="widget_shopping_cart_content">
												<ul class="woocommerce-mini-cart cart_list product_list_widget">
													<li class="woocommerce-mini-cart-item mini_cart_item">
														<a href="#" class="remove" aria-label="Retirer cet article" data-product_id="rarsm-book" data-product_sku="RARSM-PRINT">×</a>
														<a href="shop-cart.php"><img src="images/view-rarsm.JPG" alt="Ouvrage RARSM"></a>
														<a href="shop-cart.php">RARSM - Edition papier</a>
														<span class="quantity">0 ×
															<span class="woocommerce-Price-amount amount">
																<span class="woocommerce-Price-currencySymbol">$</span>
																0.00
															</span>
														</span>
													</li>
												</ul>
												<p class="woocommerce-mini-cart__total total">
													<strong>Sous-total :</strong>
													<span class="woocommerce-Price-amount amount">
														<span class="woocommerce-Price-currencySymbol">$</span>
														0.00
													</span>
												</p>
												<p class="woocommerce-mini-cart__buttons buttons">
													<a href="shop-cart.php" class="button wc-forward">Voir le panier</a>
													<a href="shop-checkout.php" class="button checkout wc-forward">Passer à la commande</a>
												</p>
											</div>
										</div>
									</div>
								</div>
								<div class="header-language-control">
									<div class="language-switcher js-language-switcher">
										<button type="button" class="language-toggle js-language-toggle" aria-expanded="false" aria-label="Choix de la langue actuelle">Fr</button>
										<div class="language-menu" role="menu" aria-label="Choix de la langue">
											<button type="button" class="language-option js-language-option is-active" data-language="fr" role="menuitem">Fr</button>
											<button type="button" class="language-option js-language-option" data-language="en" role="menuitem">En</button>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<span class="toggle_menu" aria-label="Ouvrir le menu mobile" role="button" tabindex="0"><span></span></span>
			</header>

			<section class="page_title ds s-parallax s-py-110" style="background: linear-gradient(135deg, rgba(7, 17, 63, 0.74) 0%, rgba(13, 30, 92, 0.64) 42%, rgba(32, 42, 82, 0.58) 58%, rgba(239, 59, 35, 0.30) 100%), url('images/rarsm-generated/page-title-rarsm-hero.png') no-repeat center/cover;">
				<div class="container">
					<div class="row">
						<div class="col-md-12 text-center">
							<h1 class="small-title"><?php echo rarsm_e($selected['name']); ?></h1>
							<ol class="breadcrumb">
								<li class="breadcrumb-item"><a href="index.html">Accueil</a></li>
								<li class="breadcrumb-item"><a href="institutions.php">Institutions</a></li>
								<li class="breadcrumb-item active">Fiche détail</li>
							</ol>
						</div>
					</div>
				</div>
			</section>

			<section class="ls ms s-py-90 s-py-xl-150 section-institution-detail-page">
				<div class="container">
					<?php if ($notFound): ?>
						<div class="institution-detail-alert">
							L’institution demandée n’a pas été trouvée. La première fiche disponible a été affichée par défaut.
						</div>
					<?php endif; ?>
					<div class="row c-gutter-30">
						<div class="col-lg-8">
							<article class="institution-detail-card">
								<div class="institution-detail-header">
									<div class="institution-detail-logo<?php echo $selectedLogoPath === '' ? ' institution-detail-logo--initials' : ''; ?>">
										<?php if ($selectedLogoPath !== ''): ?>
											<img src="<?php echo rarsm_e($selectedLogoPath); ?>" alt="Logo <?php echo rarsm_e($selected['name']); ?>" loading="lazy" decoding="async">
										<?php else: ?>
											<?php echo rarsm_e($selectedLogoInitials); ?>
										<?php endif; ?>
									</div>
									<div class="institution-detail-heading">
										<div class="institution-detail-meta">
											<span class="institution-detail-sector text-danger"><?php echo rarsm_e($selected['sector']); ?></span>
											
										</div>
										<h2><?php echo rarsm_e($selected['name']); ?></h2>
										<p class="institution-detail-lead"><?php echo rarsm_e($selected['summary']); ?></p>
									</div>
								</div>
								<div class="institution-detail-body">
									<div class="institution-detail-section">
										<h4 class="institution-detail-role-title">Rôle et périmètre d’action</h4>
										<div class="institution-detail-paragraphs"><?php echo rarsm_render_paragraphs($selected['details']); ?></div>
									</div>
									<?php if (!empty($selected['leader_name']) || !empty($selectedLeaderPhoto)): ?>
										<div class="institution-detail-section">
											<h4 class="institution-detail-leader-title">Responsable mis en avant</h4>
											<div class="institution-profile-card">
												<div class="institution-profile-media">
													<?php if ($selectedLeaderPhoto !== ''): ?>
														<span class="institution-profile-photo-frame" style="--institution-photo-position: <?php echo rarsm_e($selectedLeaderPhotoPosition); ?>; --institution-photo-scale: <?php echo rarsm_e(number_format($selectedLeaderPhotoScale, 2, '.', '')); ?>;">
															<img class="institution-profile-photo" src="<?php echo rarsm_e($selectedLeaderPhoto); ?>" alt="<?php echo rarsm_e($selected['leader_name']); ?>" loading="lazy" decoding="async">
														</span>
													<?php else: ?>
														<span class="institution-profile-placeholder"><?php echo rarsm_e(rarsm_initials(!empty($selected['leader_name']) ? $selected['leader_name'] : $selected['name'])); ?></span>
													<?php endif; ?>
												</div>
												<div class="institution-profile-copy">
													<strong><?php echo rarsm_e($selected['leader_name']); ?></strong>
													<?php if (!empty($selected['leader_role'])): ?>
														<span class="institution-profile-role"><?php echo rarsm_e($selected['leader_role']); ?></span>
													<?php endif; ?>
													<?php if (!empty($selected['quote'])): ?>
														<p class="institution-profile-note">
															<span class="institution-profile-quote"><?php echo rarsm_e($selected['quote']); ?></span>
															<?php if (!empty($selected['quote_source'])): ?>
																<span>
																	<span class="institution-profile-source-label">Source :</span>
																	<?php if ($selectedQuoteSourceUrl !== ''): ?>
																		<a href="<?php echo rarsm_e($selectedQuoteSourceUrl); ?>" target="_blank" rel="noopener"><?php echo rarsm_e($selected['quote_source']); ?></a>
																	<?php else: ?>
																		<?php echo rarsm_e($selected['quote_source']); ?>
																	<?php endif; ?>
																</span>
															<?php endif; ?>
														</p>
													<?php endif; ?>
												</div>
											</div>
										</div>
									<?php endif; ?>
									<div class="institution-detail-section">
										<h4 class="institution-detail-rarsm-title">Pourquoi cette institution compte dans le RARSM</h4>
										<p class="institution-detail-rarsm-note"><?php echo rarsm_e($selectedSectorNote); ?></p>
									</div>
									<?php if ($selected['website'] !== ''): ?>
										<div class="institution-detail-main-actions">
											<a href="<?php echo rarsm_e($selected['website']); ?>" class="btn btn-maincolor" target="_blank" rel="noopener">Visiter le site officiel</a>
											<a href="institutions.php" class="btn btn-outline-maincolor">Retour aux institutions</a>
										</div>
									<?php else: ?>
										<div class="institution-detail-main-actions">
											<a href="institutions.php" class="btn btn-outline-maincolor">Retour aux institutions</a>
										</div>
									<?php endif; ?>
								</div>
							</article>
						</div>
						<div class="col-lg-4">
							<aside class="institution-sidebar-stack">
                                <div class="institution-side-card">
									<span class="activities-eyebrow">AUTRES INSTITUTIONS</span>
									<h4>Suggestions à consulter</h4>
									<ul class="institution-suggestion-list list-unstyled">
										<?php foreach ($suggestions as $slug => $institution): ?>
											<li>
												<a class="institution-suggestion-item" href="istitutions-details.php?institution=<?php echo rarsm_e($slug); ?>">
													<?php $suggestionLogoPath = isset($institution['logo_path']) && rarsm_public_file_exists($institution['logo_path']) ? $institution['logo_path'] : ''; ?>
													<?php $suggestionLogoInitials = !empty($institution['logo_initials']) ? (string) $institution['logo_initials'] : rarsm_initials($institution['name']); ?>
													<span class="institution-suggestion-logo<?php echo $suggestionLogoPath === '' ? ' institution-suggestion-logo--initials' : ''; ?>">
														<?php if ($suggestionLogoPath !== ''): ?>
															<img src="<?php echo rarsm_e($suggestionLogoPath); ?>" alt="Logo <?php echo rarsm_e($institution['name']); ?>" loading="lazy" decoding="async">
														<?php else: ?>
															<?php echo rarsm_e($suggestionLogoInitials); ?>
														<?php endif; ?>
													</span>
													<span class="institution-suggestion-copy">
														<strong><?php echo rarsm_e($institution['name']); ?></strong>
														<small><?php echo rarsm_e($institution['sector']); ?></small>
													</span>
												</a>
											</li>
										<?php endforeach; ?>
									</ul>
								</div>
								<div class="institution-side-card">
									<span class="activities-eyebrow">ACTIONS RAPIDES</span>
									<h4>Accès utiles</h4>
									<div class="institution-quick-actions">
										<a href="institutions.php" class="btn btn-maincolor">Retour au panorama</a>
										<a href="shop-cart.php" class="btn btn-outline-maincolor">Commander l’ouvrage</a>
										<a href="contact.html" class="btn btn-outline-maincolor">Contacter l'équipe</a>
										
									</div>
								</div>
								
							</aside>
						</div>
					</div>
				</div>
			</section>

			<footer class="page_footer ds s-py-85 s-py-xl-155">
				<div class="container">
					<div class="row">
						<div class="col-12 text-center animate" data-animation="fadeInUp">
							<a href="./" class="logo justify-content-center">
								<img src="logo/rarsm-logo-wordmark-white.png" alt="RARSM">
							</a>
							<div class="divider-40"></div>
							<div class="widget widget_nav_menu nav-in-line">
								<ul class="menu">
									<li class="menu-item"><a href="index.html">Accueil</a></li>
									<li class="menu-item"><a href="book.html">Ouvrage</a></li>
									<li class="menu-item"><a href="author.html">Équipe</a></li>
									<li class="menu-item"><a href="pricing.html">Shop</a></li>
									<li class="menu-item"><a href="institutions.php">Institutions</a></li>
									<li class="menu-item"><a href="activites.html">Activités</a></li>
									<li class="menu-item"><a href="contact.html">Contact</a></li>
									<li class="menu-item"><a href="faq.html">FAQ</a></li>
								</ul>
							</div>
						</div>
					</div>
				</div>
			</footer>

			<section class="page_copyright ls s-py-20 s-py-xl-50">
				<div class="container">
					<div class="row align-items-center">
						<div class="col-md-12 text-center color-dark">
							<p>&copy; <span class="copyright_year">2026</span> RARSM - Recueil des Actes Réglementaires du Secteur Minier. Tous droits réservés.</p>
						</div>
					</div>
				</div>
			</section>
		</div>
	</div>

	<script src="js/compressed.js"></script>
	<script src="js/rarsm-i18n.js?v=20260810-header-balance-v2"></script>
	<script src="js/rarsm-ui.js?v=20260810-header-balance-v2"></script>
</body>
</html>
