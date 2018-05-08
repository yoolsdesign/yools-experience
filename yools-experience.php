<?php
/*
Plugin Name:  Yools Experience
Plugin URI:   https://yools.be
Description:  Yools Experience Plugin
Version:      0.3.2
Author:       Yools
Author URI:   https://yools.be
*/

/**
   * Enqueue scripts & style
   *
   *
*/
	function yools_admin_css() {
		wp_enqueue_style( 'yools-unslider-css', plugins_url( '/public/css/unslider.css', __FILE__ ) );
		wp_enqueue_style( 'yools-unslider-dots-css', plugins_url( '/public/css/unslider-dots.css', __FILE__ ) );
		wp_enqueue_style( 'yools-admin-css', plugins_url( '/public/css/yools_admin.css', __FILE__ ) );
		wp_enqueue_script( 'yools-unslider', plugins_url( '/public/js/unslider.js', __FILE__ ) );
	}
	add_action( 'wp_enqueue_scripts', 'yools_admin_css' );

	function yools_backend_css() {
		if ( current_user_can('klant') ) {
			wp_enqueue_style( 'yools-backend-css', plugins_url( '/public/css/yools_backend.css', __FILE__ ) );
			wp_enqueue_script( 'yools-backend-js', plugins_url( '/public/js/yools_backend.js', __FILE__ ) );
		}
	}
	add_action( 'admin_enqueue_scripts', 'yools_backend_css' );

