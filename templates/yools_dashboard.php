<?php if( is_user_logged_in() ): ?>

<?php include_once( ABSPATH . 'wp-admin/includes/plugin.php' ); ?>

<div class="sheet">

<!--
***
******* DASHBOARD *******
***
-->

<div class="dashboard-group dashboard-group-website">
	<h2>Dashboard</h2>
	<div class="dashboard-slider" style="width: 100%;">
		<ul>
			<li>
				<div class="row">
					
					<a class="dashboard-group-card col-25" href="<?php echo esc_url( home_url( '/' ) ); ?>">
						<div class="dashboard-group-card-icon">
							<img src="<?php echo plugin_dir_url( __DIR__ ) . 'public/images/cards/website.svg'; ?>">
						</div> <!-- .dashboard-group-card-icon -->
						<div class="dashboard-group-card-text">
							<span>Website bekijken</span>
						</div> <!-- .dashboard-group-card-text -->
					</a> <!-- .col-25 -->
					
					<a class="dashboard-group-card col-25" href="/wp-admin/edit.php?post_type=page">
						<div class="dashboard-group-card-icon">
							<img src="<?php echo plugin_dir_url( __DIR__ ) . 'public/images/cards/page.svg'; ?>">
						</div> <!-- .dashboard-group-card-icon -->
						<div class="dashboard-group-card-text">
							<span>Pagina's bewerken</span>
						</div> <!-- .dashboard-group-card-text -->
					</a> <!-- .col-25 -->
					
					<a class="dashboard-group-card col-25" href="https://support.yools.be/support/home" target="_blank">
						<div class="dashboard-group-card-icon">
							<img src="<?php echo plugin_dir_url( __DIR__ ) . 'public/images/cards/support.svg'; ?>">
						</div> <!-- .dashboard-group-card-icon -->
						<div class="dashboard-group-card-text">
							<span>Support</span>
						</div> <!-- .dashboard-group-card-text -->
					</a> <!-- .col-25 -->
					
					<a class="dashboard-group-card col-25" href="/wp-admin">
						<div class="dashboard-group-card-icon">
							<img src="<?php echo plugin_dir_url( __DIR__ ) . 'public/images/cards/old.svg'; ?>">
						</div> <!-- .dashboard-group-card-icon -->
						<div class="dashboard-group-card-text">
							<span>Naar het oude dashboard</span>
						</div> <!-- .dashboard-group-card-text -->
					</a> <!-- .col-25 -->

				</div> <!-- .row -->
			</li>
			<li>
				<div class="row">
					
					<a class="dashboard-group-card col-25" href="/wp-admin/upload.php">
						<div class="dashboard-group-card-icon">
							<img src="<?php echo plugin_dir_url( __DIR__ ) . 'public/images/cards/media.svg'; ?>">
						</div> <!-- .dashboard-group-card-icon -->
						<div class="dashboard-group-card-text">
							<span>Media</span>
						</div> <!-- .dashboard-group-card-text -->
					</a> <!-- .col-25 -->
					
					<?php if( current_user_can( 'edit_theme_options' ) ): ?>
					<a class="dashboard-group-card col-25" href="/wp-admin/nav-menus.php">
						<div class="dashboard-group-card-icon">
							<img src="<?php echo plugin_dir_url( __DIR__ ) . 'public/images/cards/menu.svg'; ?>">
						</div> <!-- .dashboard-group-card-icon -->
						<div class="dashboard-group-card-text">
							<span>Menu bewerken</span>
						</div> <!-- .dashboard-group-card-text -->
					</a> <!-- .col-25 -->
					<?php endif; ?>
					
				</div> <!-- .row -->
			</li>
		</ul>
	</div> <!-- .dashboard-slider -->
</div> <!-- .dashboard-group .dashboard-group-website -->

<!--
***
******* PLUGINS *******
***
-->

<?php if(
	is_plugin_active('slideshow-jquery-image-gallery/slideshow.php')
	||
	is_plugin_active('nextgen-gallery/nggallery.php') ):
?>
<div class="dashboard-group dashboard-group-plugins">
	<h2>Plugins</h2>
	<div class="dashboard-slider" style="width: 100%;">
		<ul>
			<li>
				<div class="row">

					<?php if( is_plugin_active('slideshow-jquery-image-gallery/slideshow.php') ): ?>
					<a class="dashboard-group-card col-25" href="/wp-admin/edit.php?post_type=slideshow">
						<div class="dashboard-group-card-icon">
							<img src="<?php echo plugin_dir_url( __DIR__ ) . 'public/images/cards/'; ?>">
						</div> <!-- .dashboard-group-card-icon -->
						<div class="dashboard-group-card-text">
							<span>Slideshows bewerken</span>
						</div> <!-- .dashboard-group-card-text -->
					</a> <!-- .col-25 -->
					<?php endif; ?>

					<?php if( is_plugin_active('nextgen-gallery/nggallery.php') ): ?>
					<a class="dashboard-group-card col-25" href="/wp-admin/admin.php?page=nggallery-manage-gallery">
						<div class="dashboard-group-card-icon">
							<img src="<?php echo plugin_dir_url( __DIR__ ) . 'public/images/cards/'; ?>">
						</div> <!-- .dashboard-group-card-icon -->
						<div class="dashboard-group-card-text">
							<span>Gallerijen bewerken</span>
						</div> <!-- .dashboard-group-card-text -->
					</a> <!-- .col-25 -->
					<?php endif; ?>

				</div> <!-- .row -->
			</li>
		</ul>
	</div> <!-- .dashboard-slider -->
