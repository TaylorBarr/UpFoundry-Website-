<form action="<?php echo home_url( '/' ); ?>" class="seacrh-form row" role="search" method="get">

    <div class="col-md-9">

        <input name="s" id="s" type="text" placeholder="<?php _e('Type and press enter to search&#8230;', LANGUAGE_ZONE); ?>" value="">

        <?php if ( defined('ICL_LANGUAGE_CODE') ) : ?>
            <input type="hidden" name="lang" value="<?php echo(ICL_LANGUAGE_CODE); ?>" />
        <?php endif; ?>

    </div>

</form>