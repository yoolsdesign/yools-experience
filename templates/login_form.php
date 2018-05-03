<div class="login-form-container">
    <?php if ( $attributes['show_title'] ) : ?>
        <h2><?php _e( 'Sign In', 'yools-experience' ); ?></h2>
    <?php endif; ?>

    <!-- Show errors if there are any -->
	<?php if ( count( $attributes['errors'] ) > 0 ) : ?>
	    <?php foreach ( $attributes['errors'] as $error ) : ?>
	        <p class="login-error">
	            <?php echo $error; ?>
	        </p>
	    <?php endforeach; ?>
	<?php endif; ?>

    <!-- Show logged out message if user just logged out -->
    <?php if ( $attributes['logged_out'] ) : ?>
        <p class="login-info">
            <?php _e( 'Je bent afgemeld. Wil je je opnieuw aanmelden?', 'yools-experience' ); ?>
        </p>
    <?php endif; ?>

    <!-- Show registered message if user just got registered -->
    <?php if ( $attributes['registered'] ) : ?>
        <p class="login-info">
            <?php
                printf(
                    __( 'Je hebt je succesvol geregistreerd. We hebben je wachtwoord gemaild naar het e-mailadres dat je hebt ingevoerd.', 'yools-experience' ),
                    get_bloginfo( 'name' )
                );
            ?>
        </p>
    <?php endif; ?>

    <!-- Show password lost message if user just lost his password -->
    <?php if ( $attributes['lost_password_sent'] ) : ?>
        <p class="login-info">
            <?php _e( 'Controleer je e-mail voor een link om uw wachtwoord opnieuw in te stellen.', 'yools-experience' ); ?>
        </p>
    <?php endif; ?>

    <!-- Show password reset message if user just reset his password -->
    <?php if ( $attributes['password_updated'] ) : ?>
        <p class="login-info">
            <?php _e( 'Je wachtwoord is gewijzigd. Je kan nu inloggen.', 'yools-experience' ); ?>
        </p>
    <?php endif; ?>
     
    <?php
        wp_login_form(
            array(
                'label_username' => __( 'E-mail', 'yools-experience' ),
                'label_remember' => __( 'Gegevens onthouden', 'yools-experience' ),
                'label_log_in' => __( 'Inloggen', 'yools-experience' ),
                'redirect' => $attributes['redirect'],
            )
        );
    ?>
     
    <a class="forgot-password" href="<?php echo wp_lostpassword_url(); ?>">
        <?php _e( 'Wachtwoord vergeten?', 'yools-experience' ); ?>
    </a>
</div>