/**
   * Create a custom post type.
   *
   * yools_admin creates a new post type for the login/password pages
*/
	function yools_admin() {
		$labels = array(
			'name'                  => 'Yools',
			'singular_name'         => 'Yools',
			'menu_name'             => 'Yools',
			'name_admin_bar'        => 'Yools',
			'archives'              => 'Item Archives',
			'attributes'            => 'Item Attributes',
			'parent_item_colon'     => 'Parent Item:',
			'all_items'             => 'All Items',
			'add_new_item'          => 'Add New Item',
			'add_new'               => 'Add New',
			'new_item'              => 'New Item',
			'edit_item'             => 'Edit Item',
			'update_item'           => 'Update Item',
			'view_item'             => 'View Item',
			'view_items'            => 'View Items',
			'search_items'          => 'Search Item',
			'not_found'             => 'Not found',
			'not_found_in_trash'    => 'Not found in Trash',
			'featured_image'        => 'Featured Image',
			'set_featured_image'    => 'Set featured image',
			'remove_featured_image' => 'Remove featured image',
			'use_featured_image'    => 'Use as featured image',
			'insert_into_item'      => 'Insert into item',
			'uploaded_to_this_item' => 'Uploaded to this item',
			'items_list'            => 'Items list',
			'items_list_navigation' => 'Items list navigation',
			'filter_items_list'     => 'Filter items list',
		);
		$args = array(
			'label'                 => 'Yools',
			'labels'                => $labels,
			'supports'              => array( 'title', 'editor' ),
			'hierarchical'          => false,
			'public'                => true,
			'show_ui'               => true,
			'show_in_menu'          => false,
			'menu_position'         => 5,
			'show_in_admin_bar'     => false,
			'show_in_nav_menus'     => false,
			'can_export'            => true,
			'has_archive'           => true,
			'exclude_from_search'   => false,
			'publicly_queryable'    => true,
			'capability_type'       => 'page',
		);
		register_post_type( 'yools_admin', $args );
	}
	add_action( 'init', 'yools_admin', 0);


	/**
	 *
   	 * Apply custom template to yools_admin post type
	 *
  	*/
	add_filter('single_template', 'yools_admin_template');

	function yools_admin_template($single) {

			global $wp_query, $post;

			/* Checks for single template by post type */
			if ( $post->post_type == 'yools_admin' ) {
					if ( file_exists( dirname( __FILE__ ) . '/templates/yools_admin_template.php' ) ) {
							return dirname( __FILE__ ) . '/templates/yools_admin_template.php';
					}
			}

			return $single;
	}

	/**
	 *
   	 * Apply custom class to body if post slug matches the Yools dashboard
	 *
  	*/
	add_filter( 'body_class','add_body_classes' );
	function add_body_classes( $classes ) {
	    if ( is_single('user-account') ) {
	     
	        $classes[] = 'yools-dashboard';
	         
	    }
	    return $classes;
	}

	/**
	 *
   	 * Custom registration mail
	 *
  	*/
	function send_welcome_email_to_new_user($user_id) {
	    $user = get_userdata($user_id);
	    $user_email = $user->user_email;
	    $key = get_password_reset_key($user);

	    $to = $user_email;
	    $subject = "Hey " . $user->user_firstname . "!";
	    $body = '
	              <h1>Hallo ' . $user->user_firstname . ',</h1></br>
	              <p>Bericht</p>'
	              . wp_login_url(). '?action=rp&key=' . $key . '&login=' . rawurlencode($user->user_login)
	    ;
	    $headers = array('Content-Type: text/html; charset=UTF-8','From: Yools Webdesign <support@yools.be>');

	    if ( wp_mail($to, $subject, $body, $headers) ) {
	      error_log("email has been successfully sent to " . $user_email);
	    }
	    else {
	      error_log("email failed to sent to " . $user_email);
	    }
	}
	add_action('user_register', 'send_welcome_email_to_new_user');

	/**
	 *
   	 * Edit admin toolbar
	 *
  	*/
	function yools_admin_bar_render() {
		global $wp_admin_bar;
		$logout_url = wp_login_url();
		$home_url = home_url();
		
		if ( current_user_can('klant') ) {
			/* ADMIN BAR */
			$wp_admin_bar->remove_menu('comments');
			$wp_admin_bar->remove_menu('my-account');
			$wp_admin_bar->remove_menu('search');
			$wp_admin_bar->remove_menu('seed-csp4-notice');
			$wp_admin_bar->remove_menu('customize');
			$wp_admin_bar->remove_menu('site-name');
			$wp_admin_bar->remove_menu('wp-logo');
			$wp_admin_bar->remove_menu('new-content');
			
			$wp_admin_bar->add_menu( array(
				'id' => 'yools-website',
				'title' => __('Website bekijken'),
				'href' => $home_url
			));
			
			$wp_admin_bar->add_menu( array(
				'id' => 'yools-dashboard',
				'title' => __('Dashboard'),
				'href' => '/yools_admin/user-account/'
			));
			
			$wp_admin_bar->add_menu( array(
				'id' => 'yools-support',
				'title' => __('Hulp nodig?'),
				'href' => 'https://support.yools.be/support/home'
			));
			
			$wp_admin_bar->add_menu( array(
				'id' => 'yools-logout',
				'title' => __('Uitloggen'),
				'href' => $logout_url . '?action=logout'
			));
		}
	}
	add_action( 'wp_before_admin_bar_render', 'yools_admin_bar_render' );

	/**
	 *
   	 * Edit WP menu
	 *
  	*/
	function custom_menu_page_removing() {
		
			if ( current_user_can('klant') ) {
				remove_menu_page( 'wpcf7' );
				remove_menu_page( 'index.php');
				remove_menu_page( 'profile.php');
				remove_menu_page( 'tools.php');
				remove_menu_page( 'plugin-editor.php');
				remove_submenu_page( 'themes.php', 'themes.php');
				remove_submenu_page( 'themes.php', 'widgets.php');
				remove_submenu_page( 'themes.php', 'customize.php');
				remove_submenu_page( 'themes.php', 'wpeditor_themes');
				remove_menu_page( 'customize.php');
				add_menu_page( 'Dashboard', 'Dashboard', 'manage_options', 'custom.php', '', 'dashicons-welcome-widgets-menus', 90 );
			}
		
	}
	add_action( 'admin_menu', 'custom_menu_page_removing', 999 );


	/**
	 *
   	 * Add floating button to backend
	 *
  	*/
	function yools_floating_button() {
	    ?>

	    <div class="yools-floating-button">
	        <svg xmlns="http://www.w3.org/2000/svg" width="44" height="64" viewBox="0 0 44 64"><desc>  Created with Sketch.</desc><g fill="none"><g fill="#FFF"><path d="M23.8 55L19.3 55C17.9 55 17 55.9 17 57.3L17 61.8C17 63.1 17.9 64 19.3 64L23.8 64C25.1 64 26 63.1 26 61.8L26 57.3C26 55.9 25.1 55 23.8 55Z"/><path d="M22 0C9.8 0 0 9.8 0 22 0 23.3 0 24.5 0.2 25.7 0.5 27.2 2 27.9 3.4 27.7L8.1 26.2C9.3 26 10 24.7 9.8 23.5 9.8 23 9.8 22.5 9.8 22 9.8 15.2 15.2 9.8 22 9.8 28.8 9.8 34.2 15.2 34.2 22 34.2 26.2 31.8 28.2 27.4 31.8 23.2 35.3 18.3 39.2 17.1 46.3 16.9 47.8 18.1 49 19.6 49L24.4 49C25.7 49 26.6 48.3 26.9 47 27.6 44.1 29.8 42.1 33.5 39.4 38.1 35.8 44 31.1 44 22 44 9.8 34.2 0 22 0Z"/></g></g></svg>
	        <span class="floating-message floating-message-1">Hulp nodig?</span>
	        <span class="floating-message floating-message-2">Zit je even vast?</span>
	        <ul>
	        	<li><a href="https://support.yools.be/support/" target="_blank">Ga naar onze helpdesk</a></li>
	        	<li><a href="https://support.yools.be/support/tickets/new" target="_blank">Stel ons je vraag</a></li>
	        	<li><a href="https://yools.freshdesk.com/support/solutions/folders/5000120221" target="_blank">Veelgestelde vragen</a></li>
	        	<li><a href="https://www.facebook.com/yoolsbelgium/" target="_blank">Yools op Facebook</a></li>
	        </ul>
	    </div> <!-- .yools-floating-button -->

	    <?php
	}
	add_action( 'admin_notices', 'yools_floating_button' );


	/**
	 *
   	 * Remove unnecessary meta boxes
	 *
  	*/
	function my_remove_meta_boxes() {
	if ( current_user_can('klant') ) {
		remove_meta_box( 'pageparentdiv', 'page', 'normal' );
		remove_meta_box( 'commentsdiv', 'page', 'normal' );
		remove_meta_box( 'revisionsdiv', 'page', 'normal' );
		remove_meta_box( 'revisionsdiv', 'post', 'normal' );
		remove_meta_box( 'aam-acceess-manager', 'post', 'normal' );
	}
	}
	add_action( 'admin_init', 'my_remove_meta_boxes' );


