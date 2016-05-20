<?php
/**
 * @author Vlad Mustiata
 * @team StylishThemes
 */

// File Security Check
if ( ! defined( 'ABSPATH' ) ) { exit; }
global $clx_data;
?>

    <!-- Page Footer Bottom -->
    <!-- Toggle Class 'light' to change layout color -->
    <div class="page-footer-bottom <?php if(is_single()) { echo 'light'; } ?>">
        <div class="container">
            <div class="row">
                <div class="col-sm-12">
                    <div class="row">
                        <div class="col-sm-6">
                            <p>
                                <?php echo wp_kses_post(get_theme_mod( 'footer_copyright' )); ?>
                            </p>
                        </div>
                        <div class="col-sm-6">
                            <a href="body" data-easing="easeInOutQuint" data-scroll="" data-speed="600" data-url="false" class="go-to-top-button">
                                <i class="fa fa-angle-up"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php wp_footer(); ?>

</body>
</html>