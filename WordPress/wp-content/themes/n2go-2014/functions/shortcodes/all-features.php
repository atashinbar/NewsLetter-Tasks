<?php

function all_features_shortcode()
{
    ob_start();

    ?>
    <div class="n2go-centeredContent n2go-centeredContent-page" style="padding-bottom: 3.5rem;">
        <h2 style="margin-bottom: 2rem;" name="z"><?php echo get_field( 'all_features_headline', 'option' ); ?></h2>
        <div class="n2go-grid n2go-featuresGrid" data-num-items-per-row="4">
            <?php

            echo smn_full_navigation_hierarchy( 'all-features-column-1', 'n2go_all_features_nav_menu_item_output', '<div class="n2go-gridItem"><div class="n2go-featuresGrid_column">', '</div></div>', '<div class="n2go-allFeatures_feature">', '</div>' );
            echo smn_full_navigation_hierarchy( 'all-features-column-2', 'n2go_all_features_nav_menu_item_output', '<div class="n2go-gridItem"><div class="n2go-featuresGrid_column">', '</div></div>', '<div class="n2go-allFeatures_feature">', '</div>' );
            echo smn_full_navigation_hierarchy( 'all-features-column-3', 'n2go_all_features_nav_menu_item_output', '<div class="n2go-gridItem"><div class="n2go-featuresGrid_column">', '</div></div>', '<div class="n2go-allFeatures_feature">', '</div>' );
            echo smn_full_navigation_hierarchy( 'all-features-column-4', 'n2go_all_features_nav_menu_item_output', '<div class="n2go-gridItem"><div class="n2go-featuresGrid_column">', '</div></div>', '<div class="n2go-allFeatures_feature">', '</div>' );

            ?>
        </div>
    </div>
    <?php

    return ob_get_clean();
}

add_shortcode( 'all_features', 'all_features_shortcode' );