<?php
// 9a952b7990038502c3a67004dd31a88b
/*
* Blueshift API call to insert article to catalogue
*/

define('BSFT_KEY', '9a952b7990038502c3a67004dd31a88b');
define('BSFT_PREFIX', 'salud_test_');
define('BSFT_CATALOGUE_UUID', '6e411ddd-04ed-4dfb-be9e-7788878a2894');
add_action('publish_post', 'bsft_put_new_post');
add_action('update_post', 'bsft_put_new_post');

add_action('wp', 'bsft_send_post_on_page_load');
function bsft_send_post_on_page_load() {
    
    $type_array = array (
        'post'
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
        $featured_img = get_site_url().'/wp-content/uploads/2019/08/logoWeb.png';
        $thumbnail = get_site_url().'/wp-content/uploads/2019/08/logoWeb.png';
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
            var prefix = 'nyc_';
            var id = <?php echo get_the_ID(); ?>;
            
            window._blueshiftid = '0adb5056cc0035aa55d990eadf1eae57';
            window.blueshift = window.blueshift || [];
            if (blueshift.constructor === Array) {
                blueshift.load = function() {
                    var d = function(a) {
                            return function() {
                                blueshift.push([a].concat(Array.prototype.slice.call(arguments, 0)))
                            }
                        },
                        e = ["identify", "track", "click", "pageload", "capture", "retarget"];
                    for (var f = 0; f < e.length; f++) blueshift[e[f]] = d(e[f])
                };
            }
            blueshift.load();
            blueshift.pageload();
            blueshift.track("view", {
                    product_id: prefix + id
                }
            );
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


