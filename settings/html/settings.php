<div class="wrap" id="ce-settings">
	<h1>Crop.Express Settings</h1>
	<div flex grow>
		<aside>
			<ul nav id="ce-nav">
				<li>
					<a
						<?php echo empty( $_GET['screen'] ) || 'welcome' === $_GET['screen'] ? 'selected' : '' ?>
							href="<?php echo remove_query_arg( 'screen' ); ?>">
						Welcome
					</a>
				</li>
				<?php /*
				<li>
					<a
						<?php echo empty( $_GET['screen'] ) || 'site-wide' === $_GET['screen'] ? 'selected' : '' ?>
							href="<?php echo remove_query_arg( 'screen' ); ?>">
						Site-wide
					</a>
				</li>
 */ ?>
				<li>
					<a
						<?php echo ! empty( $_GET['screen'] ) && 'block' === $_GET['screen'] ? 'selected' : '' ?>
							href="<?php echo add_query_arg( [ 'screen' => 'block' ] ); ?>">
						Gutenberg Block
					</a>
				</li>
				<li>
					<a
						<?php echo ! empty( $_GET['screen'] ) && 'featured-image' === $_GET['screen'] ? 'selected' : '' ?>
							href="<?php echo add_query_arg( [ 'screen' => 'featured-image' ] ); ?>">
						Featured Image
					</a>
				</li>
				<li>
					<a
						<?php echo ! empty( $_GET['screen'] ) && 'media-library' === $_GET['screen'] ? 'selected' : '' ?>
							href="<?php echo add_query_arg( [ 'screen' => 'media-library' ] ); ?>">
						Media Library
					</a>
				</li>
			</ul>
		</aside>

		<main flex-column>
			<?php include sprintf( '%ssettings/html/settings/%s.php',
				Cropexpress()->plugin_data()['plugin_dir_path'],
				$screen
			); ?>
		</main>
	</div>
</div>