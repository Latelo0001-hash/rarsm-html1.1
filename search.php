<?php
require_once __DIR__ . '/includes/i18n.php';
//////////////////
//Text variables//
//////////////////
$is_english = rarsm_current_language() === 'en';
$searched_message = $is_english ? 'You searched for:' : 'Vous avez recherché :';
$has_results = $is_english ? 'Pages matching your search:' : 'Pages correspondant à votre recherche :';
$no_results = $is_english ? 'No page matches your search.' : 'Aucune page ne correspond à votre recherche.';


if(isset($_POST['search'])) {
	$search = stripslashes(strip_tags(trim((string) $_POST['search'])));
	//if search is empty - exit with no results
	if(!$search) {
		echo $no_results;
		exit();
	}

	$dir = ".";
	$found_files = array();

	//serching query string inside site root dierectory in HTML files
	foreach( new DirectoryIterator($dir) as $file) {
	    if( $file->isFile() && strtolower($file->getExtension()) === 'html') {
	        $content = strip_tags(file_get_contents($file->getBasename()));
	        if(stripos($content, $search) !== false) {
	        	$found_files[] = $file->getBasename('.html');
	        }
	        
	    }
	}

	//building response text
	echo $searched_message . ' <strong>' . htmlspecialchars($search, ENT_QUOTES, 'UTF-8') . '</strong><br>';

	//if found something
	if($found_files) {
		echo $has_results . '<br>';
		foreach ($found_files as $key => $file) {
			echo '<a href="' . $file . '.html">' . ucfirst($file) . '</a><br>';
		}
	//if no search results
	} else {
		echo $no_results;
	}
}
