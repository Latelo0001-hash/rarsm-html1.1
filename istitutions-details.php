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

$institutions = [
	'igm' => [
		'name' => "Inspection Generale des Mines - IGM",
		'sector' => "Mines",
		'summary' => "Service public d'inspection, de controle et d'audit des activites minieres et des carrieres en RDC.",
		'details' => "L'Inspection Generale des Mines est un service public place sous l'autorite du ministere des Mines. Elle assure l'inspection, le controle et l'audit des activites minieres et des carrieres sur l'ensemble du territoire national. Elle veille au respect du Code minier, du Reglement minier et des autres textes applicables, tout en renforcant la tracabilite des minerais et la lutte contre la fraude, la contrebande et le commerce illicite des produits miniers.",
		'website' => '',
		'website_label' => '',
	],
	'ctcpm' => [
		'name' => "Cellule Technique de Coordination et de Planification Miniere - CTCPM",
		'sector' => "Mines",
		'summary' => "Organe technique de conseil, d'etudes, de coordination et de planification rattache au ministere des Mines.",
		'details' => "La CTCPM assiste le ministre dans la conception et la mise en oeuvre de la politique miniere nationale. Elle analyse les donnees economiques, techniques, juridiques et statistiques relatives au secteur minier, centralise certaines informations sur la production et les exportations, et formule des avis sur les projets miniers, les strategies sectorielles et les reformes reglementaires.",
		'website' => 'https://www.ctcpm.cd/',
		'website_label' => 'ctcpm.cd',
	],
	'fomin' => [
		'name' => "Fonds Minier pour les Generations Futures - FOMIN",
		'sector' => "Mines",
		'summary' => "Etablissement public charge de constituer des richesses financieres et materielles au profit de l'apres-mine.",
		'details' => "Le FOMIN a pour mission de preserver une partie des revenus tires des ressources miniere non renouvelables au profit des generations futures. Il gere notamment une quotite de la redevance miniere selon des principes de securite, de rentabilite, de transparence et de responsabilite, et participe a la perennisation de la recherche geologique et miniere.",
		'website' => 'https://www.fomin.cd/',
		'website_label' => 'fomin.cd',
	],
	'ceec' => [
		'name' => "Centre d'Expertise, d'Evaluation et de Certification - CEEC",
		'sector' => "Mines",
		'summary' => "Autorite chargee de l'expertise, de l'evaluation et de la certification de certaines substances minerales.",
		'details' => "Le CEEC intervient principalement dans les filieres des substances minerales precieuses, semi-precieuses et strategiques. Il analyse la qualite, la teneur, la quantite et la valeur marchande des produits miniers, delivre des certificats de conformite, d'origine et de tracabilite, et contribue a la lutte contre la sous-evaluation, la fraude et l'exportation illegale des minerais.",
		'website' => 'https://ceec.cd/',
		'website_label' => 'ceec.cd',
	],
	'sgnc' => [
		'name' => "Service Geologique National du Congo - SGNC",
		'sector' => "Mines",
		'summary' => "Etablissement public de reference charge de la connaissance scientifique du sol et du sous-sol congolais.",
		'details' => "Le SGNC acquiert, centralise, traite, conserve et diffuse les donnees geologiques et minieres nationales. Il realise des travaux de cartographie geologique, geophysique, geochimique et hydrogeologique, identifie les formations geologiques, evalue le potentiel mineral du pays et etudie aussi les risques naturels lies a la geologie.",
		'website' => 'https://sgnc.cd/',
		'website_label' => 'sgnc.cd',
	],
	'cami' => [
		'name' => "Cadastre Minier - CAMI",
		'sector' => "Mines",
		'summary' => "Etablissement public charge de la gestion du domaine minier ainsi que des droits miniers et de carrieres.",
		'details' => "Le CAMI recoit, enregistre et traite les demandes d'octroi, de renouvellement, de transformation, d'extension ou de transfert des droits miniers. Il verifie la disponibilite des perimetres, tient les registres officiels des permis et etablit les cartes cadastrales qui permettent de prevenir les chevauchements et les conflits entre titres.",
		'website' => 'https://cami.cd/',
		'website_label' => 'cami.cd',
	],
	'saemape' => [
		'name' => "Service d'Assistance et d'Encadrement de l'Exploitation miniere artisanale et a petite echelle - SAEMAPE",
		'sector' => "Mines",
		'summary' => "Service public charge de l'assistance et de l'encadrement des exploitants miniers artisanaux et des cooperatives.",
		'details' => "Le SAEMAPE favorise la formalisation et la professionnalisation de l'exploitation miniere artisanale. Il apporte une assistance technique, administrative et manageriale, sensibilise aux regles de securite, de protection de l'environnement et de tracabilite, et contribue a la lutte contre le travail des enfants, les accidents et les pratiques minieres dangereuses.",
		'website' => 'https://saemape.cd/',
		'website_label' => 'saemape.cd',
	],
	'arecoms' => [
		'name' => "Autorite de Regulation et de Controle des Marches des Substances Minerales Strategiques - ARECOMS",
		'sector' => "Mines",
		'summary' => "Autorite publique chargee de la regulation et du controle des marches des substances minerales strategiques.",
		'details' => "L'ARECOMS veille a l'organisation, a l'assainissement et a la stabilite des marches des substances minerales declarees strategiques en RDC, notamment le cobalt, le germanium et la colombo-tantalite. Elle controle les activites de production, d'achat, de traitement, de commercialisation et d'exportation relevant de son mandat, et propose au Gouvernement des mesures de sauvegarde et des reformes sectorielles.",
		'website' => '',
		'website_label' => '',
	],
	'cnlfm' => [
		'name' => "Commission Nationale de Lutte contre la Fraude Miniere - CNLFM",
		'sector' => "Mines",
		'summary' => "Mecanisme de coordination entre plusieurs services publics impliques dans la lutte contre la fraude et la contrebande minieres.",
		'details' => "La CNLFM favorise la collaboration entre les Mines, l'Interieur, la Justice, la Defense, les regies financieres et les organismes techniques. Elle organise l'echange d'informations, les missions conjointes de controle et les actions de terrain contre l'exploitation clandestine, le transport irregulier, la dissimulation et l'exportation frauduleuse des produits miniers.",
		'website' => 'https://ceec.cd/fraude-miniere',
		'website_label' => 'Plus d\'informations',
	],
	'ministere-finances' => [
		'name' => "Ministere des Finances",
		'sector' => "Finances",
		'summary' => "Organe charge de la gestion generale des finances publiques et de la mobilisation des recettes du pouvoir central.",
		'details' => "Le ministere des Finances conçoit et met en oeuvre la politique fiscale, financiere, comptable et budgetaire de l'Etat. Dans le secteur minier, il veille a la perception des impots, droits de douane, taxes, redevances et autres recettes revenant au pouvoir central, tout en suivant la tresorerie, la dette publique et les reformes de transparence et de digitalisation.",
		'website' => 'https://finances.gouv.cd/',
		'website_label' => 'finances.gouv.cd',
	],
	'dgi' => [
		'name' => "Direction Generale des Impots - DGI",
		'sector' => "Finances",
		'summary' => "Regie financiere chargee de l'assiette, du controle, du recouvrement et du contentieux des impots du pouvoir central.",
		'details' => "La DGI identifie les contribuables, recoit les declarations fiscales et controle leur exactitude. Elle recouvre notamment les impots sur les benefices, les revenus, les remunerations et la TVA selon la legislation applicable, et intervient dans le secteur minier pour suivre les obligations fiscales des societes, sous-traitants, fournisseurs et travailleurs concernes.",
		'website' => 'https://dgi.gouv.cd/',
		'website_label' => 'dgi.gouv.cd',
	],
	'dgda' => [
		'name' => "Direction Generale des Douanes et Accises - DGDA",
		'sector' => "Finances",
		'summary' => "Regie financiere chargee de l'application de la legislation douaniere et accisienne sur toute l'etendue du territoire national.",
		'details' => "La DGDA percoit les droits, taxes et redevances dus a l'importation, a l'exportation, au transit et a l'entreposage des marchandises. Dans le secteur minier, elle verifie les formalites douanieres relatives aux equipements importes et aux produits miniers exportes, tout en participant a la lutte contre la fraude, la contrebande et la fausse declaration.",
		'website' => 'https://douane.gouv.cd/',
		'website_label' => 'douane.gouv.cd',
	],
	'dgrad' => [
		'name' => "Direction Generale des Recettes Administratives, Judiciaires, Domaniales et de Participations - DGRAD",
		'sector' => "Finances",
		'summary' => "Service public charge de l'ordonnancement et du recouvrement des recettes non fiscales du pouvoir central.",
		'details' => "La DGRAD verifie les operations des services d'assiette, etablit les titres de perception et assure le recouvrement des recettes non fiscales provenant notamment des mines, de l'environnement, du commerce, des transports et des affaires foncieres. Sa digitalisation vise a renforcer la tracabilite et a limiter les paiements irreguliers.",
		'website' => 'https://dgrad.gouv.cd/',
		'website_label' => 'dgrad.gouv.cd',
	],
	'recettes-provinciales' => [
		'name' => "Directions provinciales des recettes",
		'sector' => "Finances",
		'summary' => "Regies financieres instituees par les provinces pour mobiliser les revenus relevant de leurs competences.",
		'details' => "Leur appellation et leur organisation varient selon les provinces, mais leur mission reste d'identifier les contribuables, d'ordonner, de recouvrer et de controler les impots, taxes, droits et redevances provinciaux. Dans les provinces minieres, elles jouent un role important dans le suivi des operateurs et des activites economiques liees aux mines.",
		'website' => '',
		'website_label' => '',
	],
	'ministere-transports' => [
		'name' => "Ministere des Transports, Voies de Communication et Desenclavement",
		'sector' => "Transports",
		'summary' => "Ministere charge de la politique nationale des transports et des corridors logistiques en RDC.",
		'details' => "Ce ministere prepare les normes, strategies et programmes relatifs aux transports routier, ferroviaire, fluvial, lacustre, maritime et aerien. Dans le secteur minier, il intervient dans l'organisation des corridors servant a acheminer les minerais et les equipements, et son action influence directement les couts et la competitivite des exportations miniere congolaises.",
		'website' => 'https://transports.gouv.cd/',
		'website_label' => 'transports.gouv.cd',
	],
	'ogefrem' => [
		'name' => "Office de Gestion du Fret Multimodal - OGEFREM",
		'sector' => "Transports",
		'summary' => "Etablissement public charge de l'encadrement et de la regulation du fret congolais.",
		'details' => "L'OGEFREM defend les interets des chargeurs congolais, suit les cargaisons importees ou exportees par les differents corridors desservant la RDC et gere des documents de suivi du fret. Il collecte et analyse des donnees sur les volumes, les itineraires et les couts logistiques, ce qui en fait un acteur important pour le suivi des minerais exportes et des equipements importes.",
		'website' => 'https://ogefrem.cd/',
		'website_label' => 'ogefrem.cd',
	],
	'lmc' => [
		'name' => "Lignes Maritimes Congolaises - LMC",
		'sector' => "Transports",
		'summary' => "Armement maritime national de la RDC, intervenant dans l'organisation du transport maritime et multimodal.",
		'details' => "La LMC assure a la RDC l'exercice et la jouissance de ses droits de trafic maritime. Elle peut transporter des marchandises par ses propres navires, des navires affretes ou des accords avec d'autres transporteurs. Son role est strategique pour un pays dependant fortement des ports et corridors regionaux pour son commerce exterieur, y compris les produits miniers.",
		'website' => 'https://lmc.cd/',
		'website_label' => 'lmc.cd',
	],
	'ministere-recherche' => [
		'name' => "Ministere charge de la Recherche scientifique et de l'Innovation technologique",
		'sector' => "Recherche",
		'summary' => "Ministere charge de la politique nationale de recherche, d'innovation et de developpement technologique.",
		'details' => "Ce ministere oriente les programmes scientifiques vers les besoins economiques, industriels, environnementaux et sociaux du pays. Il supervise les centres, instituts et organismes publics de recherche, encourage la formation des chercheurs et accompagne les institutions travaillant sur la geologie, la metallurgie, l'environnement et l'energie dans le champ minier.",
		'website' => 'https://minesursi.gouv.cd/',
		'website_label' => 'minesursi.gouv.cd',
	],
	'cnpri' => [
		'name' => "Comite National de Protection contre les Rayonnements Ionisants - CNPRI",
		'sector' => "Recherche",
		'summary' => "Autorite reglementaire nationale chargee de la surete nucleaire, de la securite radiologique et de la radioprotection.",
		'details' => "Le CNPRI veille a la protection des travailleurs, des patients, du public, des biens et de l'environnement. Il controle les activites qui utilisent, detiennent, transportent, importent ou exportent des sources radioactives. Dans le secteur minier, son intervention est importante lorsque des minerais contiennent des substances radioactives ou lorsque des appareils utilisant des rayonnements sont employes.",
		'website' => 'https://cnpri.cd/',
		'website_label' => 'cnpri.cd',
	],
	'cgea' => [
		'name' => "Commissariat General a l'Energie Atomique - CGEA",
		'sector' => "Recherche",
		'summary' => "Institution congolaise chargee de promouvoir et coordonner les recherches liees a l'utilisation pacifique de l'energie atomique.",
		'details' => "Le CGEA developpe des programmes scientifiques et technologiques dans plusieurs domaines, notamment la physique, la chimie, l'agronomie, les sciences de la vie et les techniques appliquees. Dans le secteur minier, ses techniques peuvent etre utilisees pour caracteriser des minerais, etudier des materiaux et detecter certains elements.",
		'website' => 'https://cgea-rdc.org/',
		'website_label' => 'cgea-rdc.org',
	],
	'crgm' => [
		'name' => "Centre de Recherches Geologiques et Minieres - CRGM",
		'sector' => "Recherche",
		'summary' => "Etablissement public a caractere scientifique et technique specialise dans les sciences de la Terre.",
		'details' => "Le CRGM conçoit et execute des projets destines a ameliorer la connaissance du sol et du sous-sol de la RDC. Il realise des travaux de prospection geologique et miniere, produit des cartes geologiques, gitologiques, hydrogeologiques et geotechniques, et etudie aussi les risques geologiques ainsi que les effets environnementaux des activites minieres.",
		'website' => '',
		'website_label' => '',
	],
	'ministere-commerce-exterieur' => [
		'name' => "Ministere du Commerce exterieur",
		'sector' => "Commerce",
		'summary' => "Ministere charge de la politique nationale relative aux importations, exportations, transit et relations commerciales internationales.",
		'details' => "Le ministere du Commerce exterieur elabore les orientations destinees a promouvoir les produits congolais sur les marches etrangers, negocie et suit les accords commerciaux et supervise ou collabore avec des organismes comme l'OCC. Dans le secteur minier, il encadre les aspects commerciaux des exportations de minerais et de produits transformes.",
		'website' => '',
		'website_label' => '',
	],
	'occ' => [
		'name' => "Office Congolais de Controle - OCC",
		'sector' => "Commerce",
		'summary' => "Etablissement public charge du controle de la qualite, de la conformite et de certaines analyses techniques.",
		'details' => "L'OCC controle les marchandises et produits importes, exportes ou fabriques localement. Ses missions portent sur la qualite, la quantite, la conformite, le prix et le respect des normes applicables. Dans le domaine minier, il peut intervenir dans le controle de certains produits, equipements et operations lies au commerce exterieur.",
		'website' => 'https://www.occ.cd/',
		'website_label' => 'occ.cd',
	],
	'ministere-environnement' => [
		'name' => "Ministere de l'Environnement, Developpement durable et Nouvelle Economie du climat",
		'sector' => "Environnement",
		'summary' => "Organe gouvernemental charge de definir et mettre en oeuvre la politique nationale en matiere d'environnement.",
		'details' => "Ce ministere veille a la protection des ecosystemes, de la biodiversite, des forets, des ressources naturelles et du cadre de vie des populations. Dans le domaine minier, il intervient dans la prevention et la gestion des pollutions de l'air, de l'eau et des sols, et suit l'application des conventions environnementales ratifiees par la RDC.",
		'website' => 'https://medd.gouv.cd/',
		'website_label' => 'medd.gouv.cd',
	],
	'ace' => [
		'name' => "Agence Congolaise de l'Environnement - ACE",
		'sector' => "Environnement",
		'summary' => "Etablissement public charge de l'evaluation environnementale et sociale des projets de developpement en RDC.",
		'details' => "L'ACE examine les etudes d'impact environnemental et social, verifie les rapports des promoteurs et bureaux d'etudes agrees, formule des avis de conformite et peut demander des corrections ou des mesures d'attenuation. Dans le secteur minier, elle evalue notamment les risques lies aux rejets, aux dechets, a la consommation d'eau, aux deplacements de populations et a la fermeture des sites.",
		'website' => 'https://medd.gouv.cd/ace/',
		'website_label' => 'medd.gouv.cd/ace',
	],
	'egc' => [
		'name' => "Entreprise Generale du Cobalt - EGC",
		'sector' => "Autres",
		'summary' => "Filiale de la Gecamines chargee d'encadrer l'achat, le traitement et la commercialisation du cobalt artisanal.",
		'details' => "L'EGC a ete creee pour integrer la production artisanale de cobalt dans une chaine d'approvisionnement officielle, controlee et transparente. Elle collabore avec les cooperatives, les services publics, les acheteurs et les partenaires techniques, et vise a reduire le travail des enfants, les violations des droits humains, les circuits clandestins et les pertes de recettes publiques.",
		'website' => 'https://egcobalt.cd/',
		'website_label' => 'egcobalt.cd',
	],
	'itie-rdc' => [
		'name' => "Initiative pour la Transparence dans les Industries Extractives - ITIE-RDC",
		'sector' => "Autres",
		'summary' => "Mecanisme national de mise en oeuvre de l'Initiative pour la Transparence dans les Industries Extractives.",
		'details' => "L'ITIE-RDC reunit des representants de l'Etat, des entreprises extractives et de la societe civile pour promouvoir la transparence et la bonne gouvernance des revenus des secteurs minier, petrolier et gazier. Elle collecte, analyse et publie des donnees sur la production, les exportations, les paiements des entreprises et les recettes percues par l'Etat, ce qui nourrit le debat public et oriente les reformes.",
		'website' => 'https://www.itierdc.net/',
		'website_label' => 'itierdc.net',
	],
];

