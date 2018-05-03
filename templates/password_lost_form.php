<div id="password-lost-form" class="widecolumn">
    <?php if ( $attributes['show_title'] ) : ?>
        <h3><?php _e( 'Forgot Your Password?', 'yools-experience' ); ?></h3>
    <?php endif; ?>
 
    <p class="form-info">
        <?php
            _e(
                "Voer je e-mailadres in en we sturen je een link die je kunt gebruiken om een nieuw wachtwoord te kiezen.",
                'yools-experience'
            );
        ?>
    </p>

    <?php if ( count( $attributes['errors'] ) > 0 ) : ?>
        <?php foreach ( $attributes['errors'] as $error ) : ?>
            <p>
                <?php echo $error; ?>
            </p>
        <?php endforeach; ?>
    <?php endif; ?>
 
    <form id="lostpasswordform" action="<?php echo wp_lostpassword_url(); ?>" method="post">
        <p class="form-row">
            <label for="user_login"><?php _e( 'E-mail', 'yools-experience' ); ?>
            <input type="text" name="user_login" id="user_login">
        </p>
 
        <p class="lostpassword-submit">
            <input type="submit" name="submit" class="lostpassword-button"
                   value="<?php _e( 'Wachtwoord herinstellen', 'yools-experience' ); ?>"/>
        </p>
    </form>
</div>