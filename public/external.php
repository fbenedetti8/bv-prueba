<?php

$files = array(
	'ga.js' => 'https://ssl.google-analytics.com/ga.js',
	'bsa.js' => 'https://s3.buysellads.com/ac/bsa.js',
	'pro.js' => 'https://s3.buysellads.com/ac/pro.js'
);

if(isset($files[$_GET['file']])) {
	if ($files[$_GET['file']] == 'ga.js'){
		header('Expires: '.gmdate('D, d M Y H:i:s \G\M\T', time() + ((60 * 60) * 48))); // 2 days for GA
	} else {
		header('Expires: '.gmdate('D, d M Y H:i:s \G\M\T', time() + (60 * 60))); // Default set to 1 hour
	}

	echo file_get_contents($files[$_GET['file']]);
}