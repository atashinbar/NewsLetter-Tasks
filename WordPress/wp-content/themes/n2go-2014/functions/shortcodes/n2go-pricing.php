<?php

function n2go_pricing_shortcode( $atts)
{

    ob_start();

    $atts = shortcode_atts( array(
        'quantity' => '',
        'type' => '',
        'country' => '',
        'value' => ''
    ), $atts );

    ?>

    <?php
        $quantity = $atts['quantity'];
        $type = $atts['type'];
        $language = $atts['country'];
        $value = $atts['value'];

        $output = get_site_option('n2go_update_pricing_' . $language . '_' . $type . '_' . $quantity . '_' . $value);

        preg_replace('.', ',', $output);

        if ($value == 'cpm') {
            $output = number_format($output, 2, ',' , '.');
        }



        switch ($language) {
            case 'CH':
                $output .= ' CHF';
                break;
            case 'US':
                $output = '$' . $output;
                break;
            case 'GB':
                $output = '£' . $output;
                break;
            case 'NL':
                $output = '€ ' . $output;
                break;
            default:
                $output .= ' €';

        }
    ?>

    <?php

    echo $output;

    return ob_get_clean();
}

add_shortcode( 'n2go_pricing', 'n2go_pricing_shortcode' );