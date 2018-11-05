<?php

function n2go_individual_pricing_shortcode( $atts)
{

    ob_start();

    $atts = shortcode_atts( array(
        'country' => ''
    ), $atts );


    $language = $atts['country'];

    $output = '<div class="clobs">
                    <div class="flex-container individual-pricing">
                        <div class="flex-item">
                            <div class="container">
                                <div class="step-heading"><div class="step-number">1</div>' . _x( 'How many recipients would you like to reach?', 'pricing step one headline', 'n2go-theme' ) . '</div>
                                <div class="step-body"><div class="input-with-appended-text"><input id="pricing_input_size" size="16" onkeypress="" value="0"></input>' . _x( 'Recipients', 'pricing step one input extension', 'n2go-theme' ) . '</div></div>
                            </div>
                        </div>
                        <div class="flex-item">
                            <div class="container">
                                <div class="step-heading"><div class="step-number">2</div>' . _x( 'How often do you want to send monthly?', 'pricing step two headline', 'n2go-theme' ) . '</div>
                                <div class="step-body">
                                    <input id="pricing_input_repetition_rate" name="thumb-roundness" ngmodel="slider[\'contrast\']" label="" class="form-control ng-valid ng-scope ng-dirty" type="range" min="1" max="4" step="1" value="1" ng-model="slider[\'contrast\']" oninput="pricingRangeValue(this.value)" onchange="pricingRangeValue(this.value)">
                                    <ul class="pricingValues">
                                        <li id="pricingRangeValue1" class="highlight">1</li>
                                        <li id="pricingRangeValue2">2</li>
                                        <li id="pricingRangeValue3">3</li>
                                        <li id="pricingRangeValue4">4</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
        <script>

            var language = "' . $language . '";

            jQuery("#pricing_input_size").bind("input", function(){
                if(!this.value) {
                    jQuery(this).val(0);
                }
                jQuery(this).val(jQuery(this).val().replace(/[^0-9]/g, ""));
                var n = parseInt(jQuery(this).val().split(".").join("").split(",").join(""));
                jQuery(this).val(n.toLocaleString());

                request_individual_pricing();
            });
            jQuery("#pricing_input_repetition_rate").bind("input", function(){
                request_individual_pricing();
            });

            function pricingRangeValue( value ) {
                jQuery("#pricingRangeValue1").removeClass("highlight");
                jQuery("#pricingRangeValue2").removeClass("highlight");
                jQuery("#pricingRangeValue3").removeClass("highlight");
                jQuery("#pricingRangeValue4").removeClass("highlight");

                jQuery("#pricingRangeValue" + value).addClass("highlight");
            }

            function request_individual_pricing() {

                jQuery(".clobs .container-sub-header").css( "display", "-webkit-box" );
                jQuery(".clobs .container-sub-header").css( "display", "-ms-flexbox" );
                jQuery(".clobs .container-sub-header").css( "display", "flex" );

                var url = "https://api.newsletter2go.com/oauth/v2/token";

                var xhr = new XMLHttpRequest();
                xhr.open("POST", url, true);

                var params = {
                  "username": "keller+22@newsletter2go.com",
                  "grant_type": "https://nl2go.com/jwt",
                  "password": "7KNd7goQYCPixfUUPXmb"
                }

                xhr.setRequestHeader("Content-Type", "application/json;charset=UTF-8");
                xhr.setRequestHeader("Authorization", "Basic " + btoa("oxw95ao7_qXTZNB_jrcjxP_5ZUHynuS_zGRx86IW7w:vygrdw3n"));

                xhr.onload = function () {
                    if (xhr.readyState === xhr.DONE) {
                        if (xhr.status === 200) {

                            var response = xhr.responseText;
                            var jsonResponse = JSON.parse(response);

                            var access_token = jsonResponse["access_token"];

                            var types = [
                                "mail",
                                "subscription"
                            ];

                            var size = parseInt(jQuery("#pricing_input_size").val().split(".").join("").split(",").join("")) * jQuery("#pricing_input_repetition_rate").val();

                            types.forEach(function (type, i, o) {

                                if(size > 1000000) {

                                    jQuery("#pricing_output_" + type + "_amount").text("");
                                    jQuery("#pricing_output_" + type + "_quantity").text("");

                                    jQuery("<a/>", {
                                        href: "mailto:sales@newsleter2go.com",
                                        text: "' . _x( 'Request an individual offer', 'pricing individual offer', 'n2go-theme' ) . '",
                                        class: "individual_offer_link"
                                    }).appendTo("#pricing_output_" + type + "_amount");

                                } else {

                                    url = "https://api.newsletter2go.com/payment/price/" + type;
                                    params = {
                                      "size": size,
                                      "country": language
                                    }

                                    var xhr_inner = new XMLHttpRequest();
                                    xhr_inner.open("POST", url, true);

                                    xhr_inner.setRequestHeader("Content-Type", "application/json;charset=UTF-8");
                                    xhr_inner.setRequestHeader("Authorization", "Bearer " + access_token);

                                    xhr_inner.onload = function () {
                                        if (xhr_inner.readyState === xhr_inner.DONE) {
                                            if (xhr_inner.status === 201) {

                                                var innerResponse = xhr_inner.responseText;
                                                var innerJsonResponse = JSON.parse(innerResponse);

                                                var amount = innerJsonResponse["value"][0]["amount"];
                                                var quantity = innerJsonResponse["value"][0]["quantity"];

                                                amount = Math.round(amount * 100) / 100;
                                                amount = amount.toLocaleString("de-DE", {minimumFractionDigits: 2});

                                                var output_amount = amount;

                                                switch (language) {
                                                    case "CH":
                                                        output_amount += " CHF";
                                                        break;
                                                    case "US":
                                                        output_amount = "$" + output_amount;
                                                        break;
                                                    case "GB":
                                                        output_amount = "£" + output_amount;
                                                        break;
                                                    case "NL":
                                                        output_amount = "€ " + output_amount;
                                                        break;
                                                    default:
                                                        output_amount += " €";
                                                }

                                                jQuery("#pricing_output_" + type + "_amount").text(output_amount);
                                                jQuery("#pricing_output_" + type + "_quantity").text(quantity.toLocaleString() + " E-Mails");
                                            }
                                        }
                                    }

                                    xhr_inner.send(JSON.stringify(params));
                                }
                            });
                        }
                    }
                };

                xhr.send(JSON.stringify(params));  
            }
        </script>';

    echo $output;

    return ob_get_clean();
}

add_shortcode( 'n2go_individual_pricing', 'n2go_individual_pricing_shortcode' );