class Yools_Experience_Plugin {

  /**
   * Initializes the plugin.
   *
   * To keep the initialization fast, only add filter and action
   * hooks in the constructor.
  */
  public function __construct() {
		// custom login
		add_shortcode( 'custom-login-form', array( $this, 'render_login_form' ) );
		add_action( 'login_form_login', array( $this, 'redirect_to_custom_login' ) );
		add_filter( 'authenticate', array( $this, 'maybe_redirect_at_authenticate' ), 101, 3 );
		add_action( 'wp_logout', array( $this, 'redirect_after_logout' ) );
		add_filter( 'login_redirect', array( $this, 'redirect_after_login' ), 10, 3 );
		// custom registration
		/*
		add_shortcode( 'custom-register-form', array( $this, 'render_register_form' ) );
		add_action( 'login_form_register', array( $this, 'redirect_to_custom_register' ) );
		add_action( 'login_form_register', array( $this, 'do_register_user' ) );
		*/
		// password reset
		add_action( 'login_form_lostpassword', array( $this, 'redirect_to_custom_lostpassword' ) );
		add_shortcode( 'custom-password-lost-form', array( $this, 'render_password_lost_form' ) );
		add_action( 'login_form_lostpassword', array( $this, 'do_password_lost' ) );
		add_filter( 'retrieve_password_message', array( $this, 'replace_retrieve_password_message' ), 10, 4 );
		add_action( 'login_form_rp', array( $this, 'redirect_to_custom_password_reset' ) );
		add_action( 'login_form_resetpass', array( $this, 'redirect_to_custom_password_reset' ) );
		add_shortcode( 'custom-password-reset-form', array( $this, 'render_password_reset_form' ) );
		add_action( 'login_form_rp', array( $this, 'do_password_reset' ) );
		add_action( 'login_form_resetpass', array( $this, 'do_password_reset' ) );
		// user dashboard
		add_shortcode( 'yools-dashboard', array( $this, 'render_yools_dashboard' ) );
  }

  /**
	 * Plugin activation hook.
	 *
	 * Creates all WordPress pages needed by the plugin.
	 */
	public static function plugin_activated() {
	    // Information needed for creating the plugin's pages
	    $page_definitions = array(
	        'user-login' => array(
	            'title' => __( 'Inloggen', 'yools-experience' ),
	            'content' => '[custom-login-form]'
	        ),
	        'user-account' => array(
	            'title' => __( 'Jouw dashboard', 'yools-experience' ),
	            'content' => '[yools-dashboard]'
	        ),
			/*
			'member-register' => array(
				'title' => __( 'Register', 'yools-experience' ),
				'content' => '[custom-register-form]'
			),
			*/
			'user-password-lost' => array(
				'title' => __( 'Wachtwoord vergeten?', 'yools-experience' ),
				'content' => '[custom-password-lost-form]'
			),
			'user-password-reset' => array(
				'title' => __( 'Kies een nieuw wachtwoord', 'yools-experience' ),
				'content' => '[custom-password-reset-form]'
			)
	    );
	  
	    foreach ( $page_definitions as $slug => $post ) {
	        // Check that the page doesn't exist already
	        $query = new WP_Query( 'pagename=' . $slug );
	        if ( ! $query->have_posts() ) {
	        	// Add the page using the data from the array above
						$args = array(
	                    'post_content'   => $post['content'],
	                    'post_name'      => $slug,
	                    'post_title'     => $post['title'],
	                    'post_status'    => 'publish',
	                    'post_type'      => 'yools_admin',
	                    'ping_status'    => 'closed',
	                    'comment_status' => 'closed',
	          );
	          wp_insert_post( $args );
	        }
	    }
	}

