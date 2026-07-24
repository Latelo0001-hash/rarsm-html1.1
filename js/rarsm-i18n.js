(function (window, document, $) {
	"use strict";

	var STORAGE_KEY = "rarsm-language";
	var COOKIE_NAME = "rarsm_lang";
	var listeners = [];

	var titles = {
		"index.html": "RARSM | Mining Sector Regulatory Compendium",
		"book.html": "RARSM | The Book",
		"author.html": "RARSM | The Author",
		"pricing.html": "RARSM | Shop",
		"institutions.html": "RARSM | Institutions",
		"institutions.php": "RARSM | Institutions",
		"activites.html": "RARSM | Activities",
		"activites-details.php": "RARSM | Activity Details",
		"contact.html": "RARSM | Contact",
		"faq.html": "RARSM | FAQ",
		"shop-cart.php": "RARSM | Cart",
		"shop-checkout.php": "RARSM | Checkout",
		"shop-account-login.php": "RARSM | Sign In",
		"shop-account-register.php": "RARSM | Create Account",
		"shop-account-orders.php": "RARSM | My Orders",
		"success.php": "RARSM | Payment Success",
		"cancel.php": "RARSM | Payment Cancelled",
		"pending.php": "RARSM | Pending Payment",
		"istitutions-details.php": "RARSM | Institution Details"
	};

	var pageAliases = {
		"institutions.php": "institutions.html"
	};

	var institutionSectorLabels = {
		"Mines": "Mining",
		"Finances": "Finance",
		"Transports": "Transport",
		"Recherche": "Research",
		"Commerce": "Trade",
		"Environnement": "Environment",
		"Autres": "Other"
	};

	var institutionSectorNotesEn = {
		"Mines": "This family of institutions covers access to titles, oversight, certification, traceability and the supervision of the sector. Reading them through RARSM helps explain the full administrative and technical chain of mining activity.",
		"Finances": "These institutions structure public revenue collection, taxation and oversight of the financial flows linked to mining activities. They are essential to understanding how mining revenues are turned into public resources.",
		"Transports": "The transport and logistics sector plays a decisive role in costs, delays and the competitiveness of mining exports. It connects production sites to corridors, ports and exit points.",
		"Recherche": "Research and innovation institutions provide the scientific, technical and analytical support needed for geology, materials, radiation protection and knowledge of the subsoil.",
		"Commerce": "This group connects the mining sector to import-export flows, quality control and the competitiveness of products on national and international markets.",
		"Environnement": "These institutions intervene in impact assessment, pollution prevention and the protection of ecosystems and communities around extractive projects.",
		"Autres": "These bodies play a cross-cutting role in the formalization, transparency and governance of extractive value chains, especially for artisanal cobalt and sector data disclosure.",
		default: "This institution contributes to a practical reading of the mining, administrative and regulatory ecosystem covered by the compendium."
	};

	var institutionDetailDictionary = {
		"igm": {
			name: "General Inspectorate of Mines - IGM",
			sector: "Mines",
			summary: "Public inspection, oversight and audit service for mining and quarrying activities in the DRC.",
			details: "The General Inspectorate of Mines operates under the authority of the Ministry of Mines. It carries out inspection, control and audit missions over mining and quarrying activities across the country. It ensures compliance with the Mining Code, the Mining Regulations and other applicable instruments, while strengthening mineral traceability and the fight against fraud, smuggling and illicit trade in mining products."
		},
		"ctcpm": {
			name: "Technical Unit for Mining Coordination and Planning - CTCPM",
			sector: "Mines",
			summary: "Technical advisory, study, coordination and planning body attached to the Ministry of Mines.",
			details: "CTCPM assists the minister in the design and implementation of national mining policy. It analyzes economic, technical, legal and statistical data related to the sector, centralizes key information on production and exports, and issues opinions on mining projects, sector strategies and regulatory reforms."
		},
		"fomin": {
			name: "Mining Fund for Future Generations - FOMIN",
			sector: "Mines",
			summary: "Public institution responsible for building financial and material reserves for the post-mining future.",
			details: "FOMIN is tasked with preserving part of the income derived from non-renewable mining resources for future generations. It notably manages a share of mining royalties according to principles of security, profitability, transparency and accountability, and contributes to the long-term support of geological and mining research."
		},
		"ceec": {
			name: "Center for Expertise, Evaluation and Certification - CEEC",
			sector: "Mines",
			summary: "Authority responsible for the expertise, valuation and certification of certain mineral substances.",
			details: "CEEC mainly operates in the precious, semi-precious and strategic mineral sectors. It analyzes the quality, grade, quantity and market value of mining products, issues conformity, origin and traceability certificates, and contributes to the fight against undervaluation, fraud and illegal mineral exports."
		},
		"sgnc": {
			name: "National Geological Service of the Congo - SGNC",
			sector: "Mines",
			summary: "Leading public institution responsible for scientific knowledge of the Congolese soil and subsoil.",
			details: "SGNC acquires, centralizes, processes, preserves and disseminates national geological and mining data. It conducts geological, geophysical, geochemical and hydrogeological mapping, identifies geological formations, assesses the country's mineral potential and studies natural risks linked to geology."
		},
		"cami": {
			name: "Mining Cadastre - CAMI",
			sector: "Mines",
			summary: "Public institution responsible for managing the mining domain and mining and quarry rights.",
			details: "CAMI receives, registers and processes applications for the granting, renewal, conversion, extension or transfer of mining rights. It verifies the availability of perimeters, maintains the official title registers and produces cadastral maps that help prevent overlaps and conflicts between titles."
		},
		"saemape": {
			name: "Service for Assistance and Supervision of Artisanal and Small-Scale Mining - SAEMAPE",
			sector: "Mines",
			summary: "Public service responsible for assisting and supervising artisanal miners and cooperatives.",
			details: "SAEMAPE promotes the formalization and professionalization of artisanal mining. It provides technical, administrative and managerial support, raises awareness of safety, environmental protection and traceability rules, and contributes to the fight against child labor, accidents and dangerous mining practices."
		},
		"arecoms": {
			name: "Authority for the Regulation and Control of Strategic Mineral Markets - ARECOMS",
			sector: "Mines",
			summary: "Public authority responsible for regulating and overseeing markets for strategic mineral substances.",
			details: "ARECOMS works to organize, sanitize and stabilize the markets for mineral substances declared strategic in the DRC, notably cobalt, germanium and coltan. It supervises production, purchasing, processing, marketing and export activities falling within its mandate, and proposes safeguard measures and sector reforms to the Government."
		},
		"cnlfm": {
			name: "National Commission to Combat Mining Fraud - CNLFM",
			sector: "Mines",
			summary: "Coordination mechanism bringing together several public services involved in the fight against mining fraud and smuggling.",
			details: "CNLFM encourages cooperation between the ministries and services responsible for mines, the interior, justice, defense, public revenue and technical control. It organizes information-sharing, joint control missions and field actions against clandestine mining, irregular transport, concealment and fraudulent export of mining products."
		},
		"ministere-finances": {
			name: "Ministry of Finance",
			sector: "Finances",
			summary: "Government body responsible for managing public finances and mobilizing central government revenue.",
			details: "The Ministry of Finance designs and implements the State's fiscal, financial, accounting and budgetary policy. In the mining sector, it oversees the collection of taxes, customs duties, royalties and other revenues owed to the central government, while monitoring treasury, public debt and reforms in transparency and digitalization."
		},
		"dgi": {
			name: "Directorate General of Taxes - DGI",
			sector: "Finances",
			summary: "Revenue authority responsible for the assessment, audit, collection and litigation of central government taxes.",
			details: "DGI identifies taxpayers, receives tax declarations and checks their accuracy. It collects taxes on profits, income, wages and VAT under the applicable legislation, and in the mining sector it monitors the tax obligations of companies, subcontractors, suppliers and workers concerned."
		},
		"dgda": {
			name: "Directorate General of Customs and Excise - DGDA",
			sector: "Finances",
			summary: "Revenue authority responsible for enforcing customs and excise legislation throughout the country.",
			details: "DGDA collects duties, taxes and fees due on imports, exports, transit and warehousing of goods. In the mining sector, it verifies the customs formalities linked to imported equipment and exported mineral products, while taking part in the fight against fraud, smuggling and false declarations."
		},
		"dgrad": {
			name: "Directorate General of Administrative, Judicial, State and Participation Revenues - DGRAD",
			sector: "Finances",
			summary: "Public service responsible for ordering and collecting non-tax revenues for the central government.",
			details: "DGRAD reviews the operations of assessment services, issues collection titles and recovers non-tax revenues generated notably by mines, the environment, trade, transport and land-related sectors. Its digitalization efforts are intended to strengthen traceability and reduce irregular payments."
		},
		"recettes-provinciales": {
			name: "Provincial Revenue Directorates",
			sector: "Finances",
			summary: "Revenue bodies created by the provinces to mobilize income falling under their jurisdiction.",
			details: "Their names and organization vary from one province to another, but their mission remains the same: identifying taxpayers, ordering, collecting and auditing provincial taxes, duties and fees. In mining provinces, they play an important role in monitoring operators and the economic activities connected to mining."
		},
		"ministere-transports": {
			name: "Ministry of Transport, Communications and Regional Access",
			sector: "Transports",
			summary: "Ministry responsible for national transport policy and logistics corridors in the DRC.",
			details: "This ministry prepares the standards, strategies and programs related to road, rail, river, lake, maritime and air transport. In the mining sector, it is involved in organizing the corridors used to move minerals and equipment, and its action directly influences export costs and the competitiveness of Congolese mining products."
		},
		"ogefrem": {
			name: "Office for Multimodal Freight Management - OGEFREM",
			sector: "Transports",
			summary: "Public institution responsible for supervising and regulating Congolese freight.",
			details: "OGEFREM protects the interests of Congolese shippers, monitors cargo imported or exported through the different corridors serving the DRC, and manages freight monitoring documents. It collects and analyzes data on volumes, routes and logistics costs, making it a key actor in monitoring exported minerals and imported equipment."
		},
		"lmc": {
			name: "Congolese Maritime Lines - LMC",
			sector: "Transports",
			summary: "The DRC's national maritime shipping company, active in maritime and multimodal transport organization.",
			details: "LMC enables the DRC to exercise and benefit from its maritime traffic rights. It can transport goods through its own ships, chartered vessels or agreements with other carriers. Its role is strategic for a country that relies heavily on regional ports and corridors for foreign trade, including mining products."
		},
		"ministere-recherche": {
			name: "Ministry in charge of Scientific Research and Technological Innovation",
			sector: "Recherche",
			summary: "Ministry responsible for national policy on research, innovation and technological development.",
			details: "This ministry steers scientific programs toward the country's economic, industrial, environmental and social needs. It oversees public research centers, institutes and agencies, encourages researcher training, and supports institutions working on geology, metallurgy, the environment and energy in the mining field."
		},
		"cnpri": {
			name: "National Committee for Protection against Ionizing Radiation - CNPRI",
			sector: "Recherche",
			summary: "National regulatory authority in charge of nuclear safety, radiological security and radiation protection.",
			details: "CNPRI works to protect workers, patients, the public, property and the environment. It supervises activities that use, hold, transport, import or export radioactive sources. In the mining sector, its role is important whenever ores contain radioactive substances or when radiation-based equipment is used."
		},
		"cgea": {
			name: "General Commission for Atomic Energy - CGEA",
			sector: "Recherche",
			summary: "Congolese institution responsible for promoting and coordinating research related to the peaceful use of atomic energy.",
			details: "CGEA develops scientific and technological programs in fields such as physics, chemistry, agronomy, life sciences and applied techniques. In the mining sector, its methods can be used to characterize ores, study materials and detect certain elements."
		},
		"crgm": {
			name: "Geological and Mining Research Center - CRGM",
			sector: "Recherche",
			summary: "Public scientific and technical institution specialized in Earth sciences.",
			details: "CRGM designs and carries out projects aimed at improving knowledge of the DRC's soil and subsoil. It performs geological and mining prospecting, produces geological, ore, hydrogeological and geotechnical maps, and also studies geological risks and the environmental effects of mining activities."
		},
		"ministere-commerce-exterieur": {
			name: "Ministry of Foreign Trade",
			sector: "Commerce",
			summary: "Ministry responsible for national policy on imports, exports, transit and international trade relations.",
			details: "The Ministry of Foreign Trade develops policies intended to promote Congolese products on foreign markets, negotiates and follows trade agreements, and supervises or collaborates with institutions such as OCC. In the mining sector, it frames the commercial aspects of mineral and processed-product exports."
		},
		"occ": {
			name: "Congolese Control Office - OCC",
			sector: "Commerce",
			summary: "Public institution responsible for quality control, conformity verification and certain technical analyses.",
			details: "OCC inspects goods and products imported, exported or manufactured locally. Its missions cover quality, quantity, conformity, pricing and compliance with applicable standards. In the mining field, it may intervene in the control of certain products, equipment and operations linked to foreign trade."
		},
		"ministere-environnement": {
			name: "Ministry of Environment, Sustainable Development and the New Climate Economy",
			sector: "Environnement",
			summary: "Government body responsible for defining and implementing national environmental policy.",
			details: "This ministry works to protect ecosystems, biodiversity, forests, natural resources and the living environment of communities. In the mining sector, it is involved in preventing and managing air, water and soil pollution, and in monitoring the implementation of environmental conventions ratified by the DRC."
		},
		"ace": {
			name: "Congolese Environment Agency - ACE",
			sector: "Environnement",
			summary: "Public institution responsible for the environmental and social assessment of development projects in the DRC.",
			details: "ACE reviews environmental and social impact studies, verifies reports submitted by project developers and accredited consulting firms, issues compliance opinions and may request corrective or mitigation measures. In the mining sector, it assesses risks linked to discharges, waste, water use, population displacement and mine closure."
		},
		"egc": {
			name: "General Cobalt Company - EGC",
			sector: "Autres",
			summary: "Gécamines subsidiary responsible for supervising the purchase, processing and marketing of artisanal cobalt.",
			details: "EGC was created to integrate artisanal cobalt production into an official, controlled and transparent supply chain. It works with cooperatives, public services, buyers and technical partners, and aims to reduce child labor, human rights violations, clandestine circuits and losses of public revenue."
		},
		"itie-rdc": {
			name: "Extractive Industries Transparency Initiative - EITI-DRC",
			sector: "Autres",
			summary: "National mechanism responsible for implementing the Extractive Industries Transparency Initiative.",
			details: "EITI-DRC brings together representatives of the State, extractive companies and civil society to promote transparency and sound governance of mining, oil and gas revenues. It collects, analyzes and publishes data on production, exports, company payments and state revenues, thereby informing public debate and supporting reform."
		}
	};

	var strings = {
		"language.current": { en: "Current language" },
		"language.menu": { en: "Language selection" },
		"cart.empty": { en: "Your cart is empty." },
		"cart.subtotal": { en: "Subtotal:" },
		"cart.view": { en: "View cart" },
		"cart.checkout": { en: "Checkout" },
		"cart.product": { en: "RARSM product" },
		"cart.remove": { en: "Remove this item" },
		"user.options": { en: "Account options" },
		"user.account": { en: "Account" },
		"user.logout": { en: "Sign out" },
		"user.name": { en: "User" },
		"auth.login.title": { en: "Log in" },
		"auth.login.submit": { en: "Log in" },
		"auth.login.switch": { en: "Not a member yet? Sign up" },
		"auth.register.title": { en: "Sign up" },
		"auth.register.submit": { en: "Create account" },
		"auth.cancel": { en: "Cancel" },
		"auth.terms": { en: "I accept the terms of use" },
		"auth.login.placeholder": { en: "Email or username" },
		"auth.password.placeholder": { en: "Password" },
		"auth.register.name": { en: "Name or username" },
		"auth.register.email": { en: "Email" },
		"auth.register.passwordConfirm": { en: "Confirm password" }
	};

	var rules = {
		common: [
			{ selector: "#popupLogin h4", text: "Log in" },
			{ selector: "#popupLogin .registerRedirect", text: "Not a member yet? Sign up" },
			{ selector: "#popupLogin .modal-form-actions .btn-outline-maincolor", text: "Cancel" },
			{ selector: "#popupLogin .modal-form-actions .btn-maincolor", text: "Log in" },
			{ selector: "#popupRegistr h4", text: "Sign up" },
			{ selector: "#popupRegistr .modal-form-actions .btn-outline-maincolor", text: "Cancel" },
			{ selector: "#popupRegistr .modal-form-actions .btn-maincolor", text: "Create account" },
			{ selector: "#popupRegistr label[for='popupRegistrTerms']", text: "I accept the terms of use" },
			{ selector: ".top-nav .sf-menu > li:nth-child(1) > a", text: "Home" },
			{ selector: ".top-nav .sf-menu > li:nth-child(2) > a", text: "Book" },
			{ selector: ".top-nav .sf-menu > li:nth-child(3) > a", text: "Author" },
			{ selector: ".top-nav .sf-menu > li:nth-child(4) > a", text: "Shop" },
			{ selector: ".top-nav .sf-menu > li:nth-child(5) > a", text: "Institutions" },
			{ selector: ".top-nav .sf-menu > li:nth-child(6) > a", text: "Activities" },
			{ selector: ".top-nav .sf-menu > li:nth-child(7) > a", text: "Contact" },
			{ selector: ".menu-auth-login > a[data-toggle][href='#popupLogin']", text: "Log in" },
			{ selector: ".menu-auth-register > a[data-toggle][href='#popupRegistr']", text: "Sign up" },
			{ selector: ".header-utilities > a.btn-outline-maincolor[data-toggle][href='#popupLogin']", text: "Log in" },
			{ selector: ".header-utilities > a.btn-maincolor[data-toggle][href='#popupRegistr']", text: "Sign up" },
			{ selector: ".page_footer .menu-item:eq(0) a", text: "Home" },
			{ selector: ".page_footer .menu-item:eq(1) a", text: "Book" },
			{ selector: ".page_footer .menu-item:eq(2) a", text: "Author" },
			{ selector: ".page_footer .menu-item:eq(3) a", text: "Shop" },
			{ selector: ".page_footer .menu-item:eq(4) a", text: "Institutions" },
			{ selector: ".page_footer .menu-item:eq(5) a", text: "Activities" },
			{ selector: ".page_footer .menu-item:eq(6) a", text: "Contact" },
			{ selector: ".page_footer .menu-item:eq(7) a", text: "FAQ" },
			{ selector: ".page_copyright p", html: "&copy; <span class='copyright_year'>2026</span> RARSM - Mining Sector Regulatory Compendium. All rights reserved." },
			{ selector: ".breadcrumb-item a[href='index.html']", text: "Home" },
			{ selector: ".breadcrumb-item a[href='book.html']", text: "Book" },
			{ selector: ".breadcrumb-item a[href='author.html']", text: "Author" },
			{ selector: ".breadcrumb-item a[href='pricing.html']", text: "Shop" },
			{ selector: ".breadcrumb-item a[href='institutions.php']", text: "Institutions" },
			{ selector: ".breadcrumb-item a[href='activites.html']", text: "Activities" },
			{ selector: ".breadcrumb-item a[href='contact.html']", text: "Contact" },
			{ selector: ".woocommerce-mini-cart__total strong", text: "Subtotal:" },
			{ selector: ".woocommerce-mini-cart__buttons .button:eq(0)", text: "View cart" },
			{ selector: ".woocommerce-mini-cart__buttons .button:eq(1)", text: "Checkout" },
			{ selector: ".rarsm-user-menu-action:not(.rarsm-user-menu-action-danger) span:last-child", text: "Account" },
			{ selector: ".rarsm-user-menu-action-danger span:last-child", text: "Sign out" }
		],
		"index.html": [
			{ selector: ".rarsm-mobile-buy-btn", text: "Shop" },
			{ selector: ".hero-desktop .intro_layer.intro_feature_word p", text: "The legal reference for the mining sector" },
			{ selector: ".hero-desktop .intro_layers .intro_layer:eq(2) p", text: "At the heart of the Democratic Republic of the Congo's mining wealth lies the law. Mastering the legal references that govern the Congolese mining sector means understanding the foundations of its governance, regulation and institutional organization. RARSM brings together, in one single compendium, the regulatory acts that shape the sector and makes them easier to read, understand and apply." },
			{ selector: ".hero-desktop .several-buttons .btn-maincolor", text: "Buy the book" },
			{ selector: ".hero-desktop .several-buttons .photoswipe-link-button span", text: "Discover the book" },
			{ selector: "#author p.mb-2", text: "Collection author" },
			{ selector: "#author p.excerpt:eq(0)", text: "Lawyer, public governance expert and mining sector specialist, currently Legal Director at the Center for Expertise, Evaluation and Certification of Precious and Semi-Precious Mineral Substances (CEEC)." },
			{ selector: "#author p.excerpt:eq(1)", text: "A committed advocate against mining fraud and a recognized expert with more than two decades of experience in mining law and governance in the DRC, he delivers a major work of legal structure and analysis through RARSM." },
			{ selector: "#author .btn-darkgrey", text: "Read the biography" },
			{ selector: "#about .row.align-center:eq(0) h3 span", text: "About RARSM" },
			{ selector: "#about .row.align-center:eq(0) .col-lg-6:last-child p:eq(0)", text: "Today, the mining sector is undeniably a major lever for economic growth in the DRC." },
			{ selector: "#about .row.align-center:eq(0) .col-lg-6:last-child p:eq(1)", text: "In a field shaped by hundreds of legal instruments and marked by fragmented texts, RARSM stands out as an essential legal reference by bringing together the regulatory acts that govern the mining sector in the DRC." },
			{ selector: "#about .row.align-center:eq(1) h3 span", text: "Why is this compendium essential?" },
			{ selector: "#about .row.align-center:eq(1) .col-lg-6.order-2 p:eq(0)", text: "RARSM is essential because it answers a practical, legal and institutional need." },
			{ selector: "#about .row.align-center:eq(1) .col-lg-6.order-2 p:eq(1)", text: "In a strategic and complex sector such as mining, the number of applicable regulations often makes access difficult for practitioners, researchers, investors and public authorities." },
			{ selector: "#about .row.align-center:eq(1) .col-lg-6.order-2 p:eq(2)", text: "This compendium centralizes, organizes and makes available the legal acts that govern the mining sector. It improves the readability of standards, strengthens legal certainty and supports more effective application." },
			{ selector: "#about .row.align-center:eq(1) .col-lg-6.order-2 p:eq(3)", text: "It also helps prevent interpretive errors, promotes transparency in mining governance and supports compliance with the legal and regulatory requirements in force." },
			{ selector: "#about .row.align-center:eq(1) .col-lg-6.order-2 p:eq(4)", text: "Centralization of mining regulations" },
			{ selector: "#about .row.align-center:eq(1) .col-lg-6.order-2 p:eq(5)", text: "Stronger legal certainty" },
			{ selector: "#about .row.align-center:eq(1) .col-lg-6.order-2 p:eq(6)", text: "Prevention of interpretation errors" },
			{ selector: "#about .row.align-center:eq(1) .col-lg-6.order-2 p:eq(7)", text: "Greater transparency and compliance" },
			{ selector: "#about .row.align-center:eq(2) h3 span", text: "Transparency, traceability and certification" },
			{ selector: "#about .row.align-center:eq(2) .col-lg-6:last-child p:eq(0)", text: "They guarantee not only that mining activities comply with legal requirements, but also that mineral substances remain credible on national and international markets." },
			{ selector: "#about .row.align-center:eq(2) .col-lg-6:last-child p:eq(1)", text: "Transparency improves the visibility of mining operations, traceability follows mineral substances from extraction to marketing, and certification validates compliance with the standards that govern the sector." },
			{ selector: "#about .row.align-center:eq(2) .col-lg-6:last-child p:eq(2)", text: "- Better visibility on mining operations" },
			{ selector: "#about .row.align-center:eq(2) .col-lg-6:last-child p:eq(3)", text: "- Tracking the origin and route of mineral substances" },
			{ selector: "#about .row.align-center:eq(2) .col-lg-6:last-child p:eq(4)", text: "- Validation of compliance with certification standards" },
			{ selector: "#about .row.align-center:eq(2) .col-lg-6:last-child p:eq(5)", text: "- Action against mining fraud and illicit circuits" },
			{ selector: "#quotes p.mb-2", text: "Who is it for?" },
			{ selector: "#quotes h2 span", text: "Who is RARSM designed for?" },
			{ selector: "#quotes .quote-item:eq(0) h4", text: "Lawyers and legal practitioners" },
			{ selector: "#quotes .quote-item:eq(0) .position", text: "Legal reference" },
			{ selector: "#quotes .quote-item:eq(0) .item-content p:last", text: "Lawyers, magistrates, lecturers and students can rely on it to study, interpret and use Congolese mining law with confidence." },
			{ selector: "#quotes .quote-item:eq(1) h4", text: "Public administrations" },
			{ selector: "#quotes .quote-item:eq(1) .position", text: "Institutional support" },
			{ selector: "#quotes .quote-item:eq(1) .item-content p:last", text: "Public bodies and technical services find a clear reference to apply the rules and align their administrative practices." },
			{ selector: "#quotes .quote-item:eq(2) h4", text: "Mining companies" },
			{ selector: "#quotes .quote-item:eq(2) .position", text: "Operational compliance" },
			{ selector: "#quotes .quote-item:eq(2) .item-content p:last", text: "Operators can better structure their activities and monitor their regulatory obligations with a practical working reference." },
			{ selector: "#quotes .quote-item:eq(3) h4", text: "Investors and partners" },
			{ selector: "#quotes .quote-item:eq(3) .position", text: "Strategic visibility" },
			{ selector: "#quotes .quote-item:eq(3) .item-content p:last", text: "Partners gain a clearer understanding of the mining framework so they can assess risk and guide their decisions." },
			{ selector: "#faqs .col-12 h2 span", text: "Overall structure of the book" },
			{ selector: "#faqs .col-12 .excerpt", text: "The compendium is organized into three major parts covering the institutional framework, the monitoring of mining activities, and the mechanisms of transparency, traceability and certification." },
			{ selector: "#collapse01_header a", text: "Part 1: Institutional framework and supervision of activities" },
			{ selector: "#collapse01 .card-body", html: "<h6>Title 1: Administration and technical services</h6><ul class='list-unstyled'><li>Chapter 1: Administration and technical services of the Ministry of Mines</li><li>Chapter 2: Cooperation mechanisms</li><li>Chapter 3: Auxiliary actors</li></ul><h6>Title 2: Supervision of mining activities</h6><ul class='list-unstyled'><li>Chapter 1: Regulation of industrial mining</li><li>Chapter 2: Regulation of artisanal mining</li></ul>" },
			{ selector: "#collapse02_header a", text: "Part 2: Monitoring mining activities" },
			{ selector: "#collapse02 .card-body", html: "<h6>Title 1: Monitoring right holders and licensed operators</h6><ul class='list-unstyled'><li>Chapter 1: Holders of mining rights</li><li>Chapter 2: Holders of quarry rights</li><li>Chapter 3: Processing entities approved by the State</li></ul><h6>Title 2: Monitoring artisanal mining operators</h6><ul class='list-unstyled'><li>Chapter 1: Mining cooperatives</li><li>Chapter 2: Traders, counters, exchanges and trading centers</li><li>Chapter 3: Taxation</li></ul><h6>Titles 3 and 4: Auxiliary actors and exports</h6><ul class='list-unstyled'><li>Monitoring laboratories and other auxiliary actors</li><li>Export mechanisms and procedures</li></ul>" },
			{ selector: "#collapse07_header a", text: "Part 3: Transparency, traceability and certification" },
			{ selector: "#collapse08_header a", text: "Educational purpose of the book" },
			{ selector: "#collapse08 .card-body p", text: "RARSM was designed to gather, structure and make accessible, in a single document, the main regulatory texts that govern mining activities, in order to strengthen legal certainty and promote more effective mining governance." },
			{ selector: "#chapter h3 span", text: "Introduction and context" },
			{ selector: "#chapter .scroll-block p:eq(0)", text: "The mining sector occupies a strategic place in the DRC's economy because of the abundance and diversity of its natural resources. It is a major driver of development, public revenue mobilization and investment." },
			{ selector: "#chapter .scroll-block p:eq(1)", text: "However, the constant evolution of the legal framework has produced a large number of regulatory acts that are often fragmented and difficult to access for many stakeholders." },
			{ selector: "#chapter .scroll-block p:eq(2)", text: "RARSM was created in this context to gather, structure and make accessible the main regulatory texts that govern mining activities." },
			{ selector: "#chapter .scroll-block p:eq(3)", text: "The book answers a real need for centralized legal information, stronger legal certainty and a more effective and transparent mining governance framework." },
			{ selector: "#valeur-ajoutee h3 span", text: "Concrete added value for the DRC and investors" },
			{ selector: "#valeur-ajoutee .excerpt", text: "RARSM delivers tangible value both to the DRC and to national and international investors." },
			{ selector: "#valeur-ajoutee p:eq(1)", text: "For the DRC, the compendium supports stronger mining governance by making legal standards easier to access, improving regulatory application and promoting transparency in the management of natural resources." },
			{ selector: "#valeur-ajoutee p:eq(2)", text: "For investors, RARSM serves as a reference manual for understanding the normative framework of the Congolese mining sector and making better-informed strategic decisions." },
			{ selector: "#valeur-ajoutee .btn-darkgrey", text: "Buy the book" },
			{ selector: "#valeur-ajoutee .btn-outline-darkgrey", text: "Read an excerpt" },
			{ selector: "#soon .rarsm-counter-kicker", text: "Why choose RARSM?" },
			{ selector: "#soon h2 span", text: "A choice for clarity, legal security and efficiency" },
			{ selector: "#soon .rarsm-counter-intro", text: "Choosing RARSM means meeting a threefold need: easier access to texts, safer interpretation and more effective application of mining law in the DRC." },
			{ selector: "#soon .rarsm-counter-card:eq(0) .rarsm-counter-label", text: "Better access to regulations" },
			{ selector: "#soon .rarsm-counter-card:eq(1) .rarsm-counter-label", text: "Safer legal interpretation" },
			{ selector: "#soon .rarsm-counter-card:eq(2) .rarsm-counter-label", text: "More effective application of mining law" },
			{ selector: "#acheter .row.text-center .mb-2", text: "Ordering and acquisition" },
			{ selector: "#acheter .row.text-center h2 span", text: "Buy the RARSM book" },
			{ selector: "#acheter .row.text-center .excerpt", text: "Choose the format that fits your needs: print edition, digital version or institutional order." },
			{ selector: "#acheter .pricing-plan:eq(0) .plan-name h3", text: "Print edition" },
			{ selector: "#acheter .pricing-plan:eq(0) .plan-description", text: "Order the printed volume for your professional library, office, company or institution." },
			{ selector: "#acheter .pricing-plan:eq(0) .plan-features li:eq(0)", text: "Physical format" },
			{ selector: "#acheter .pricing-plan:eq(0) .plan-features li:eq(1)", text: "Professional use" },
			{ selector: "#acheter .pricing-plan:eq(0) .plan-features li:eq(2)", text: "Delivery on request" },
			{ selector: "#acheter .pricing-plan:eq(0) .plan-button .btn", text: "Order" },
			{ selector: "#acheter .pricing-plan:eq(1) .plan-name h3", text: "Digital version" },
			{ selector: "#acheter .pricing-plan:eq(1) .plan-description", text: "Request the PDF or eBook format for fast consultation on desktop, tablet or mobile." },
			{ selector: "#acheter .pricing-plan:eq(1) .plan-features li:eq(0)", text: "Easy access" },
			{ selector: "#acheter .pricing-plan:eq(1) .plan-features li:eq(1)", text: "Mobile reading" },
			{ selector: "#acheter .pricing-plan:eq(1) .plan-features li:eq(2)", text: "Delivery by email" },
			{ selector: "#acheter .pricing-plan:eq(1) .plan-button .btn", text: "Request the format" },
			{ selector: "#acheter .pricing-plan:eq(2) .plan-name h3", text: "Institutional order" },
			{ selector: "#acheter .pricing-plan:eq(2) .plan-description", text: "For administrations, firms, companies, universities or partners wishing to purchase several copies." },
			{ selector: "#acheter .pricing-plan:eq(2) .plan-features li:eq(0)", text: "Bulk request" },
			{ selector: "#acheter .pricing-plan:eq(2) .plan-features li:eq(1)", text: "Dedicated presentation" },
			{ selector: "#acheter .pricing-plan:eq(2) .plan-features li:eq(2)", text: "Tailored offer" },
			{ selector: "#acheter .pricing-plan:eq(2) .plan-button .btn", text: "Request an offer" },
			{ selector: "#blog .rarsm-axes-heading .mb-2", text: "Major themes of the compendium" },
			{ selector: "#blog .rarsm-axes-heading h2 span", text: "Three sections to understand, apply and secure mining law" },
			{ selector: "#blog .rarsm-axes-intro .mb-0", text: "The compendium follows a clear progression: identifying institutions and supervisory frameworks, monitoring mining activities on a daily basis, and mastering the mechanisms of transparency, traceability and certification." },
			{ selector: "#blog .rarsm-axis-card:eq(0) .rarsm-axis-card__tag", text: "Part 1" },
			{ selector: "#blog .rarsm-axis-card:eq(0) h3 a", text: "Institutional framework and supervision of mining activities" },
			{ selector: "#blog .rarsm-axis-card:eq(0) p", text: "A structured view of mining administrations, technical services and the frameworks that govern industrial and artisanal operations." },
			{ selector: "#blog .rarsm-axis-card:eq(0) .rarsm-axis-card__points li:eq(0)", text: "Administration and technical services of the sector" },
			{ selector: "#blog .rarsm-axis-card:eq(0) .rarsm-axis-card__points li:eq(1)", text: "Supervision of industrial and artisanal activities" },
			{ selector: "#blog .rarsm-axis-card:eq(1) .rarsm-axis-card__tag", text: "Part 2" },
			{ selector: "#blog .rarsm-axis-card:eq(1) h3 a", text: "Monitoring mining activities" },
			{ selector: "#blog .rarsm-axis-card:eq(1) p", text: "This section gathers the monitoring mechanisms that apply to right holders, artisanal sector actors and the main operational flows." },
			{ selector: "#blog .rarsm-axis-card:eq(1) .rarsm-axis-card__points li:eq(0)", text: "Monitoring mining rights, quarries and licenses" },
			{ selector: "#blog .rarsm-axis-card:eq(1) .rarsm-axis-card__points li:eq(1)", text: "Cooperatives, trading, exports and taxation" },
			{ selector: "#blog .rarsm-axis-card:eq(2) .rarsm-axis-card__tag", text: "Part 3" },
			{ selector: "#blog .rarsm-axis-card:eq(2) h3 a", text: "Transparency, traceability and certification" },
			{ selector: "#blog .rarsm-axis-card:eq(2) p", text: "The final section highlights the standards and initiatives that strengthen the credibility, compliance and governance of mineral substances." },
			{ selector: "#blog .rarsm-axis-card:eq(2) .rarsm-axis-card__points li:eq(0)", text: "EITI, Kimberley Process, ICGLR and OECD" },
			{ selector: "#blog .rarsm-axis-card:eq(2) .rarsm-axis-card__points li:eq(1)", text: "Certification, due diligence and responsible traceability" },
			{ selector: "#contact h2 span", text: "Contact us" },
			{ selector: "#contact .col-lg-4 > p", text: "Use this form for any request related to information, acquisition, presentation or partnership around the RARSM compendium." },
			{ selector: "#contact .icon-inline:eq(0) p", text: "Mining operators, lawyers, investors and public institutions" },
			{ selector: "#contact .icon-inline:eq(1) p", text: "Book presentation, information request or partnership" },
			{ selector: "#contact .icon-inline:eq(2) p", text: "Coverage: governance, activity monitoring, exports and certification" },
			{ selector: "#contact button[name='contact_submit']", text: "Send request" }
		],
		"book.html": [
			{ selector: ".page_title .small-title", text: "The Book" },
			{ selector: ".page_title .breadcrumb-item.active", text: "The Book" },
			{ selector: ".book-overview-kicker", text: "RARSM" },
			{ selector: ".book-overview-section h2 span", text: "Summary, target audience and structure of the compendium" },
			{ selector: ".book-overview-lead", text: "RARSM centralizes, organizes and makes more accessible the regulatory framework that shapes mining activities in the Democratic Republic of the Congo." },
			{ selector: ".book-overview-copy .book-overview-intro", text: "Designed as a working tool, RARSM brings together texts that are often scattered, helping readers improve the readability of mining law, legal certainty and decision-making quality." },
			{ selector: ".book-overview-copy p:eq(1)", text: "It supports practitioners, institutions and investors looking for a clear reference to understand the rules, procedures and obligations that govern the Congolese mining sector." },
			{ selector: ".book-overview-benefit:eq(0) span:last-child", text: "Centralization of relevant regulations" },
			{ selector: ".book-overview-benefit:eq(1) span:last-child", text: "Clearer reading of the applicable legal framework" },
			{ selector: ".book-overview-benefit:eq(2) span:last-child", text: "Useful support for compliance and governance" },
			{ selector: ".book-overview-benefit:eq(3) span:last-child", text: "Time saved in legal and documentary research" },
			{ selector: ".book-overview-card:eq(0) .book-overview-card__eyebrow", text: "Who is it for?" },
			{ selector: ".book-overview-card:eq(0) h5", text: "Target audience" },
			{ selector: ".book-overview-card:eq(0) li:eq(0)", text: "Mining operators and investors" },
			{ selector: ".book-overview-card:eq(0) li:eq(1)", text: "Lawyers, attorneys and advisory firms" },
			{ selector: ".book-overview-card:eq(0) li:eq(2)", text: "Administrations and technical services" },
			{ selector: ".book-overview-card:eq(0) li:eq(3)", text: "Universities, researchers and students" },
			{ selector: ".book-overview-card:eq(1) .book-overview-card__eyebrow", text: "What it brings" },
			{ selector: ".book-overview-card:eq(1) h5", text: "Benefits of the compendium" },
			{ selector: ".book-overview-card:eq(1) li:eq(0)", text: "Better command of legal obligations" },
			{ selector: ".book-overview-card:eq(1) li:eq(1)", text: "Cross-cutting view of control mechanisms" },
			{ selector: ".book-overview-card:eq(1) li:eq(2)", text: "Support for transparency and traceability" },
			{ selector: ".book-overview-card:eq(1) li:eq(3)", text: "A practical reference for public and private action" },
			{ selector: "#faqs .col-12 h2 span", text: "Overall structure of the book" },
			{ selector: "#faqs .col-12 .excerpt", text: "The compendium is pedagogically organized around three major parts: the institutional framework, the monitoring of mining activities, and the mechanisms of transparency, traceability and certification." },
			{ selector: "#collapse01_header a", text: "Part 1: Institutional framework and supervision of activities" },
			{ selector: "#collapse02_header a", text: "Part 2: Monitoring mining activities" },
			{ selector: "#collapse07_header a", text: "Part 3: Transparency, traceability and certification" },
			{ selector: "#collapse08_header a", text: "Educational purpose of the book" },
			{ selector: "#formats .mb-2", text: "Formats and acquisition" },
			{ selector: "#formats h2 span", text: "Choose the option that fits your needs" },
			{ selector: "#formats .excerpt", text: "This page gathers the available formats, payment channels, information required to place an order and the delivery terms for the book." },
			{ selector: "#formats .rarsm-pricing-flow-note p", text: "Choose a format, add the product to your cart and proceed to payment." },
			{ selector: "#formats .pricing-plan:eq(0) .plan-name h3", text: "Print edition" },
			{ selector: "#formats .pricing-plan:eq(0) .plan-description", text: "Recommended for lawyers, administrations, firms, libraries and readers who need a reliable physical reference." },
			{ selector: "#formats .pricing-plan:eq(0) .plan-features li:eq(0)", text: "Single-copy or bulk orders" },
			{ selector: "#formats .pricing-plan:eq(0) .plan-features li:eq(1)", text: "Availability confirmed before payment" },
			{ selector: "#formats .pricing-plan:eq(0) .plan-features li:eq(2)", text: "Local pickup or shipping depending on destination" },
			{ selector: "#formats .pricing-plan:eq(0) button", text: "Add to cart" },
			{ selector: "#formats .pricing-plan:eq(1) .plan-name h3", text: "Digital version" },
			{ selector: "#formats .pricing-plan:eq(1) .plan-description", text: "Designed for quick consultation on desktop, tablet or smartphone, with digital delivery after validation." },
			{ selector: "#formats .pricing-plan:eq(1) .plan-features li:eq(0)", text: "Sent by email after confirmation" },
			{ selector: "#formats .pricing-plan:eq(1) .plan-features li:eq(1)", text: "Easy access for personal or professional reading" },
			{ selector: "#formats .pricing-plan:eq(1) .plan-features li:eq(2)", text: "Edition and availability confirmed on order" },
			{ selector: "#formats .pricing-plan:eq(1) button", text: "Add to cart" },
			{ selector: "#formats .pricing-plan:eq(2) .plan-name h3", text: "Institutional order" },
			{ selector: "#formats .pricing-plan:eq(2) .plan-price", text: "Quotation" },
			{ selector: "#formats .pricing-plan:eq(2) .plan-decimals", text: "tailored" },
			{ selector: "#formats .pricing-plan:eq(2) .plan-description", text: "For institutions, companies, universities or libraries seeking several copies or specific administrative treatment." },
			{ selector: "#formats .pricing-plan:eq(2) .plan-features li:eq(0)", text: "Tailored quotation" },
			{ selector: "#formats .pricing-plan:eq(2) .plan-features li:eq(1)", text: "Dedicated follow-up" },
			{ selector: "#formats .pricing-plan:eq(2) .plan-features li:eq(2)", text: "Public or private procurement support" },
			{ selector: "#formats .pricing-plan:eq(2) button", text: "Request a quote" }
		],
		"author.html": [
			{ selector: ".page_title .small-title", text: "About the author" },
			{ selector: ".page_title .breadcrumb-item.active", text: "Author" },
			{ selector: ".author-hero-copy-simple p.mb-2", text: "Author of the compendium" },
			{ selector: ".author-hero-copy-simple .excerpt:eq(0)", text: "Mining sector lawyer, public governance expert and current Legal Director in charge of fighting mining fraud at CEEC." },
			{ selector: ".author-hero-copy-simple .excerpt:eq(1)", text: "With more than twenty years of experience in law, public governance and the mining sector in the DRC, he brings to RARSM a rigorous work of structure, clarification and legal accessibility." },
			{ selector: ".author-story-card .author-section-kicker", text: "Career path" },
			{ selector: ".author-story-card h3", text: "A legal practice built in the field" },
			{ selector: ".author-story-card > p:eq(0)", text: "Born on November 25, 1968, Maître Jean-Baptiste Otshudi Disashi Kalonda has built a career shaped by legal practice, public governance and the regulation of the extractive sector. His experience has grown through administrative realities, litigation and control mechanisms applied to natural resources." },
			{ selector: ".author-story-card > p:eq(1)", text: "Through RARSM, he puts that experience at the service of a clear ambition: making mining regulations more readable, more accessible and easier to use for the State, investors, companies and operators." },
			{ selector: ".author-inline-quote p", text: "The author's work does not stop at gathering texts. It turns regulation into a tool for interpretation, decision-making and the security of practices." },
			{ selector: ".author-expertise-card .author-section-kicker", text: "Areas of expertise" },
			{ selector: ".author-expertise-list li:eq(0) strong", text: "Mining law and litigation" },
			{ selector: ".author-expertise-list li:eq(0) p", text: "Interpretation, application and defense of the texts that govern mining activities in the DRC." },
			{ selector: ".author-expertise-list li:eq(1) strong", text: "Public governance" },
			{ selector: ".author-expertise-list li:eq(1) p", text: "Institutional reading of public services, control bodies and decision-making circuits." },
			{ selector: ".author-expertise-list li:eq(2) strong", text: "Action against mining fraud" },
			{ selector: ".author-expertise-list li:eq(2) p", text: "Compliance, traceability and control of the risks linked to operations and mineral flows." },
			{ selector: ".author-expertise-list li:eq(3) strong", text: "Certification and regulation" },
			{ selector: ".author-expertise-list li:eq(3) p", text: "Supervision of transparency, certification and legal security mechanisms." },
			{ selector: ".author-vision-section .author-section-kicker", text: "Contribution to RARSM" },
			{ selector: ".author-vision-section h3 span", text: "Turning regulation into a decision-making tool" },
			{ selector: ".author-vision-section p:eq(0)", text: "The compendium does more than compile texts. It proposes a logical organization of the institutional framework, the monitoring of activities, taxation, exports and certification mechanisms in order to support fairer and safer decisions." },
			{ selector: ".author-vision-section p:eq(1)", text: "This vision makes RARSM a reference designed to support both immediate compliance for sector actors and the long-term construction of a transparent, fair and well-governed mining sector." },
			{ selector: ".author-vision-point:eq(0) strong", text: "Readability" },
			{ selector: ".author-vision-point:eq(0) span", text: "A clearer reading of the applicable acts and procedures." },
			{ selector: ".author-vision-point:eq(1) strong", text: "Legal certainty" },
			{ selector: ".author-vision-point:eq(1) span", text: "Practical support to reduce interpretation and implementation errors." },
			{ selector: ".author-vision-point:eq(2) strong", text: "Practical value" },
			{ selector: ".author-vision-point:eq(2) span", text: "A resource that can be used directly by companies, lawyers and institutions." },
			{ selector: ".author-cta-section .author-section-kicker", text: "Go further" },
			{ selector: ".author-cta-section h3", text: "Discover the book behind this expertise" },
			{ selector: ".author-cta-section p", text: "RARSM extends this field experience through a structured reading of mining law designed for institutions, practitioners, companies and investors." },
			{ selector: ".author-cta-actions .btn-outline-maincolor", text: "View the book" },
			{ selector: ".author-cta-actions .btn-maincolor", text: "Order" }
		],
		"pricing.html": [
			{ selector: ".page_title .small-title", text: "RARSM Shop" },
			{ selector: ".page_title .breadcrumb-item.active", text: "Shop" },
			{ selector: "#formats .product_title", text: "RARSM - Print edition" },
			{ selector: "#formats .woocommerce-review-link", text: "(reference work)" },
			{ selector: "#formats .summary > div p", text: "A practical legal reference for the mining sector in the DRC." },
			{ selector: "#formats .variations label[for='rarsm_order_profile']", text: "Order profile" },
			{ selector: "#formats #rarsm_order_profile option:eq(0)", text: "Individual" },
			{ selector: "#formats #rarsm_order_profile option:eq(1)", text: "Firm / company" },
			{ selector: "#formats #rarsm_order_profile option:eq(2)", text: "Institution" },
			{ selector: "#formats .variations label[for='rarsm_delivery_mode']", text: "Delivery method" },
			{ selector: "#formats #rarsm_delivery_mode option:eq(0)", text: "Pickup in Kinshasa" },
			{ selector: "#formats #rarsm_delivery_mode option:eq(1)", text: "Local delivery" },
			{ selector: "#formats #rarsm_delivery_mode option:eq(2)", text: "Shipping depending on destination" },
			{ selector: "#formats .quantity input", attr: { title: "Quantity" } },
			{ selector: "#formats .single_add_to_cart_button", text: "Add to cart" },
			{ selector: "#formats .rarsm-shop-meta .sku_wrapper", html: "Reference: <span class='sku'>RARSM-PRINT</span>" },
			{ selector: "#formats .rarsm-shop-meta .posted_in", html: "Categories: <a href='book.html'>Mining law</a>, <a href='institutions.php'>Institutional use</a>" },
			{ selector: "#formats .rarsm-shop-meta .tagged_as", text: "Audience: lawyers, public administrations, investors, mining operators" },
			{ selector: "#tab-title-description a", text: "Description" },
			{ selector: "#tab-title-additional_information a", text: "Practical information" },
			{ selector: "#tab-title-usage a", text: "Recommended uses" },
			{ selector: "#tab-description .excerpt", text: "RARSM is designed as a working, reference and legal-security tool for organizations active in the Congolese mining environment." },
			{ selector: "#tab-description p:eq(1)", text: "By bringing together scattered texts, it reduces omissions, misinterpretation and time lost in legal research. Its centralized approach improves clarity, rigor and efficiency in decision-making." },
			{ selector: "#tab-description p:eq(2)", text: "This edition suits both individual readers and the needs of libraries, law firms, legal departments, public administrations, companies and technical partners." },
			{ selector: "#tab-additional_information h2", text: "Practical information" },
			{ selector: "#tab-additional_information tr:eq(0) th", text: "Author" },
			{ selector: "#tab-additional_information tr:eq(0) td p", text: "RARSM editorial team" },
			{ selector: "#tab-additional_information tr:eq(1) th", text: "Coverage" },
			{ selector: "#tab-additional_information tr:eq(1) td p", text: "DRC - mining sector" },
			{ selector: "#tab-additional_information tr:eq(2) th", text: "Format" },
			{ selector: "#tab-additional_information tr:eq(2) td p", text: "Print edition" },
			{ selector: "#tab-additional_information tr:eq(3) th", text: "Availability" },
			{ selector: "#tab-additional_information tr:eq(3) td p", text: "Subject to stock confirmation" },
			{ selector: "#tab-additional_information tr:eq(4) th", text: "Delivery" },
			{ selector: "#tab-additional_information tr:eq(4) td p", text: "Local pickup or shipping depending on destination" },
			{ selector: "#tab-additional_information tr:eq(5) th", text: "Order options" },
			{ selector: "#tab-additional_information tr:eq(5) td p", text: "Single-copy, multiple-copy or institutional order" },
			{ selector: "#tab-usage .rarsm-shop-note p", text: "The compendium is especially recommended for:" },
			{ selector: "#tab-usage .rarsm-shop-checklist li:eq(0)", text: "preparing legal opinions, compliance notes and consultations" },
			{ selector: "#tab-usage .rarsm-shop-checklist li:eq(1)", text: "updating documentation in institutions and specialized libraries" },
			{ selector: "#tab-usage .rarsm-shop-checklist li:eq(2)", text: "presentations, training sessions and exchanges on mining governance" },
			{ selector: "#formats .rarsm-shop-related-head h2", text: "More shop items" },
			{ selector: "#formats .rarsm-shop-related-intro", text: "Browse the other formats and branded items in a clear, easy-to-order carousel." },
			{ selector: "#formats .rarsm-shop-related-carousel .product:eq(0) .woocommerce-loop-product__title", text: "RARSM - Digital version" },
			{ selector: "#formats .rarsm-shop-related-carousel .product:eq(0) p", text: "A convenient format for desktop, tablet and smartphone." },
			{ selector: "#formats .rarsm-shop-related-carousel .product:eq(0) button", text: "Add to cart" },
			{ selector: "#formats .rarsm-shop-related-carousel .product:eq(1) .woocommerce-loop-product__title", text: "Institutional order" },
			{ selector: "#formats .rarsm-shop-related-carousel .product:eq(1) p", text: "For administrations, firms, companies or libraries seeking several copies." },
			{ selector: "#formats .rarsm-shop-related-carousel .product:eq(1) .woocommerce-Price-amount", text: "Quote on request" },
			{ selector: "#formats .rarsm-shop-related-carousel .product:eq(1) button", text: "Request a quote" },
			{ selector: "#formats .rarsm-shop-related-carousel .product:eq(2) .woocommerce-loop-product__title", text: "RARSM T-shirt" },
			{ selector: "#formats .rarsm-shop-related-carousel .product:eq(2) p", text: "A textile item for public events and project visibility." },
			{ selector: "#formats .rarsm-shop-related-carousel .product:eq(2) button", text: "Add to cart" },
			{ selector: "#formats .rarsm-shop-related-carousel .product:eq(3) .woocommerce-loop-product__title", text: "RARSM Cap" },
			{ selector: "#formats .rarsm-shop-related-carousel .product:eq(3) p", text: "A lightweight cap for field visits and outdoor events." },
			{ selector: "#formats .rarsm-shop-related-carousel .product:eq(3) button", text: "Add to cart" },
			{ selector: "#formats .rarsm-shop-related-carousel .product:eq(4) .woocommerce-loop-product__title", text: "RARSM Tote Bag" },
			{ selector: "#formats .rarsm-shop-related-carousel .product:eq(4) p", text: "A practical bag for carrying the book and working documents." },
			{ selector: "#formats .rarsm-shop-related-carousel .product:eq(4) button", text: "Add to cart" },
			{ selector: "#payment .rarsm-payment-kicker", text: "Payment confirmation" },
			{ selector: "#payment h2 span", text: "A simple order and payment flow" },
			{ selector: "#payment .excerpt", text: "Follow the steps below to complete the purchase of a book or any shop item with ease." },
			{ selector: "#payment .rarsm-payment-step:eq(0) h5", text: "Choose item" },
			{ selector: "#payment .rarsm-payment-step:eq(0) p", text: "Select the book, an institutional package or a branded item that fits your needs." },
			{ selector: "#payment .rarsm-payment-step:eq(1) h5", text: "Add to cart" },
			{ selector: "#payment .rarsm-payment-step:eq(1) p", text: "Click Add to cart to save your choice and open the cart summary." },
			{ selector: "#payment .rarsm-payment-step:eq(2) h5", text: "Review the cart" },
			{ selector: "#payment .rarsm-payment-step:eq(2) p", text: "Check your items, enter your details and proceed to checkout." },
			{ selector: "#payment .rarsm-payment-step:eq(3) h5", text: "Complete payment" },
			{ selector: "#payment .rarsm-payment-step:eq(3) p", text: "You are redirected to the partner gateway. Once payment is confirmed, your order is tracked in your account." },
			{ selector: "#payment .rarsm-payment-card:eq(0) h5", text: "Mobile Money" },
			{ selector: "#payment .rarsm-payment-card:eq(0) p", text: "Mobile payment for local orders through the available channel: M-Pesa, Airtel Money, Orange Money or a similar service." },
			{ selector: "#payment .rarsm-payment-card:eq(1) h5", text: "Bank transfer" },
			{ selector: "#payment .rarsm-payment-card:eq(1) p", text: "Suitable for individuals, firms, companies and institutions that need a traceable and documented payment method." },
			{ selector: "#payment .rarsm-payment-card:eq(2) h5", text: "Institutional invoicing" },
			{ selector: "#payment .rarsm-payment-card:eq(2) p", text: "Supports quotations, pro forma invoices and purchase orders for public and private organizations." },
			{ selector: "#payment .rarsm-payment-card:eq(3) h5", text: "Local pickup" },
			{ selector: "#payment .rarsm-payment-card:eq(3) p", text: "For certain orders in Kinshasa, payment can be arranged when the book or another item is physically handed over." }
		],
                                                                                                                                                       "institutions.html": [
			{ selector: ".page_title .small-title", text: "Institutions" },
			{ selector: ".page_title .breadcrumb-item.active", text: "Institutions" },
			{ selector: ".section-institutions-intro .activities-eyebrow", text: "INSTITUTIONAL OFFER" },
			{ selector: ".section-institutions-intro h2", text: "A solution tailored to administrations, companies, universities and libraries" },
			{ selector: ".section-institutions-intro .institutions-lead", text: "RARSM can be purchased and distributed within professional or institutional settings, with dedicated handling for bulk orders, invoicing, libraries, training or documentary reference needs." },
			{ selector: ".section-institutions-intro p:not(.institutions-lead)", text: "This page is designed for organizations that want to equip their teams with a clear, centralized and immediately usable legal working tool focused on the Congolese mining sector." },
			{ selector: ".institutions-checklist li:eq(0)", text: "single-copy or multi-copy ordering" },
			{ selector: ".institutions-checklist li:eq(1)", text: "dedicated invoicing and follow-up for organizations" },
			{ selector: ".institutions-checklist li:eq(2)", text: "support for libraries, firms and legal departments" },
			{ selector: ".section-institutions-intro .btn-maincolor", text: "Request an offer" },
			{ selector: ".section-institutions-intro .btn-outline-maincolor", text: "Contact the team" },
			{ selector: ".institutions-floating-kicker", text: "Common uses" },
			{ selector: ".institutions-floating-card h4", text: "Library, training, compliance, bulk order" },
			{ selector: ".institutions-floating-card p.mb-0", text: "A flexible offer for public and private institutions." },
			{ selector: ".section-institutions-targets .activities-eyebrow", text: "INSTITUTIONAL LANDSCAPE" },
			{ selector: ".section-institutions-targets h2", text: "Institutions and services to know across the mining sector" },
			{ selector: ".section-institutions-targets .institutions-split-header > p", text: "Here is a sector-based reading of the main institutions referenced in the compendium. The tabs help you browse public services, technical bodies, financial agencies and control structures involved in the Congolese mining ecosystem." },
			{ selector: ".institutions-directory-nav .nav-item:eq(0) .nav-link", html: "Mining <span class='institutions-tab-count'>9</span>" },
			{ selector: ".institutions-directory-nav .nav-item:eq(1) .nav-link", html: "Finance <span class='institutions-tab-count'>5</span>" },
			{ selector: ".institutions-directory-nav .nav-item:eq(2) .nav-link", html: "Transport <span class='institutions-tab-count'>3</span>" },
			{ selector: ".institutions-directory-nav .nav-item:eq(3) .nav-link", html: "Research <span class='institutions-tab-count'>4</span>" },
			{ selector: ".institutions-directory-nav .nav-item:eq(4) .nav-link", html: "Trade <span class='institutions-tab-count'>2</span>" },
			{ selector: ".institutions-directory-nav .nav-item:eq(5) .nav-link", html: "Environment <span class='institutions-tab-count'>2</span>" },
			{ selector: ".institutions-directory-nav .nav-item:eq(6) .nav-link", html: "Other <span class='institutions-tab-count'>2</span>" },
			{ selector: ".institutions-card-actions a[href*='istitutions-details.php']", text: "See more" },
			{ selector: "#institutions-mines .institutions-service-card:eq(0) h4 a", text: "General Inspectorate of Mines - IGM" },
			{ selector: "#institutions-mines .institutions-service-card:eq(0) p", text: "Public service in charge of inspection, oversight and audit of mining and quarrying activities. It ensures compliance with the Mining Code, fights fraud and strengthens mineral traceability." },
			{ selector: "#institutions-mines .institutions-service-card:eq(1) p", text: "Technical body for advisory work, studies and planning attached to the Ministry of Mines. It assists the minister in designing mining policy and producing sector data." },
			{ selector: "#institutions-mines .institutions-service-card:eq(2) p", text: "Public institution responsible for building reserves for future generations and the post-mining era. It manages part of the mining royalty according to principles of security and transparency." },
			{ selector: "#institutions-mines .institutions-service-card:eq(3) p", text: "Authority responsible for the expertise, valuation and certification of certain mineral substances. It secures exports and the compliance of submitted lots." },
			{ selector: "#institutions-mines .institutions-service-card:eq(4) p", text: "National geological service responsible for acquiring, centralizing and disseminating the country's geological and mining data. It supports mapping, resource assessment and risk reduction." },
			{ selector: "#institutions-mines .institutions-service-card:eq(5) p", text: "Public institution responsible for managing the mining domain and mining and quarry rights. It processes title applications, keeps registers and prevents perimeter conflicts." },
			{ selector: "#institutions-mines .institutions-service-card:eq(6) p", text: "Public service that supports and supervises artisanal and small-scale mining. It promotes formalization, safety and the professionalization of the artisanal sector." },
			{ selector: "#institutions-mines .institutions-service-card:eq(7) p", text: "Regulatory and oversight authority for markets involving strategic mineral substances. It acts on the organization, stability and transparency of critical mineral markets." },
			{ selector: "#institutions-mines .institutions-service-card:eq(8) p", text: "Coordination mechanism between public services to fight mining fraud and smuggling. It organizes cooperation, joint controls and information sharing." },
			{ selector: "#institutions-mines .institutions-service-card:eq(8) .institutions-card-actions a:last-child", text: "More information" },
			{ selector: "#institutions-finances .institutions-service-card:eq(0) h4 a", text: "Ministry of Finance" },
			{ selector: "#institutions-finances .institutions-service-card:eq(0) p", text: "It leads the State's tax, financial and budgetary policy. In the mining sector, it ensures that mining revenues are mobilized and transformed into public resources." },
			{ selector: "#institutions-finances .institutions-service-card:eq(1) p", text: "Agency responsible for assessing, auditing and collecting taxes due to the central government. It notably monitors the tax obligations of mining companies and their partners." },
			{ selector: "#institutions-finances .institutions-service-card:eq(2) p", text: "Agency responsible for customs and excise legislation. It controls equipment imports, mineral exports and the related formalities." },
			{ selector: "#institutions-finances .institutions-service-card:eq(3) p", text: "Public service responsible for the assessment and collection of non-tax revenues. It intervenes in several rights, duties and royalties linked to the mining sector." },
			{ selector: "#institutions-finances .institutions-service-card:eq(4) h4 a", text: "Provincial revenue directorates" },
			{ selector: "#institutions-finances .institutions-service-card:eq(4) p", text: "Revenue bodies established by provinces to collect income falling under their jurisdiction. Their organization varies by province and they play an important role in mining territories." },
			{ selector: "#institutions-transports .institutions-service-card:eq(0) h4 a", text: "Ministry of Transport" },
			{ selector: "#institutions-transports .institutions-service-card:eq(0) p", text: "It develops national transport policy and acts on the logistics corridors used for minerals and equipment. Its action directly affects export costs and competitiveness." },
			{ selector: "#institutions-transports .institutions-service-card:eq(1) p", text: "Public institution responsible for regulating Congolese freight. It tracks cargo, provides logistics information and defends the interests of Congolese shippers." },
			{ selector: "#institutions-transports .institutions-service-card:eq(2) p", text: "The national maritime shipping company of the DRC. Lignes Maritimes Congolaises contributes to the organization of maritime and multimodal transport for goods, including mining products." },
			{ selector: "#institutions-recherche .institutions-service-card:eq(0) h4 a", text: "Ministry in charge of Scientific Research" },
			{ selector: "#institutions-recherche .institutions-service-card:eq(0) p", text: "It coordinates national research and innovation policy. In the mining field, it supports scientific institutions working on geology, materials, energy and the environment." },
			{ selector: "#institutions-recherche .institutions-service-card:eq(1) p", text: "Authority responsible for nuclear safety and radiation protection. Its role is essential whenever radioactive substances or radiation-based equipment are involved." },
			{ selector: "#institutions-recherche .institutions-service-card:eq(2) p", text: "Research and coordination institution for the peaceful use of atomic energy. It supports materials analysis, certain industrial studies and specialized scientific training." },
			{ selector: "#institutions-recherche .institutions-service-card:eq(3) p", text: "Scientific center specialized in Earth sciences. It produces maps, conducts surveys and studies geological risks as well as the environmental effects of mining activities." },
			{ selector: "#institutions-commerce .institutions-service-card:eq(0) h4 a", text: "Ministry of Foreign Trade" },
			{ selector: "#institutions-commerce .institutions-service-card:eq(0) p", text: "It leads national policy on imports, exports and trade relations. In the mining sector, it oversees the commercial aspects of mineral exports and export competitiveness." },
			{ selector: "#institutions-commerce .institutions-service-card:eq(1) p", text: "Public institution responsible for quality and conformity control. It intervenes on products, some equipment and foreign trade operations, including within the mining environment." },
			{ selector: "#institutions-environnement .institutions-service-card:eq(0) h4 a", text: "Ministry of Environment" },
			{ selector: "#institutions-environnement .institutions-service-card:eq(0) p", text: "It defines national policy on the environment, sustainable development and climate. In mining, it works to prevent pollution and protect ecosystems and exposed populations." },
			{ selector: "#institutions-environnement .institutions-service-card:eq(1) p", text: "The Congolese Environment Agency assesses the environmental and social impacts of projects. It follows impact studies, management plans and environmental risks related to mining projects." },
			{ selector: "#institutions-autres .institutions-service-card:eq(0) p", text: "Subsidiary of Gécamines created to supervise the purchase, processing and marketing of artisanal cobalt. It aims to improve formalization, traceability and reduce clandestine channels." },
			{ selector: "#institutions-autres .institutions-service-card:eq(1) p", text: "National mechanism implementing the Extractive Industries Transparency Initiative. It publishes data on production, payments and revenues from the extractive sector." },
			{ selector: ".section-institutions-cta .activities-eyebrow", text: "TAKE ACTION" },
			{ selector: ".section-institutions-cta h2", text: "Need an offer for your organization?" },
			{ selector: ".section-institutions-cta p", text: "We can prepare a tailored response for a bulk order, a library allocation, a team acquisition or a professional presentation request." },
			{ selector: ".section-institutions-cta .btn-maincolor", text: "Send a request" },
			{ selector: ".section-institutions-cta .btn-outline-maincolor", text: "View activities" }
		],
		"institutions.php": [
			{ selector: ".page_title .small-title", text: "Institutions" },
			{ selector: ".page_title .breadcrumb-item.active", text: "Institutions" }
		],
		"activites.html": [
			{ selector: ".page_title .small-title", text: "Activities" },
			{ selector: ".page_title .breadcrumb-item.active", text: "Activities" },
			{ selector: ".section-activities-intro .activities-eyebrow", text: "SECTOR AGENDA" },
			{ selector: ".section-activities-intro .activities-highlight-card h1", text: "Follow the annual activities of the mining sector" },
			{ selector: ".section-activities-intro .activities-highlight-card h5", text: "This page brings together the conferences, workshops, institutional consultations, field missions and public interventions that shape the annual life of the mining sector and its key institutions." },
			{ selector: ".section-activities-calendar-block .activities-calendar-label", text: "Annual calendar" },
			{ selector: ".section-activities-calendar-block .activities-calendar-intro", text: "Browse the annual mining sector agenda month by month. Marked dates open the details of institutional, technical, field and communication activities." },
			{ selector: ".section-activities-calendar-block .activities-nav-btn[data-calendar-nav='prev-year']", text: "Previous year" },
			{ selector: ".section-activities-calendar-block .activities-nav-btn[data-calendar-nav='prev-month']", text: "Previous month" },
			{ selector: ".section-activities-calendar-block .activities-nav-btn[data-calendar-nav='next-month']", text: "Next month" },
			{ selector: ".section-activities-calendar-block .activities-nav-btn[data-calendar-nav='next-year']", text: "Next year" },
			{ selector: ".section-activities-calendar-block label[for='activities-month-select']", text: "Choose month" },
			{ selector: ".section-activities-calendar-block label[for='activities-year-select']", text: "Choose year" },
			{ selector: ".section-activities-calendar-block .activities-calendar-toolbar", attr: { "aria-label": "Calendar navigation" } },
			{ selector: ".section-activities-calendar-block .activities-legend-item:eq(0)", html: "<span class='activities-legend-dot event-launch'></span>Forum" },
			{ selector: ".section-activities-calendar-block .activities-legend-item:eq(1)", html: "<span class='activities-legend-dot event-institution'></span>Institutions" },
			{ selector: ".section-activities-calendar-block .activities-legend-item:eq(2)", html: "<span class='activities-legend-dot event-signing'></span>Field" },
			{ selector: ".section-activities-calendar-block .activities-legend-item:eq(3)", html: "<span class='activities-legend-dot event-media'></span>Media" }
		],
		"contact.html": [
			{ selector: ".page_title .small-title", text: "Contact" },
			{ selector: ".page_title .breadcrumb-item.active", text: "Contact" },
			{ selector: ".js-rarsm-cartography h3", text: "Map" },
			{ selector: ".js-rarsm-cartography p", text: "Location of the RARSM office in Gombe, Kinshasa." },
			{ selector: ".contact-form h4", text: "Contact form" },
			{ selector: ".contact-form label[for='name']", text: "Full name *" },
			{ selector: ".contact-form label[for='email']", text: "Email address *" },
			{ selector: ".contact-form label[for='message']", text: "Message" },
			{ selector: ".contact-form button[type='submit']", text: "Send message" },
			{ selector: ".contact-form #name", placeholder: "Your full name" },
			{ selector: ".contact-form #email", placeholder: "Your email address" },
			{ selector: ".contact-form #message", placeholder: "Your message" },
			{ selector: ".contact-text h4", text: "Contact channels" },
			{ selector: ".contact-text h5:eq(0)", text: "Address:" },
			{ selector: ".contact-text h5:eq(1)", text: "WhatsApp / Phone:" },
			{ selector: ".contact-text h5:eq(2)", text: "Email:" },
			{ selector: ".social-icons + h4", text: "Social media" }
		],
		"faq.html": [
			{ selector: ".page_title .small-title", text: "FAQ" },
			{ selector: ".page_title .breadcrumb-item.active", text: "FAQ" },
			{ selector: ".js-rarsm-cartography h3", text: "Ask a question" },
			{ selector: ".js-rarsm-cartography p", text: "Before placing an order, you can contact us with any question about format, pricing, delivery or availability." },
			{ selector: ".contact-form h4", text: "Use the form below to contact us." },
			{ selector: ".contact-form button[type='submit']", text: "Send the question" },
			{ selector: ".accordion h4:eq(0)", text: "Before purchase" },
			{ selector: ".accordion h5:eq(0)", text: "Is the price of the book fixed?" },
			{ selector: ".accordion .card-body p:eq(0)", text: "The price depends on the chosen format, availability and, for grouped orders, the quantity requested. The final amount is always confirmed before payment." },
			{ selector: ".accordion h5:eq(1)", text: "Can I request an excerpt before ordering?" },
			{ selector: ".accordion .card-body p:eq(1)", text: "Yes. A presentation excerpt can be shared on request so you can discover the general structure of the compendium and its main themes." },
			{ selector: ".accordion h5:eq(2)", text: "Who is RARSM mainly intended for?" },
			{ selector: ".accordion .card-body p:eq(2)", text: "The compendium is designed for mining operators, investors, lawyers, advisory firms, public administrations, lecturers, researchers and students who need a reliable legal reference." },
			{ selector: ".accordion h5:eq(3)", text: "How do I start an order?" },
			{ selector: ".accordion .card-body p:eq(3)", text: "You can order through the contact page or by sending a direct request with the desired format, quantity and delivery location." },
			{ selector: ".accordion h4:eq(1)", text: "Formats, delivery and contact" },
			{ selector: ".accordion h5:eq(4)", text: "Is the PDF format available?" },
			{ selector: ".accordion .card-body p:eq(4)", text: "Depending on the edition and distribution campaign, a digital version may be available. Availability is always confirmed on request." },
			{ selector: ".accordion h5:eq(5)", text: "How is delivery handled?" },
			{ selector: ".accordion .card-body p:eq(5)", text: "Delivery depends on your location and the chosen format. Local handover, coordinated shipment or digital delivery can be arranged depending on the order." },
			{ selector: ".accordion h5:eq(6)", text: "Can I buy several copies?" },
			{ selector: ".accordion .card-body p:eq(6)", text: "Yes. Institutional or grouped orders are possible and can include a quotation, logistics follow-up and invoicing." },
			{ selector: ".accordion h5:eq(7)", text: "How can I reach you quickly?" },
			{ selector: ".accordion .card-body p:eq(7)", text: "The Contact page centralizes the form, email, WhatsApp and social media so you can reach the team quickly before placing an order." }
		],
		"shop-cart.php": [
			{ selector: ".page_title .small-title", text: "Cart" },
			{ selector: ".page_title .breadcrumb-item.active", text: "Cart" },
			{ selector: ".rarsm-status-kicker", text: "Empty cart" },
			{ selector: ".rarsm-status-card h3", text: "No item has been added yet" },
			{ selector: ".rarsm-status-card p:last", text: "Choose a RARSM book format to start your order and then proceed to checkout." },
			{ selector: ".rarsm-status-card .btn-maincolor", text: "View formats" },
			{ selector: ".woocommerce-message .button", text: "Checkout" },
			{ selector: ".shop_table thead th.product-name", text: "Product" },
			{ selector: ".shop_table thead th.product-price", text: "Price" },
			{ selector: ".shop_table thead th.product-quantity", text: "Quantity" },
			{ selector: ".shop_table thead th.product-subtotal", text: "Total" },
			{ selector: ".product-price .amount:contains('Sur devis')", text: "Quote on request" },
			{ selector: ".product-subtotal .amount:contains('A confirmer')", text: "To be confirmed" },
			{ selector: ".actions .button[name='update_cart']", value: "Update cart" },
			{ selector: ".cart_totals h2", text: "Summary" },
			{ selector: ".cart-subtotal th", text: "Subtotal" },
			{ selector: ".cart_totals tr:eq(1) th", text: "Immediate payment" },
			{ selector: ".cart_totals tr:eq(2) th", text: "Delivery" },
			{ selector: ".wc-proceed-to-checkout .checkout-button", text: "Proceed to checkout" },
			{ selector: ".rarsm-shop-note", text: "A customer account is required at checkout so payments, cancellations and future product sales can be tracked in one place." }
		],
		"shop-checkout.php": [
			{ selector: ".page_title .small-title", text: "Checkout" },
			{ selector: ".page_title .breadcrumb-item.active", text: "Checkout" },
			{ selector: ".rarsm-status-kicker", text: "Empty cart" },
			{ selector: ".rarsm-status-card h3", text: "Add a product first" },
			{ selector: ".rarsm-status-card p", text: "Checkout becomes available once the book has been added to the cart." },
			{ selector: ".rarsm-status-card .btn-maincolor", text: "View formats" },
			{ selector: ".hero-bg h3", text: "Login required" },
			{ selector: ".hero-bg p", text: "To track orders and retrieve your payments, checkout is reserved for signed-in users." },
			{ selector: ".hero-bg .btn-maincolor", text: "Log in" },
			{ selector: ".hero-bg .btn-outline-darkgrey", text: "Back to cart" },
			{ selector: ".rarsm-status-card .rarsm-status-kicker", text: "New account" },
			{ selector: ".rarsm-status-card h4", text: "Create an account before paying" },
			{ selector: ".rarsm-status-card .btn-outline-maincolor", text: "Sign up" },
			{ selector: ".woocommerce-info", text: "Your order will be created before you are redirected to the intermediary payment platform." },
			{ selector: ".woocommerce-billing-fields h3", text: "Contact details" },
			{ selector: "label[for='billing_first_name']", text: "First name *" },
			{ selector: "label[for='billing_last_name']", text: "Last name *" },
			{ selector: "label[for='billing_company']", text: "Institution / Company" },
			{ selector: "label[for='billing_phone']", text: "Phone / WhatsApp *" },
			{ selector: "label[for='billing_email']", text: "Email *" },
			{ selector: "label[for='billing_country']", text: "Country *" },
			{ selector: "label[for='billing_address_1']", text: "Address" },
			{ selector: "label[for='billing_city']", text: "City" },
			{ selector: "label[for='billing_state']", text: "Province / State" },
			{ selector: "label[for='billing_postcode']", text: "Postal code" },
			{ selector: ".woocommerce-additional-fields h3", text: "Delivery and payment" },
			{ selector: "label[for='order_delivery']", text: "Delivery mode" },
			{ selector: "#order_delivery option[value='pickup']", text: "Pickup in Kinshasa" },
			{ selector: "#order_delivery option[value='local']", text: "Local delivery" },
			{ selector: "#order_delivery option[value='shipping']", text: "Shipping outside Kinshasa" },
			{ selector: "#order_delivery option[value='email']", text: "Digital delivery" },
			{ selector: "label[for='payment_method']", text: "Payment method" },
			{ selector: "#payment_method option[value='partner_gateway']", text: "Partner gateway" },
			{ selector: "#payment_method option[value='mobile_money']", text: "Mobile Money" },
			{ selector: "#payment_method option[value='bank_transfer']", text: "Bank transfer" },
			{ selector: "label[for='order_comments']", text: "Order notes" },
			{ selector: "#order_comments", placeholder: "Invoice, quotation, quantity, delivery notes..." },
			{ selector: ".hero-bg h5", text: "Tracked account" },
			{ selector: ".hero-bg p.mb-0", text: "Orders created here will appear in your order history." },
			{ selector: "#order_review_heading", text: "Your order" },
			{ selector: ".woocommerce-checkout-review-order-table th.product-name", text: "Product" },
			{ selector: ".woocommerce-checkout-review-order-table th.product-total", text: "Total" },
			{ selector: ".woocommerce-checkout-review-order-table .amount:contains('Sur devis')", text: "Quote on request" },
			{ selector: ".woocommerce-checkout-review-order-table tfoot tr:eq(0) th", text: "Subtotal" },
			{ selector: ".woocommerce-checkout-review-order-table tfoot tr:eq(1) th", text: "Amount due" },
			{ selector: ".woocommerce-checkout-review-order-table tfoot tr:eq(2) th", text: "Delivery" },
			{ selector: ".place-order .button", text: "Back to cart" },
			{ selector: "#place_order", value: "Continue to payment" }
		],
		"shop-account-login.php": [
			{ selector: ".page_title .small-title", text: "Sign in" },
			{ selector: ".page_title .breadcrumb-item.active", text: "Sign in" },
			{ selector: ".rarsm-account-form-card h3", text: "Sign in" },
			{ selector: ".rarsm-account-form-card > p", text: "Sign in to track your orders, resume a payment and retrieve your information later." },
			{ selector: "label[for='login_email']", text: "Email" },
			{ selector: "label[for='login_password']", text: "Password" },
			{ selector: ".rarsm-form-actions .btn-maincolor", text: "Log in" },
			{ selector: ".rarsm-account-side-card .rarsm-status-kicker", text: "New customer" },
			{ selector: ".rarsm-account-side-card h4", text: "Create an account to track your purchases" },
			{ selector: ".rarsm-account-side-card p", text: "Your account links the cart, checkout, order flow and history so that future items sold on the site remain connected to the same customer profile." },
			{ selector: ".rarsm-account-side-card .btn-outline-maincolor", text: "Create account" },
			{ selector: ".rarsm-account-side-card .btn-outline-darkgrey", text: "Back to cart" }
		],
		"shop-account-register.php": [
			{ selector: ".page_title .small-title", text: "Create account" },
			{ selector: ".page_title .breadcrumb-item.active", text: "Register" },
			{ selector: ".rarsm-account-form-card h3", text: "Customer registration" },
			{ selector: ".rarsm-account-form-card > p", text: "This account will be used to track payments, orders and future product sales on the site." },
			{ selector: "label[for='register_first_name']", text: "First name" },
			{ selector: "label[for='register_last_name']", text: "Last name" },
			{ selector: "label[for='register_email']", text: "Email" },
			{ selector: "label[for='register_phone']", text: "Phone / WhatsApp" },
			{ selector: "label[for='register_password']", text: "Password" },
			{ selector: "label[for='register_password_confirm']", text: "Confirm password" },
			{ selector: ".rarsm-account-form-card .btn-maincolor", text: "Create my account" }
		],
		"shop-account-orders.php": [
			{ selector: ".page_title .small-title", text: "My orders" },
			{ selector: ".page_title .breadcrumb-item.active", text: "Orders" },
			{ selector: ".rarsm-status-kicker", text: "No orders yet" },
			{ selector: ".rarsm-status-card h3", text: "Your history is still empty" },
			{ selector: ".rarsm-status-card p", text: "Start by adding the RARSM book to your cart, then complete checkout to see your orders appear here." },
			{ selector: ".rarsm-status-card .btn-maincolor", text: "Buy the book" },
			{ selector: "table th:eq(0)", text: "Reference" },
			{ selector: "table th:eq(1)", text: "Date" },
			{ selector: "table th:eq(2)", text: "Status" },
			{ selector: "table th:eq(3)", text: "Amount" },
			{ selector: "table th:eq(4)", text: "Action" },
			{ selector: "a.btn:contains('Payer')", text: "Pay" },
			{ selector: "a.btn:contains('Relancer')", text: "Retry" },
			{ selector: "a.btn:contains('Assistance')", text: "Support" }
		],
		"success.php": [
			{ selector: ".rarsm-status-kicker", text: "Payment successful" },
			{ selector: ".rarsm-status-card h2", text: "Order confirmed" },
			{ selector: ".rarsm-status-card p", text: "Thank you. Your payment has been recorded for this order." },
			{ selector: ".rarsm-status-metadata span:eq(0)", text: "Amount:" },
			{ selector: ".rarsm-status-metadata span:eq(1)", text: "Status:" },
			{ selector: ".rarsm-gateway-actions .btn-maincolor", text: "View my orders" },
			{ selector: ".rarsm-gateway-actions .btn-outline-maincolor", text: "Continue shopping" }
		],
		"cancel.php": [
			{ selector: ".rarsm-status-kicker", text: "Payment cancelled" },
			{ selector: ".rarsm-status-card h2", text: "Your order was not completed" },
			{ selector: ".rarsm-status-card p", text: "The order has been marked as cancelled. You can resume the payment later." },
			{ selector: ".rarsm-gateway-actions .btn-maincolor", text: "Retry payment" },
			{ selector: ".rarsm-gateway-actions .btn-outline-maincolor", text: "My orders" }
		],
		"pending.php": [
			{ selector: ".rarsm-status-kicker", text: "Order tracking" },
			{ selector: ".rarsm-status-card p:eq(0)", text: "Reference:" },
			{ selector: ".rarsm-status-metadata span:eq(0)", text: "Status:" },
			{ selector: ".rarsm-status-metadata span:eq(1)", text: "Amount due:" },
			{ selector: ".rarsm-gateway-actions .btn-maincolor", text: "View my orders" },
			{ selector: ".rarsm-gateway-actions .btn-outline-maincolor", text: "Contact the team" }
		],
		"activites-details.php": [
			{ selector: ".page_title .small-title", text: "Activity details" },
			{ selector: ".page_title .breadcrumb-item:eq(1) a", text: "Activities" },
			{ selector: ".activities-detail-alert", text: "The requested activity could not be found. The next available activity has been displayed by default." },
			{ selector: ".activities-detail-main-card .activities-calendar-label", text: "Selected activity" },
			{ selector: ".activities-detail-section:eq(0) h4", text: "About this activity" },
			{ selector: ".activities-detail-section:eq(1) h4", text: "Institution involved" },
			{ selector: ".activities-detail-section:eq(2) h4", text: "Key points" },
			{ selector: ".activities-detail-cta .btn-outline-maincolor", text: "Back to calendar" },
			{ selector: ".activities-detail-cta .btn-maincolor", text: "Contact the team" },
			{ selector: ".activities-institution-card .activities-calendar-label", text: "Institution involved" },
			{ selector: ".activities-institution-actions .btn:eq(0)", text: "View institution" },
			{ selector: ".activities-institution-actions .btn:eq(1)", text: "Back to institutions" },
			{ selector: ".activities-detail-side-card:last .activities-calendar-label", text: "Other activities" },
			{ selector: ".activities-detail-side-card:last h4", text: "Also coming up" }
		],
		"istitutions-details.php": [
			{ selector: ".page_title .breadcrumb-item.active", text: "Details" },
			{ selector: ".page_title .breadcrumb-item:eq(1) a", text: "Institutions" },
			{ selector: ".institution-detail-alert", text: "The requested institution could not be found. The first available record has been displayed by default." },
			{ selector: ".institution-detail-section:eq(0) h4", text: "Role and scope of action" },
			{ selector: ".institution-detail-section:eq(1) h4", text: "Why this institution matters in RARSM" },
			{ selector: ".institution-detail-main-actions .btn-maincolor", text: "Visit official website" },
			{ selector: ".institution-detail-main-actions .btn-outline-maincolor", text: "Back to institutions" },
			{ selector: ".institution-side-card:eq(0) .activities-eyebrow", text: "OTHER INSTITUTIONS" },
			{ selector: ".institution-side-card:eq(0) h4", text: "Suggested institutions" },
			{ selector: ".institution-side-card:eq(1) .activities-eyebrow", text: "QUICK ACTIONS" },
			{ selector: ".institution-side-card:eq(1) h4", text: "Useful links" },
			{ selector: ".institution-quick-actions .btn:eq(0)", text: "Back to overview" },
			{ selector: ".institution-quick-actions .btn:eq(1)", text: "Order the book" },
			{ selector: ".institution-quick-actions .btn:eq(2)", text: "Contact the team" },
			{ selector: ".institution-quick-actions .btn:eq(3)", text: "Official website" }
		]
	};

	function getQueryParameter(parameterName) {
		try {
			return new URLSearchParams(window.location.search).get(parameterName);
		} catch (error) {
			return null;
		}
	}

	function sanitizeSlug(value) {
		return ((value || "") + "").toLowerCase().replace(/[^a-z0-9\-]/g, "");
	}

	function applyStoredText($elements, key, translated, language) {
		if (!$elements || !$elements.length) {
			return;
		}

		$elements.each(function () {
			var $element = $(this);
			cacheOriginal($element, key, $element.text());
			$element.text(language === "en" ? translated : restoreOriginal($element, key));
		});
	}

	function applyStoredAttribute($elements, key, attribute, translated, language) {
		if (!$elements || !$elements.length) {
			return;
		}

		$elements.each(function () {
			var $element = $(this);
			cacheOriginal($element, key, $element.attr(attribute));
			$element.attr(attribute, language === "en" ? translated : restoreOriginal($element, key));
		});
	}

	function applyInstitutionDetailTranslation(language) {
		if (!$ || getPageKey() !== "istitutions-details.php") {
			return;
		}

		var slug = sanitizeSlug(getQueryParameter("institution")) || "igm";
		var entry = institutionDetailDictionary[slug] || institutionDetailDictionary.igm;
		var originalTitle = document.documentElement.getAttribute("data-rarsm-original-title") || document.title;
		var sectorNote = institutionSectorNotesEn[entry.sector] || institutionSectorNotesEn.default;

		applyStoredText($(".page_title .small-title"), "InstitutionDetailHeroTitle", entry.name, language);
		applyStoredText($(".institution-detail-heading h2"), "InstitutionDetailHeading", entry.name, language);
		applyStoredText($(".institution-detail-lead"), "InstitutionDetailLead", entry.summary, language);
		applyStoredText($(".institution-detail-section:eq(0) p"), "InstitutionDetailBody", entry.details, language);
		applyStoredText($(".institution-detail-section:eq(1) p"), "InstitutionDetailSectorNote", sectorNote, language);
		applyStoredAttribute($("meta[name='description']"), "InstitutionMetaDescription", "content", entry.summary, language);

		$(".institution-detail-sector").each(function () {
			var $element = $(this);
			cacheOriginal($element, "InstitutionMainSector", $element.text());
			var originalSector = restoreOriginal($element, "InstitutionMainSector");
			$element.text(language === "en" ? (institutionSectorLabels[originalSector] || originalSector) : originalSector);
		});

		$(".institution-suggestion-item").each(function () {
			var $item = $(this);
			var href = $item.attr("href") || "";
			var match = href.match(/institution=([a-z0-9\-]+)/i);
			var suggestionSlug = match ? sanitizeSlug(match[1]) : "";
			var suggestion = institutionDetailDictionary[suggestionSlug];
			var $name = $item.find("strong");
			var $sector = $item.find("small");

			if (suggestion) {
				applyStoredText($name, "InstitutionSuggestionName", suggestion.name, language);
			}

			$sector.each(function () {
				var $sectorElement = $(this);
				cacheOriginal($sectorElement, "InstitutionSuggestionSector", $sectorElement.text());
				var originalSector = restoreOriginal($sectorElement, "InstitutionSuggestionSector");
				$sectorElement.text(language === "en" ? (institutionSectorLabels[originalSector] || originalSector) : originalSector);
			});
		});

		document.title = language === "en" ? "RARSM | " + entry.name : originalTitle;
	}

	function resolveLanguage(language) {
		return language === "en" ? "en" : "fr";
	}

	function getPageKey() {
		var path = window.location.pathname || "";
		var fileName = path.split("/").pop();
		return fileName ? fileName.toLowerCase() : "index.html";
	}

	function getStoredLanguage() {
		var query;

		try {
			query = new URLSearchParams(window.location.search).get("lang");
		} catch (error) {
			query = null;
		}

		if (query === "en" || query === "fr") {
			return query;
		}

		try {
			var stored = window.localStorage.getItem(STORAGE_KEY);
			if (stored === "en" || stored === "fr") {
				return stored;
			}
		} catch (error2) {
			// Ignore storage access errors.
		}

		return "fr";
	}

	function setLanguageCookie(language) {
		document.cookie = COOKIE_NAME + "=" + encodeURIComponent(language) + "; path=/; max-age=31536000; SameSite=Lax";
	}

	function rememberLanguage(language) {
		try {
			window.localStorage.setItem(STORAGE_KEY, language);
		} catch (error) {
			// Ignore storage access errors.
		}

		setLanguageCookie(language);
	}

	function cacheOriginal($element, key, value) {
		var dataKey = "rarsmI18nOriginal" + key;
		if (typeof $element.data(dataKey) === "undefined") {
			$element.data(dataKey, value);
		}
	}

	function restoreOriginal($element, key) {
		return $element.data("rarsmI18nOriginal" + key);
	}

	function applyRule(rule, language) {
		var $elements = $(rule.selector);

		if (!$elements.length) {
			return;
		}

		$elements.each(function () {
			var $element = $(this);

			if (typeof rule.text !== "undefined") {
				cacheOriginal($element, "Text", $element.text());
				cacheOriginal($element, "TextHtml", $element.html());

				if (language === "en") {
					$element.text(rule.text);
				} else {
					$element.html(restoreOriginal($element, "TextHtml"));
				}
			}

			if (typeof rule.html !== "undefined") {
				cacheOriginal($element, "Html", $element.html());
				$element.html(language === "en" ? rule.html : restoreOriginal($element, "Html"));
			}

			if (typeof rule.placeholder !== "undefined") {
				cacheOriginal($element, "Placeholder", $element.attr("placeholder"));
				$element.attr("placeholder", language === "en" ? rule.placeholder : restoreOriginal($element, "Placeholder"));
			}

			if (typeof rule.value !== "undefined") {
				cacheOriginal($element, "Value", $element.val());
				$element.val(language === "en" ? rule.value : restoreOriginal($element, "Value"));
			}

			if (rule.attr) {
				Object.keys(rule.attr).forEach(function (attribute) {
					cacheOriginal($element, "Attr" + attribute, $element.attr(attribute));
					$element.attr(attribute, language === "en" ? rule.attr[attribute] : restoreOriginal($element, "Attr" + attribute));
				});
			}
		});
	}

	function applyTitle(language) {
		var pageKey = getPageKey();
		var originalTitle = document.documentElement.getAttribute("data-rarsm-original-title") || document.title;
		if (!document.documentElement.getAttribute("data-rarsm-original-title")) {
			document.documentElement.setAttribute("data-rarsm-original-title", originalTitle);
		}

		document.title = language === "en" && titles[pageKey] ? titles[pageKey] : originalTitle;
		document.documentElement.lang = language;
	}

	function applyRules(language) {
		if (!$) {
			return;
		}

		var pageKey = getPageKey();
		var aliasedPageKey = pageAliases[pageKey] || null;
		var mergedRules = (rules.common || []).slice();

		if (rules[pageKey]) {
			mergedRules = mergedRules.concat(rules[pageKey]);
		}

		if (aliasedPageKey && rules[aliasedPageKey]) {
			mergedRules = mergedRules.concat(rules[aliasedPageKey]);
		}

		mergedRules.forEach(function (rule) {
			applyRule(rule, language);
		});
	}

	function notifyListeners(language) {
		listeners.forEach(function (listener) {
			try {
				listener(language);
			} catch (error) {
				// Continue notifying other listeners.
			}
		});

		if (typeof window.CustomEvent === "function") {
			window.dispatchEvent(new window.CustomEvent("rarsm:languagechange", {
				detail: {
					language: language
				}
			}));
		}
	}

	function getLanguage() {
		if (!window.__rarsmLanguage) {
			window.__rarsmLanguage = resolveLanguage(getStoredLanguage());
		}

		return window.__rarsmLanguage;
	}

	function refreshLanguage() {
		var language = getLanguage();
		applyTitle(language);
		applyRules(language);
		applyInstitutionDetailTranslation(language);
	}

	function setLanguage(language) {
		var normalized = resolveLanguage(language);
		window.__rarsmLanguage = normalized;
		rememberLanguage(normalized);
		refreshLanguage();
		notifyListeners(normalized);
	}

	function translate(key, fallback) {
		var language = getLanguage();
		var entry = strings[key] || {};

		if (language === "en" && entry.en) {
			return entry.en;
		}

		if (language === "fr" && entry.fr) {
			return entry.fr;
		}

		return fallback || "";
	}

	window.RARSM_I18N = {
		getLanguage: getLanguage,
		setLanguage: setLanguage,
		refresh: refreshLanguage,
		t: translate,
		onChange: function (listener) {
			if (typeof listener === "function") {
				listeners.push(listener);
			}
		}
	};

	if ($) {
		$(function () {
			refreshLanguage();
		});

		$(window).on("load", function () {
			refreshLanguage();
		});
	}
})(window, document, window.jQuery);
