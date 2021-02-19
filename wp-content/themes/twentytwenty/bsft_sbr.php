<?php
/*
* Blueshift API call to insert article to catalogue
*/

define('BSFT_KEY', '824b652543d3f2d889905a7c7ca47d32');
define('BSFT_PREFIX', 'sbr_test_');
define('BSFT_CATALOGUE_UUID', 'aec444d0-4b89-4948-99d0-d9bd537b84e7');

add_action('publish_crypto_profits_extr', 'bsft_put_new_post');
add_action('publish_cycle_trend_forcast', 'bsft_put_new_post');
add_action('publish_income_for_life', 'bsft_put_new_post');
add_action('publish_dynamic_inv_trends', 'bsft_put_new_post');
add_action('publish_ev_profit_alert', 'bsft_put_new_post');
add_action('publish_exp_energy_forecast', 'bsft_put_new_post');
add_action('publish_eef_private_reserve', 'bsft_put_new_post');
add_action('publish_exi_premium', 'bsft_put_new_post');
add_action('publish_exodus_trader', 'bsft_put_new_post');
add_action('publish_frontier_investor', 'bsft_put_new_post');
add_action('publish_gold_membership', 'bsft_put_new_post');
add_action('publish_gold_stock_fort', 'bsft_put_new_post');
add_action('publish_tech_profits_conf', 'bsft_put_new_post');
add_action('publish_ldn_investment_alert', 'bsft_put_new_post');
add_action('publish_microcap_mill', 'bsft_put_new_post');
add_action('publish_new_drug_speculator', 'bsft_put_new_post');
add_action('publish_platinum_membership', 'bsft_put_new_post');
add_action('publish_power_and_profits', 'bsft_put_new_post');
add_action('publish_power_hour_trader', 'bsft_put_new_post');
add_action('publish_brexit_speculator', 'bsft_put_new_post');
add_action('publish_research_investments', 'bsft_put_new_post');
add_action('publish_rev_tech_investor', 'bsft_put_new_post');
add_action('publish_short_the_world', 'bsft_put_new_post');
add_action('publish_southbank_daily', 'bsft_put_new_post');
add_action('publish_strategic_int', 'bsft_put_new_post');
add_action('publish_the_dividend_letter', 'bsft_put_new_post');
add_action('publish_zero_hour_alert', 'bsft_put_new_post');
add_action('publish_fleet_street_letter', 'bsft_put_new_post');
add_action('publish_the_price_report', 'bsft_put_new_post');
add_action('publish_trigger_point_trader', 'bsft_put_new_post');
add_action('publish_uk_ind_wealth', 'bsft_put_new_post');

add_action('update_crypto_profits_extr', 'bsft_put_new_post');
add_action('update_cycle_trend_forcast', 'bsft_put_new_post');
add_action('update_income_for_life', 'bsft_put_new_post');
add_action('update_dynamic_inv_trends', 'bsft_put_new_post');
add_action('update_ev_profit_alert', 'bsft_put_new_post');
add_action('update_exp_energy_forecast', 'bsft_put_new_post');
add_action('update_eef_private_reserve', 'bsft_put_new_post');
add_action('update_exi_premium', 'bsft_put_new_post');
add_action('update_exodus_trader', 'bsft_put_new_post');
add_action('update_frontier_investor', 'bsft_put_new_post');
add_action('update_gold_membership', 'bsft_put_new_post');
add_action('update_gold_stock_fort', 'bsft_put_new_post');
add_action('update_tech_profits_conf', 'bsft_put_new_post');
add_action('update_ldn_investment_alert', 'bsft_put_new_post');
add_action('update_microcap_mill', 'bsft_put_new_post');
add_action('update_new_drug_speculator', 'bsft_put_new_post');
add_action('update_platinum_membership', 'bsft_put_new_post');
add_action('update_power_and_profits', 'bsft_put_new_post');
add_action('update_power_hour_trader', 'bsft_put_new_post');
add_action('update_brexit_speculator', 'bsft_put_new_post');
add_action('update_research_investments', 'bsft_put_new_post');
add_action('update_rev_tech_investor', 'bsft_put_new_post');
add_action('update_short_the_world', 'bsft_put_new_post');
add_action('update_southbank_daily', 'bsft_put_new_post');
add_action('update_strategic_int', 'bsft_put_new_post');
add_action('update_the_dividend_letter', 'bsft_put_new_post');
add_action('update_zero_hour_alert', 'bsft_put_new_post');
add_action('update_fleet_street_letter', 'bsft_put_new_post');
add_action('update_the_price_report', 'bsft_put_new_post');
add_action('update_trigger_point_trader', 'bsft_put_new_post');
add_action('update_uk_ind_wealth', 'bsft_put_new_post');