	/**
	 * A shortcode for rendering the login form.
	 *
	 * @param  array   $attributes  Shortcode attributes.
	 * @param  string  $content     The text content for shortcode. Not used.
	 *
	 * @return string  The shortcode output
	 */
	public function render_login_form( $attributes, $content = null ) {
	  // Parse shortcode attributes
	  $default_attributes = array( 'show_title' => false );
	  $attributes = shortcode_atts( $default_attributes, $attributes );
	  $show_title = $attributes['show_title'];

	    // Error messages
		$errors = array();
		if ( isset( $_REQUEST['login'] ) ) {
		    $error_codes = explode( ',', $_REQUEST['login'] );
		 
		    foreach ( $error_codes as $code ) {
		        $errors []= $this->get_error_message( $code );
		    }
		}
		$attributes['errors'] = $errors;

		// Check if user just logged out
		$attributes['logged_out'] = isset( $_REQUEST['logged_out'] ) && $_REQUEST['logged_out'] == true;
		
		// Check if user just updated password
		$attributes['password_updated'] = isset( $_REQUEST['password'] ) && $_REQUEST['password'] == 'changed';
	 
	  if ( is_user_logged_in() ) {
	      return __( 'You are already signed in.', 'yools-experience' );
	  }
		
		// Check if the user just registered
		$attributes['registered'] = isset( $_REQUEST['registered'] );
		
		// Check if the user just requested a new password 
		$attributes['lost_password_sent'] = isset( $_REQUEST['checkemail'] ) && $_REQUEST['checkemail'] == 'confirm';
	     
	  // Pass the redirect parameter to the WordPress login functionality: by default,
	  // don't specify a redirect, but if a valid redirect URL has been passed as
	  // request parameter, use it.
	  $attributes['redirect'] = '';
	  if ( isset( $_REQUEST['redirect_to'] ) ) {
	      $attributes['redirect'] = wp_validate_redirect( $_REQUEST['redirect_to'], $attributes['redirect'] );
	  }
	     
	  // Render the login form using an external template
	  return $this->get_template_html( 'login_form', $attributes );
	}

	/**
	 * Renders the contents of the given template to a string and returns it.
	 *
	 * @param string $template_name The name of the template to render (without .php)
	 * @param array  $attributes    The PHP variables for the template
	 *
	 * @return string               The contents of the template.
	 */
	private function get_template_html( $template_name, $attributes = null ) {
	    if ( ! $attributes ) {
	        $attributes = array();
	    }
	 
	    ob_start();
	 
	    do_action( 'yools_experience_before_' . $template_name );
	 
	    require( 'templates/' . $template_name . '.php');
	 
	    do_action( 'yools_experience_after_' . $template_name );
	 
	    $html = ob_get_contents();
	    ob_end_clean();
	 
	    return $html;
	}

	/**
	 * Redirect the user to the custom login page instead of wp-login.php.
	 */
	function redirect_to_custom_login() {
	    if ( $_SERVER['REQUEST_METHOD'] == 'GET' ) {
	        $redirect_to = isset( $_REQUEST['redirect_to'] ) ? $_REQUEST['redirect_to'] : null;
	     
	        if ( is_user_logged_in() ) {
	            $this->redirect_logged_in_user( $redirect_to );
	            exit;
	        }
	 
	        // The rest are redirected to the login page
	        $login_url = home_url( '/yools_admin/user-login' );
	        if ( ! empty( $redirect_to ) ) {
	            $login_url = add_query_arg( 'redirect_to', $redirect_to, $login_url );
	        }
	 
	        wp_redirect( $login_url );
	        exit;
	    }
	}