$sectorNotes = [
	'Mines' => "Cette famille d'institutions couvre l'acces aux titres, le controle, la certification, la tracabilite et l'encadrement du secteur. Leur lecture dans le RARSM aide a comprendre toute la chaine administrative et technique de l'activite miniere.",
	'Finances' => "Ces institutions structurent la collecte des recettes publiques, la fiscalite et le controle des flux financiers lies aux activites minieres. Elles sont essentielles pour comprendre comment les revenus du secteur sont transformes en ressources publiques.",
	'Transports' => "Le secteur des transports et de la logistique est determinant pour les couts, les delais et la competitivite des exportations miniere. Il relie les sites de production aux corridors, ports et points de sortie du pays.",
	'Recherche' => "Les institutions de recherche et d'innovation fournissent l'appui scientifique, technique et analytique utile a la geologie, aux materiaux, a la radioprotection et a la connaissance du sous-sol.",
	'Commerce' => "Ce bloc relie le secteur minier aux flux d'import-export, aux controles de qualite et a la competitivite des produits sur les marches nationaux et internationaux.",
	'Environnement' => "Ces institutions interviennent dans l'evaluation des impacts, la prevention des pollutions et la protection des ecosystemes et des communautes autour des projets extractifs.",
	'Autres' => "Ces organismes jouent un role transversal dans la formalisation, la transparence et la gouvernance des chaines de valeur extractives, en particulier pour le cobalt artisanal et la publication des donnees sectorielles.",
];

