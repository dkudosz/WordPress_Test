<?php

/*
* Blueshift API call to insert article to catalogue
*/

define('BSFT_KEY', '8b8c57e88ae362295c99026f428bd31d');
define('BSFT_PREFIX', 'local_');
define('BSFT_CATALOGUE_UUID', 'b728d747-0eec-46e3-a004-f0916fa678c7');
add_action('publish_post', 'bsft_put_new_post');
add_action('update_post', 'bsft_put_new_post');

add_action('wp', 'bsft_send_post_on_page_load');
function bsft_send_post_on_page_load() {
    if (get_post_type() == 'post' && is_singular()) {

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

    // UTC time
    date_default_timezone_set('UTC');

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

    if(has_post_thumbnail($id)) {
        $featured_img = get_the_post_thumbnail_url($id);
        $thumbnail = get_the_post_thumbnail_url($id,'thumbnail');
    } else {
        $featured_img = get_site_url().'/wp-content/themes/websplash/images/av_logo.png';
        $thumbnail = get_site_url().'/wp-content/themes/websplash/images/av_logo.png';
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

//load blueshift view event
function blueshift_script() {
    ?>
        <script>

        const getDeviceType = () => {
        const ua = navigator.userAgent;

        if (/(tablet|ipad|playbook|silk)|(android(?!.*mobi))/i.test(ua)) {
            return "tablet";
        }
        if (
            /Mobile|iP(hone|od)|Android|BlackBerry|IEMobile|Kindle|Silk-Accelerated|(hpw|web)OS|Opera M(obi|ini)/.test(
            ua
            )
        ) {
            return "mobile";
        }

        return "desktop";

        };


            fetch('https://api.getblueshift.com/live', {
                method: 'POST',
                mode: 'no-cors',
                headers: {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                    'Authorization': 'Basic OGI4YzU3ZTg4YWUzNjIyOTVjOTkwMjZmNDI4YmQzMWQ6'
                },
                body: {
                        'user': {
                            'cookie': 'ca1e333f-6e50-8488-3b55-cd5dedbf4a5d'
                        },
                        'slot': 'Live_Content_JSON_test',
                        'api_key': '9eb4d84117639dbcad65e95f0da11047'
                    }
            })
            .then(response => {
                console.log(response);
            })
            .catch(err => {
                console.error(err);
            });


            var prefix = 'local_';
            var id = <?php echo get_the_ID(); ?>;
            
            window._blueshiftid = '9eb4d84117639dbcad65e95f0da11047';
            window.blueshift = window.blueshift || [];
            if (blueshift.constructor === Array) {
                blueshift.load = function() {
                    var d = function(a) {
                            return function() {
                                blueshift.push([a].concat(Array.prototype.slice.call(arguments, 0)))
                            }
                        },
                        e = ["identify", "track", "click", "pageload", "capture", "retarget", "live"];
                    for (var f = 0; f < e.length; f++) blueshift[e[f]] = d(e[f])
                };
            }
            blueshift.load();
            blueshift.pageload();
            blueshift.track("view", {
                product_id: prefix + id
            }
            );
            blueshift.track("device_type", {
                device_type: getDeviceType(),
            });
            if (blueshift.constructor === Array) {
                (function() {
                    var b = document.createElement("script");
                    b.type = "text/javascript", b.async = !0,
                        b.src = ("https:" === document.location.protocol ? "https:" : "http:") + "//cdn.getblueshift.com/blueshift.js";
                    var c = document.getElementsByTagName("script")[0];
                    c.parentNode.insertBefore(b, c);
                })()
            }

            
        </script>
        
    <?php
    }
add_action('wp_footer', 'blueshift_script');


define('BSFT_USER_API_KEY', '8b8c57e88ae362295c99026f428bd31d');
define('BSFT_EVENT_API_KEY', '9eb4d84117639dbcad65e95f0da11047');
add_shortcode('bsft_live_content', 'bsft_get_live_content');
function bsft_get_live_content($atts) {

    $atts = shortcode_atts( 
        array(
            'slot' => '',
            'block' => '',
            'block_item' => '',
            'container_class' => '',
            'alt_slot' => '',
            'alt_block' => '',
            'alt_block_item' => '',
        ), $atts, 'bsft_live_content'
    );

    $slot = $atts['slot'];
    $block = 'block'.$atts['block'];
    $block_item = intval($atts['block_item']) - 1;

    if(isset($_COOKIE['_bs'])) {
        $cookie = $_COOKIE['_bs'];
    } else {
        $cookie = NULL;
    }

    $response = bsft_lc_api_call($cookie, $slot);

    if(count($response->content->{$block}) == 0) {
        $slot = $atts['alt_slot'];
        $block = 'block'.$atts['alt_block'];
        $block_item = intval($atts['alt_block_item']) - 1;

        $response = bsft_lc_api_call($cookie, $slot);
    }

    $title = $response->content->{$block}[$block_item]->name;
    $url = $response->content->{$block}[$block_item]->url;
    $image_url = $response->content->{$block}[$block_item]->image_url;
    $thumbnail_url = $response->content->{$block}[$block_item]->thumbnail_url;
    $author = $response->content->{$block}[$block_item]->author;
    $categories = $response->content->{$block}[$block_item]->categories;
    $tags = $response->content->{$block}[$block_item]->tags;
    $start_date = $response->content->{$block}[$block_item]->start_date;
    $end_date = $response->content->{$block}[$block_item]->end_date;
    $desc = $response->content->{$block}[$block_item]->description;

    $html = '<div id="bsft_live_content" class="'.esc_html($atts['container_class']).'">
        <div style="width:50%; display:inline-block; padding:10px 0; float:left;">'.$title.'</div>
        <div class="exit">Exit</div>
        <div style="width:100%; height:280px; display:block;">
            <a href="'.$url.'">
                <img src="'.$image_url.'" style="width:100%; height:100%; display:block; object-fit:cover;">
            </a>
        </div>
        <div style="width:100%; display:block; padding:10px 0;">Author: '.$author.'</div>
    </div>';

    return $html;
}

function bsft_lc_api_call($cookie, $slot) {

    $key = base64_encode(BSFT_USER_API_KEY);
    $url = 'https://api.getblueshift.com/live';

    $body = array(
        'user' => array(
            'cookie' => $cookie,
        ),
        'slot' => $slot,
        'api_key' => BSFT_EVENT_API_KEY
    );
    
    $headers = array(
        'Authorization' => 'Basic '.$key,
        'Content-type' => 'application/json',
        'Accept' => 'application/json'
    );

    $args = array(
        'headers' => $headers,
        'body' => json_encode($body),
        'method' => 'POST',
    );
    
    $response = wp_remote_post($url, $args);
    $response = json_decode($response['body']);

    return $response;
}

add_action('wp_head', 'popup_thing');
function popup_thing() {
    ?>
    <style>
        .my_popup {
            background-color: rgba(172, 172, 172, 0.75);
            position: fixed;
            height: 100vh;
            width: 100%;
            z-index: 999;
            display: none;
            justify-content: center;
            align-items: center;
        }
        .testing_lc {
            width: 500px;
            max-width: 100%;
            height: 400px;
            max-height: 100%;
            margin: auto auto;
            padding: 20px;
            border-radius: 30px;
            background-color: #fff;
            display: block;
        }
        .exit {
            width:50%; 
            display:inline-block; 
            padding:10px 0; 
            text-align:right;
            cursor: pointer;
        }
        .exit_popup {
            cursor: pointer;
        }

    </style>
    <script>
        jQuery(document).ready(function($) {
            
            $('.exit_popup').on('click', function(){
                $('.my_popup').show();
                $('.my_popup').css('display', 'flex');
            });
            $('.exit').on('click', function(){
                $('.my_popup').hide();
            });

        });
    </script>
    <div class="my_popup">
    
        <?php echo do_shortcode('[bsft_live_content slot="Live_Content_JSON_test" block="1" block_item="2" container_class="testing_lc" alt_slot="Live_Content_JSON_test_2" alt_block="2" alt_block_item="1"] '); ?>
    
    </div>
    <?php
}