	/**
	 * Redirects the user to the correct page depending on whether he / she
	 * is an admin or not.
	 *
	 * @param string $redirect_to   An optional redirect_to URL for admin users
	 */
	private function redirect_logged_in_user( $redirect_to = null ) {
	    $user = wp_get_current_user();
	    if ( user_can( $user, 'manage_options' ) ) {
	        if ( $redirect_to ) {
	            wp_safe_redirect( $redirect_to );
	        } else {
	            wp_redirect( admin_url() );
	        }
	    } else {
	        wp_redirect( home_url( 'user-account' ) );
	    }
	}

	/**
	 * Redirect the user after authentication if there were any errors.
	 *
	 * @param Wp_User|Wp_Error  $user       The signed in user, or the errors that have occurred during login.
	 * @param string            $username   The user name used to log in.
	 * @param string            $password   The password used to log in.
	 *
	 * @return Wp_User|Wp_Error The logged in user, or error information if there were errors.
	 */
	function maybe_redirect_at_authenticate( $user, $username, $password ) {
	    // Check if the earlier authenticate filter (most likely, 
	    // the default WordPress authentication) functions have found errors
	    if ( $_SERVER['REQUEST_METHOD'] === 'POST' ) {
	        if ( is_wp_error( $user ) ) {
	            $error_codes = join( ',', $user->get_error_codes() );
	 
	            $login_url = home_url( '/yools_admin/user-login' );
	            $login_url = add_query_arg( 'login', $error_codes, $login_url );
	 
	            wp_redirect( $login_url );
	            exit;
	        }
	    }
	 
	    return $user;
	}

	/**
	 * Finds and returns a matching error message for the given error code.
	 *
	 * @param string $error_code    The error code to look up.
	 *
	 * @return string               An error message.
	 */
	private function get_error_message( $error_code ) {
	    switch ( $error_code ) {
			// Reset password
			case 'expiredkey':
			case 'invalidkey':
					return __( 'De link voor het opnieuw instellen van het wachtwoord is niet meer geldig.', 'yools-experience' );
			case 'password_reset_mismatch':
					return __( "De twee wachtwoorden die je hebt ingevoerd komen niet overeen.", 'yools-experience' );
			case 'password_reset_empty':
					return __( "Sorry, we accepteren geen lege wachtwoorden.", 'yools-experience' );
			// Lost password
			case 'empty_username':
					return __( 'Je moet je e-mailadres invoeren om verder te gaan.', 'yools-experience' );
			case 'invalid_email':
			case 'invalidcombo':
					return __( 'Er zijn geen gebruikers geregistreerd met dit e-mailadres.', 'yools-experience' );
			// Registration errors
			case 'email':
					return __( 'Het e-mailadres dat je hebt ingevoerd is niet geldig.', 'yools-experience' );
			case 'email_exists':
					return __( 'Er bestaat een account met dit e-mailadres.', 'yools-experience' );
			case 'closed':
					return __( 'Het registreren van nieuwe gebruikers is momenteel niet toegestaan.', 'yools-experience' );
	        case 'empty_username':
	            return __( 'Je hebt wel een e-mailadres, toch?', 'yools-experience' );
	        case 'empty_password':
	            return __( 'Je moet een wachtwoord invoeren om in te loggen.', 'yools-experience' );
	        case 'invalid_username':
	            return __(
	                "We vinden geen gebruikers met dat e-mailadres. Misschien heb je een andere gebruikt toen je je aanmeldde?",
	                'yools-experience'
	            );
	        case 'incorrect_password':
	            $err = __(
	                "Het wachtwoord dat je hebt ingevoerd klopt niet helemaal. <a href='%s'>Ben je je wachtwoord vergeten</a>?",
	                'yools-experience'
	            );
	            return sprintf( $err, wp_lostpassword_url() );
	 
	        default:
	            break;
	    }
	    return __( 'Een onbekende fout is opgetreden. Probeer het later opnieuw.', 'yools-experience' );
	}

	/**
	 * Redirect to custom login page after the user has been logged out.
	 */
	public function redirect_after_logout() {
	    $redirect_url = home_url( 'user-login?logged_out=true' );
	    wp_safe_redirect( $redirect_url );
	    exit;
	}

