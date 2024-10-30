<h2>Featured Image Settings</h2>

<?php
$sections = [ 'presets', 'sizes'  ];

foreach ( $sections as $section ) {
	include sprintf(
		'%s/settings/html/settings/html/%s.php',
		Cropexpress()->plugin_data()['plugin_dir_path'],
		$section
	);
}