add_action('wp', 'bsft_send_post_on_page_load');
function bsft_send_post_on_page_load() {

    $type_array = array (
        'crypto_profits_extr',
        'cycle_trend_forcast',
        'income_for_life',
        'dynamic_inv_trends',
        'ev_profit_alert',
        'exp_energy_forecast',
        'eef_private_reserve',
        'exi_premium',
        'exodus_trader',
        'frontier_investor',
        'gold_membership',
        'gold_stock_fort',
        'tech_profits_conf',
        'ldn_investment_alert',
        'microcap_mill',
        'new_drug_speculator',
        'platinum_membership',
        'power_and_profits',
        'power_hour_trader',
        'brexit_speculator',
        'research_investments',
        'rev_tech_investor',
        'short_the_world',
        'southbank_daily',
        'strategic_int',
        'the_dividend_letter',
        'zero_hour_alert',
        'fleet_street_letter',
        'the_price_report',
        'trigger_point_trader',
        'uk_ind_wealth'
    );

    if (in_array(get_post_type(), $type_array) && is_singular()) {

        global $post;
        $post_flag = get_option('bsft_post_added');

        if($post_flag == true || $post_flag == 1) {
            return;
        } else {
            bsft_put_new_post($post->ID);
        }
    }
}

function bsft_put_new_post($id) {
    
    $key = base64_encode(BSFT_KEY);
    $url = 'https://api.getblueshift.com/api/v1/catalogs/' . BSFT_CATALOGUE_UUID . '.json';

    $post = get_post($id);
    $author = $post->post_author;
    $name = get_the_author_meta('display_name', $author);
    $title = $post->post_title;
    $permalink = get_permalink($id);
    $content = wp_strip_all_tags($post->post_content);
    $status = $post->post_status;
    $excerpt = wp_strip_all_tags($post->post_excerpt);
    $date = date('Y-m-dTH:i:sZ', strtotime($post->post_date));
    $taxonomy_names = get_object_taxonomies($post->post_type);
    $post_type_obj = get_post_type_object($post->post_type);
    $post_type_name = $post_type_obj->labels->name;
 
    $categories = array();
    $tags = array();

    if(!empty($taxonomy_names)) {
        foreach($taxonomy_names as $taxonomy) {
            
            $terms = wp_get_post_terms($id, $taxonomy, array(
                'fields' => 'all'
                )
            );

            if(is_taxonomy_hierarchical($taxonomy)) {
                
                foreach($terms as $term) {
                    if(!empty($term)) {
                        if($term->parent == 0) {
                            array_push($categories, $term->name);
                        }
                        if($term->parent != 0) {
                            $parent = get_category($term->parent);

                            if($parent->parent != 0) {
                                $has_parent = get_category($parent->parent);
                                $has_parent = $has_parent->name;
                            }

                            if(isset($has_parent)) {
                                array_push($categories, $has_parent . ' > ' . $parent->name . ' > ' . $term->name);
                            }
                            
                            if(!isset($has_parent)) {
                                array_push($categories, $parent->name . ' > ' . $term->name);
                            }
                        }
                    }
                }
                
            }

            if(!is_taxonomy_hierarchical($taxonomy)) {
                foreach($terms as $term) {
                    if(!empty($term)) {
                        array_push($tags, $term->name);
                    }
                }
            }
            
        }

    }

    $post_pubcodes = wp_get_post_terms($id, 'pubcode');
    
    if(is_array($post_pubcodes)) {
        foreach($post_pubcodes as $code) {
            $pub_code = $code->name;
            array_push($tags, $pub_code);
        }
    }

    if(has_post_thumbnail($id)) {
        $featured_img = get_the_post_thumbnail_url($id);
        $thumbnail = get_the_post_thumbnail_url($id,'thumbnail');
    } else {
        $featured_img = get_site_url().'/wp-content/themes/southbankresearch/img/logo.png';
        $thumbnail = get_site_url().'/wp-content/themes/southbankresearch/img/logo.png';
    }

    $body = array(
        'catalog' => (object) [
            'products' => array(
                (object) [
                    'product_id' => BSFT_PREFIX . $id,
                    'title' => $title,
                    'brand' => get_bloginfo('name'),
                    'image' => $featured_img,
                    'thumbnail' => $thumbnail,
                    'availability' => 'in stock',
                    'web_link' => $permalink,
                    'category' => $categories,
                    'tags' => $tags,
                    'description' => $content,
                    'start_date' => $date,
                    'author' => $name,
                    'status' => $status,
                    'excerpt' => $excerpt,
                    'post_type' => $post_type_name
                ]
            )
        ]
    );

    $headers = array(
        'Authorization' => 'Basic '.$key,
        'Content-type' => 'application/json'
    );

    $args = array(
        'headers' => $headers,
        'body' => json_encode($body, JSON_HEX_QUOT | JSON_HEX_TAG | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES ),
        'method' => 'PUT',
    );
    
    $response = wp_remote_post($url, $args);
    $response = json_decode($response['body']);
    
    if($response->status == 'ok') {
        update_post_meta($id,'bsft_post_added', true);
    }
    
}