    /**
	 * Returns the URL to which the user should be redirected after the (successful) login.
	 *
	 * @param string           $redirect_to           The redirect destination URL.
	 * @param string           $requested_redirect_to The requested redirect destination URL passed as a parameter.
	 * @param WP_User|WP_Error $user                  WP_User object if login was successful, WP_Error object otherwise.
	 *
	 * @return string Redirect URL
	 */
	public function redirect_after_login( $redirect_to, $requested_redirect_to, $user ) {
	    $redirect_url = home_url();
	 
	    if ( ! isset( $user->ID ) ) {
	        return $redirect_url;
	    }
	 
	    if ( user_can( $user, 'manage_options' ) ) {
	        // Use the redirect_to parameter if one is set, otherwise redirect to admin dashboard.
	        if ( $requested_redirect_to == '' ) {
	            $redirect_url = admin_url();
	        } else {
	            $redirect_url = $requested_redirect_to;
	        }
	    } else {
	        // Non-admin users always go to their account page after login
	        $redirect_url = home_url( 'user-account' );
	    }
	 
	    return wp_validate_redirect( $redirect_url, home_url() );
	}
	
	/**
	 * A shortcode for rendering the new user registration form.
	 *
	 * @param  array   $attributes  Shortcode attributes.
	 * @param  string  $content     The text content for shortcode. Not used.
	 *
	 * @return string  The shortcode output
	 */
	public function render_register_form( $attributes, $content = null ) {
			// Parse shortcode attributes
			$default_attributes = array( 'show_title' => false );
			$attributes = shortcode_atts( $default_attributes, $attributes );
		
			// Retrieve possible errors from request parameters
			$attributes['errors'] = array();
			if ( isset( $_REQUEST['register-errors'] ) ) {
					$error_codes = explode( ',', $_REQUEST['register-errors'] );

					foreach ( $error_codes as $error_code ) {
							$attributes['errors'] []= $this->get_error_message( $error_code );
					}
			}

			if ( is_user_logged_in() ) {
					return __( 'You are already signed in.', 'yools-experience' );
			} elseif ( ! get_option( 'users_can_register' ) ) {
					return __( 'Registering new users is currently not allowed.', 'yools-experience' );
			} else {
					return $this->get_template_html( 'register_form', $attributes );
			}
	}
	
	/**
	 * Redirects the user to the custom registration page instead
	 * of wp-login.php?action=register.
	 */
	public function redirect_to_custom_register() {
			if ( 'GET' == $_SERVER['REQUEST_METHOD'] ) {
					if ( is_user_logged_in() ) {
							$this->redirect_logged_in_user();
					} else {
							wp_redirect( home_url( 'member-register' ) );
					}
					exit;
			}
	}
	
	/**
	 * Validates and then completes the new user signup process if all went well.
	 *
	 * @param string $email         The new user's email address
	 * @param string $first_name    The new user's first name
	 * @param string $last_name     The new user's last name
	 *
	 * @return int|WP_Error         The id of the user that was created, or error if failed.
	 */
	private function register_user( $email, $first_name, $last_name ) {
			$errors = new WP_Error();

			// Email address is used as both username and email. It is also the only
			// parameter we need to validate
			if ( ! is_email( $email ) ) {
					$errors->add( 'email', $this->get_error_message( 'email' ) );
					return $errors;
			}

			if ( username_exists( $email ) || email_exists( $email ) ) {
					$errors->add( 'email_exists', $this->get_error_message( 'email_exists') );
					return $errors;
			}

			// Generate the password so that the subscriber will have to check email...
			$password = wp_generate_password( 12, false );

			$user_data = array(
					'user_login'    => $email,
					'user_email'    => $email,
					'user_pass'     => $password,
					'first_name'    => $first_name,
					'last_name'     => $last_name,
					'nickname'      => $first_name,
			);

			$user_id = wp_insert_user( $user_data );
			wp_new_user_notification( $user_id, $password );

			return $user_id;
	}
	
	/**
	* Handles the registration of a new user.
	*
	* Used through the action hook "login_form_register" activated on wp-login.php
	* when accessed through the registration action.
	*/
	public function do_register_user() {
			if ( 'POST' == $_SERVER['REQUEST_METHOD'] ) {
					$redirect_url = home_url( 'member-register' );

					if ( ! get_option( 'users_can_register' ) ) {
							// Registration closed, display error
							$redirect_url = add_query_arg( 'register-errors', 'closed', $redirect_url );
					} else {
							$email = $_POST['email'];
							$first_name = sanitize_text_field( $_POST['first_name'] );
							$last_name = sanitize_text_field( $_POST['last_name'] );

							$result = $this->register_user( $email, $first_name, $last_name );

							if ( is_wp_error( $result ) ) {
									// Parse errors into a string and append as parameter to redirect
									$errors = join( ',', $result->get_error_codes() );
									$redirect_url = add_query_arg( 'register-errors', $errors, $redirect_url );
							} else {
									// Success, redirect to login page.
									$redirect_url = home_url( 'user-login' );
									$redirect_url = add_query_arg( 'registered', $email, $redirect_url );
							}
					}

					wp_redirect( $redirect_url );
					exit;
			}
	}
	