$requestedSlug = isset($_GET['institution']) ? strtolower(preg_replace('/[^a-z0-9\-]/', '', (string) $_GET['institution'])) : 'igm';
$notFound = !isset($institutions[$requestedSlug]);
$selectedSlug = $notFound ? 'igm' : $requestedSlug;
$selected = $institutions[$selectedSlug];
$selectedSectorNote = isset($sectorNotes[$selected['sector']]) ? $sectorNotes[$selected['sector']] : "Cette institution participe a la lecture pratique de l'ecosysteme minier, administratif et reglementaire couvert par le recueil.";
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
	<link rel="stylesheet" href="css/site.css">
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
										<button type="submit" class="btn btn-maincolor">Creer un compte</button>
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
									<li><a href="book.html">Livre</a></li>
									<li><a href="author.html">Auteur</a></li>
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
														<a href="shop-cart.php"><img src="images/view-rarsm.JPG" alt="Livre RARSM"></a>
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
													<a href="shop-checkout.php" class="button checkout wc-forward">Passer a la commande</a>
												</p>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<span class="toggle_menu" aria-label="Ouvrir le menu mobile" role="button" tabindex="0"><span></span></span>
			</header>

			<section class="page_title ds s-parallax s-py-110">
				<div class="container">
					<div class="row">
						<div class="col-md-12 text-center">
							<h1 class="small-title"><?php echo rarsm_e($selected['name']); ?></h1>
							<ol class="breadcrumb">
								<li class="breadcrumb-item"><a href="index.html">Accueil</a></li>
								<li class="breadcrumb-item"><a href="institutions.php">Institutions</a></li>
								<li class="breadcrumb-item active">Fiche detail</li>
							</ol>
						</div>
					</div>
				</div>
			</section>

			<section class="ls ms s-py-90 s-py-xl-150 section-institution-detail-page">
				<div class="container">
					<?php if ($notFound): ?>
						<div class="institution-detail-alert">
							L'institution demandee n'a pas ete trouvee. La premiere fiche disponible a ete affichee par defaut.
						</div>
					<?php endif; ?>
					<div class="row c-gutter-30">
						<div class="col-lg-8">
							<article class="institution-detail-card">
								<div class="institution-detail-header">
									<div class="institution-detail-logo"><?php echo rarsm_e(rarsm_initials($selected['name'])); ?></div>
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
										<h4>Role et perimetre d'action</h4>
										<p><?php echo rarsm_e($selected['details']); ?></p>
									</div>
									<div class="institution-detail-section">
										<h4>Pourquoi cette institution compte dans le RARSM</h4>
										<p><?php echo rarsm_e($selectedSectorNote); ?></p>
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
									<h4>Suggestions a consulter</h4>
									<ul class="institution-suggestion-list list-unstyled">
										<?php foreach ($suggestions as $slug => $institution): ?>
											<li>
												<a class="institution-suggestion-item" href="istitutions-details.php?institution=<?php echo rarsm_e($slug); ?>">
													<span class="institution-suggestion-logo"><?php echo rarsm_e(rarsm_initials($institution['name'])); ?></span>
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
									<h4>Acces utiles</h4>
									<div class="institution-quick-actions">
										<a href="institutions.php" class="btn btn-maincolor">Retour au panorama</a>
										<a href="shop-cart.php" class="btn btn-outline-maincolor">Commander le livre</a>
										<a href="contact.html" class="btn btn-outline-maincolor">Contacter l'equipe</a>
										<?php if ($selected['website'] !== ''): ?>
											<a href="<?php echo rarsm_e($selected['website']); ?>" class="btn btn-outline-maincolor" target="_blank" rel="noopener">Site officiel</a>
										<?php endif; ?>
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
									<li class="menu-item"><a href="book.html">Livre</a></li>
									<li class="menu-item"><a href="author.html">Auteur</a></li>
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
							<p>&copy; <span class="copyright_year">2026</span> RARSM - Recueil des Actes Reglementaires du Secteur Minier. Tous droits reserves.</p>
						</div>
					</div>
				</div>
			</section>
		</div>
	</div>

	<script src="js/compressed.js"></script>
	<script src="js/rarsm-ui.js"></script>
</body>
</html>
