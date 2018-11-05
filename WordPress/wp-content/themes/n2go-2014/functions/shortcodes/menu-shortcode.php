<?php

function menu_shortcode_func( $atts )
{
    $a = shortcode_atts( array(
        'menu1' => '',
        'menu2' => '',
        'menu3' => '',
        'menu4' => ''
    ), $atts );

    ob_start();

    ?>
    <div class="n2go-centeredContent n2go-centeredContent-page" style="padding-bottom: 3.5rem;">
        <div class="n2go-grid n2go-featuresGrid" data-num-items-per-row="4">
            <?php

            echo smn_full_navigation_hierarchy( $a['menu1'], 'n2go_all_features_nav_menu_item_output', '<div class="n2go-gridItem"><div class="n2go-featuresGrid_column">', '</div></div>', '<div class="n2go-allFeatures_feature">', '</div>' );
            echo smn_full_navigation_hierarchy( $a['menu2'], 'n2go_all_features_nav_menu_item_output', '<div class="n2go-gridItem"><div class="n2go-featuresGrid_column">', '</div></div>', '<div class="n2go-allFeatures_feature">', '</div>' );
            echo smn_full_navigation_hierarchy( $a['menu3'], 'n2go_all_features_nav_menu_item_output', '<div class="n2go-gridItem"><div class="n2go-featuresGrid_column">', '</div></div>', '<div class="n2go-allFeatures_feature">', '</div>' );
            echo smn_full_navigation_hierarchy( $a['menu4'], 'n2go_all_features_nav_menu_item_output', '<div class="n2go-gridItem"><div class="n2go-featuresGrid_column">', '</div></div>', '<div class="n2go-allFeatures_feature">', '</div>' );

            ?>
        </div>
    </div>
    <?php

    return ob_get_clean();
}

add_shortcode( 'menu_shortcode', 'menu_shortcode_func' );