	/**
	* Redirects the user to the custom "Forgot your password?" page instead of
	* wp-login.php?action=lostpassword.
	*/
	public function redirect_to_custom_lostpassword() {
			if ( 'GET' == $_SERVER['REQUEST_METHOD'] ) {
					if ( is_user_logged_in() ) {
							$this->redirect_logged_in_user();
							exit;
					}

					wp_redirect( home_url( 'user-password-lost' ) );
					exit;
			}
	}
	
	/**
	* A shortcode for rendering the form used to initiate the password reset.
	*
	* @param  array   $attributes  Shortcode attributes.
	* @param  string  $content     The text content for shortcode. Not used.
	*
	* @return string  The shortcode output
	*/
	public function render_password_lost_form( $attributes, $content = null ) {
		// Parse shortcode attributes
		$default_attributes = array( 'show_title' => false );
		$attributes = shortcode_atts( $default_attributes, $attributes );
		
		// Retrieve possible errors from request parameters
		$attributes['errors'] = array();
		if ( isset( $_REQUEST['errors'] ) ) {
				$error_codes = explode( ',', $_REQUEST['errors'] );

				foreach ( $error_codes as $error_code ) {
						$attributes['errors'] []= $this->get_error_message( $error_code );
				}
		}

		if ( is_user_logged_in() ) {
				return __( 'Je bent al ingelogd.', 'yools-experience' );
		} else {
				return $this->get_template_html( 'password_lost_form', $attributes );
		}
	}
	
	/**
	* Initiates password reset.
	*/
	public function do_password_lost() {
			if ( 'POST' == $_SERVER['REQUEST_METHOD'] ) {
					$errors = retrieve_password();
					if ( is_wp_error( $errors ) ) {
							// Errors found
							$redirect_url = home_url( 'user-password-lost' );
							$redirect_url = add_query_arg( 'errors', join( ',', $errors->get_error_codes() ), $redirect_url );
					} else {
							// Email sent
							$redirect_url = home_url( 'user-login' );
							$redirect_url = add_query_arg( 'checkemail', 'confirm', $redirect_url );
					}

					wp_redirect( $redirect_url );
					exit;
			}
	}
	
	/**
	 * Returns the message body for the password reset mail.
	 * Called through the retrieve_password_message filter.
	 *
	 * @param string  $message    Default mail message.
	 * @param string  $key        The activation key.
	 * @param string  $user_login The username for the user.
	 * @param WP_User $user_data  WP_User object.
	 *
	 * @return string   The mail message to send.
	 */
	public function replace_retrieve_password_message( $message, $key, $user_login, $user_data ) {
			// Create new message
			$msg  = __( 'Hallo!', 'yools-experience' ) . "\r\n\r\n";
			$msg .= sprintf( __( 'Je vroeg ons om je wachtwoord voor jouw account opnieuw in te stellen met behulp van het e-mailadres %s.', 'yools-experience' ), $user_login ) . "\r\n\r\n";
			$msg .= __( "Als dit een vergissing was of als je niet om een reset van het wachtwoord heeft gevraagd, mag je deze e-mail negeren en gebeurt er verder niets.", 'yools-experience' ) . "\r\n\r\n";
			$msg .= __( 'Ga naar het volgende adres om je wachtwoord opnieuw in te stellen:', 'yools-experience' ) . "\r\n\r\n";
			$msg .= site_url( "wp-login.php?action=rp&key=$key&login=" . rawurlencode( $user_login ), 'yools-experience' ) . "\r\n\r\n";
			$msg .= __( 'Bedankt!', 'yools-experience' ) . "\r\n";

			return $msg;
	}
	
	/**
	* Redirects to the custom password reset page, or the login page
	* if there are errors.
	*/
	public function redirect_to_custom_password_reset() {
			if ( 'GET' == $_SERVER['REQUEST_METHOD'] ) {
					// Verify key / login combo
					$user = check_password_reset_key( $_REQUEST['key'], $_REQUEST['login'] );
					if ( ! $user || is_wp_error( $user ) ) {
							if ( $user && $user->get_error_code() === 'expired_key' ) {
									wp_redirect( home_url( 'user-login?login=expiredkey' ) );
							} else {
									wp_redirect( home_url( 'user-login?login=invalidkey' ) );
							}
							exit;
					}

					$redirect_url = home_url( 'user-password-reset' );
					$redirect_url = add_query_arg( 'login', esc_attr( $_REQUEST['login'] ), $redirect_url );
					$redirect_url = add_query_arg( 'key', esc_attr( $_REQUEST['key'] ), $redirect_url );

					wp_redirect( $redirect_url );
					exit;
			}
	}
	