</div> <!-- .dashboard-group .dashboard-group-plugins -->
<?php endif; ?>

<!--
***
******* POSTS *******
***
-->

<div class="dashboard-group dashboard-group-posts">
	<h2>Berichten & catalogussen</h2>

	<div class="dashboard-slider" style="width: 100%;">
		<ul>

		<?php

		$yools_post_types = array(
			'attachment',
			'page',
			'revision',
			'nav_menu_item',
			'custom_css',
			'customize_changeset',
			'oembed_cache',
			'yools_admin',
			'acf-field-group',
			'acf-field',
			'iwp-log',
			'wpcf7_contact_form',
			'slideshow',
			'ngg_album',
			'ngg_gallery',
			'ngg_pictures',
			'lightbox_library',
			'displayed_gallery',
			'display_type',
			'gal_display_source',
			'acf',
			'user_request'
		);

		$yools_count_post_types = 0;
		$yools_total_post_types = 0;

		?>

		<?php foreach( get_post_types( '', 'names' ) as $post_type ): ?>
			<?php if( !in_array($post_type, $yools_post_types) ): ?>
				<?php $yools_total_post_types++; ?>
			<?php endif; ?>
		<?php endforeach; ?>

		<?php foreach( get_post_types( '', 'names' ) as $post_type ): ?>

			<?php if( !in_array($post_type, $yools_post_types) ): ?>

				<?php if( $yools_count_post_types % 4 == 0 ): ?>
					<li>
					<div class="row">
				<?php endif; ?>
				<?php $yools_count_post_types ++; ?>

				<?php
					$post_object = get_post_type_object($post_type);
					$count_posts_in_post_type = wp_count_posts( $post_type );
				?>

					<a class="dashboard-group-card col-25" href="<?php echo "/wp-admin/edit.php?post_type=" . $post_type; ?>">
						<div class="dashboard-group-card-icon">
							<div class="post-count">
								<span>
									<?php echo $count_posts_in_post_type->publish; ?>
								</span>
							</div> <!-- .post-count -->
						</div> <!-- .dashboard-group-card-icon -->
						<div class="dashboard-group-card-text">
							<span>
								<?php echo $post_object->label; ?>
							</span>
						</div> <!-- .dashboard-group-card-text -->
					</a> <!-- .col-25 -->

				<?php if( $yools_count_post_types % 4 == 0 || $yools_count_post_types == $yools_total_post_types ): ?>
					</div> <!-- .row -->
					</li>
				<?php endif; ?>

			<?php endif; ?>

		<?php endforeach; ?>

		</ul>
	</div> <!-- .dashboard-slider -->
</div> <!-- .dashboard-group .dashboard-group-posts -->

<!--
***
******* EXTRA *******
***
-->

<div class="dashboard-group dashboard-group-extra">
	<h2>Extra</h2>
	<div class="dashboard-slider" style="width: 100%;">
		<ul>
			<li>
				<div class="row">

					<a class="dashboard-group-card col-25" href="#">
						<div class="dashboard-group-card-icon">
							<img src="<?php echo plugin_dir_url( __DIR__ ) . 'public/images/cards/'; ?>">
						</div> <!-- .dashboard-group-card-icon -->
						<div class="dashboard-group-card-text">
							<span>Website laten opfrissen?</span>
						</div> <!-- .dashboard-group-card-text -->
					</a> <!-- .col-25 -->

					<a class="dashboard-group-card col-25" href="#">
						<div class="dashboard-group-card-icon">
							<img src="<?php echo plugin_dir_url( __DIR__ ) . 'public/images/cards/'; ?>">
						</div> <!-- .dashboard-group-card-icon -->
						<div class="dashboard-group-card-text">
							<span>Ken je iemand die ook een website wil?</span>
						</div> <!-- .dashboard-group-card-text -->
					</a> <!-- .col-25 -->

					<a class="dashboard-group-card col-25" href="#">
						<div class="dashboard-group-card-icon">
							<img src="<?php echo plugin_dir_url( __DIR__ ) . 'public/images/cards/'; ?>">
						</div> <!-- .dashboard-group-card-icon -->
						<div class="dashboard-group-card-text">
							<span>Heb jij al SSL?</span>
						</div> <!-- .dashboard-group-card-text -->
					</a> <!-- .col-25 -->

					<a class="dashboard-group-card col-25" href="#">
						<div class="dashboard-group-card-icon">
							<img src="<?php echo plugin_dir_url( __DIR__ ) . 'public/images/cards/'; ?>">
						</div> <!-- .dashboard-group-card-icon -->
						<div class="dashboard-group-card-text">
							<span>Ontdek onze Full service</span>
						</div> <!-- .dashboard-group-card-text -->
					</a> <!-- .col-25 -->

				</div> <!-- .row -->
			</li>
		</ul>
	</div> <!-- .dashboard-slider -->
</div> <!-- .dashboard-group .dashboard-group-extra -->

</div> <!-- .sheet -->

<?php endif; ?>

<?php if( !is_user_logged_in() ): ?>

	<div class="not-logged-in">
		<a href="<?php echo wp_login_url(); ?>" class="button">Inloggen</a>
	</div> <!-- .not-logged-in -->
	
<?php endif; ?>