	/**
	 * A shortcode for rendering the form used to reset a user's password.
	 *
	 * @param  array   $attributes  Shortcode attributes.
	 * @param  string  $content     The text content for shortcode. Not used.
	 *
	 * @return string  The shortcode output
	 */
	public function render_password_reset_form( $attributes, $content = null ) {
			// Parse shortcode attributes
			$default_attributes = array( 'show_title' => false );
			$attributes = shortcode_atts( $default_attributes, $attributes );

			if ( is_user_logged_in() ) {
					return __( 'Je bent al ingelogd.', 'yools-experience' );
			} else {
					if ( isset( $_REQUEST['login'] ) && isset( $_REQUEST['key'] ) ) {
							$attributes['login'] = $_REQUEST['login'];
							$attributes['key'] = $_REQUEST['key'];

							// Error messages
							$errors = array();
							if ( isset( $_REQUEST['error'] ) ) {
									$error_codes = explode( ',', $_REQUEST['error'] );

									foreach ( $error_codes as $code ) {
											$errors []= $this->get_error_message( $code );
									}
							}
							$attributes['errors'] = $errors;

							return $this->get_template_html( 'password_reset_form', $attributes );
					} else {
							return __( 'Ongeldige link voor wachtwoordherstel.', 'yools-experience' );
					}
			}
	}
	
	/**
	 * Resets the user's password if the password reset form was submitted.
	 */
	public function do_password_reset() {
		if ( 'POST' == $_SERVER['REQUEST_METHOD'] ) {
			$rp_key = $_REQUEST['rp_key'];
			$rp_login = $_REQUEST['rp_login'];
			$user = check_password_reset_key( $rp_key, $rp_login );
			if ( ! $user || is_wp_error( $user ) ) {
				if ( $user && $user->get_error_code() === 'expired_key' ) {
					wp_redirect( home_url( 'user-login?login=expiredkey' ) );
				} else {
					wp_redirect( home_url( 'user-login?login=invalidkey' ) );
				}
				exit;
			}
			if ( isset( $_POST['pass1'] ) ) {
				if ( $_POST['pass1'] != $_POST['pass2'] ) {
					// Passwords don't match
					$redirect_url = home_url( 'user-password-reset' );
					$redirect_url = add_query_arg( 'key', $rp_key, $redirect_url );
					$redirect_url = add_query_arg( 'login', $rp_login, $redirect_url );
					$redirect_url = add_query_arg( 'error', 'password_reset_mismatch', $redirect_url );
					wp_redirect( $redirect_url );
					exit;
				}
				if ( empty( $_POST['pass1'] ) ) {
					// Password is empty
					$redirect_url = home_url( 'user-password-reset' );
					$redirect_url = add_query_arg( 'key', $rp_key, $redirect_url );
					$redirect_url = add_query_arg( 'login', $rp_login, $redirect_url );
					$redirect_url = add_query_arg( 'error', 'password_reset_empty', $redirect_url );
					wp_redirect( $redirect_url );
					exit;
				}
				// Parameter checks OK, reset password
				reset_password( $user, $_POST['pass1'] );
				wp_redirect( home_url( 'user-login?password=changed' ) );
			} else {
				echo "Ongeldig verzoek.";
			}
			exit;
		}
	}

	/**
	 * A shortcode for rendering the yools dashboard.
	 *
	 * @param  array   $attributes  Shortcode attributes.
	 * @param  string  $content     The text content for shortcode. Not used.
	 *
	 * @return string  The shortcode output
	 */
	public function render_yools_dashboard( $attributes, $content = null ) {
		$default_attributes = array( 'show_title' => false );
		$attributes = shortcode_atts( $default_attributes, $attributes );
		return $this->get_template_html( 'yools_dashboard', $attributes );
	}

}

require 'plugin-update-checker/plugin-update-checker.php';
$myUpdateChecker = Puc_v4_Factory::buildUpdateChecker(
	'https://github.com/yoolsdesign/yools-experience',
	__FILE__,
	'yools-experience'
);
 
// Initialize the plugin
$yools_experience_plugin = new Yools_Experience_Plugin();

// Create the custom pages at plugin activation
register_activation_hook( __FILE__, array( 'Yools_Experience_Plugin', 'plugin_activated' ) );
