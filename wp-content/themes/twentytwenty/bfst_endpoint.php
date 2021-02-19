<?php

/*
* REST API endpoint class
*/
class restAPIendpoint10 {
 
    public function __construct()
    {
        // Add custom REST API endpoint
        add_action('rest_api_init', __NAMESPACE__ . '\\ads_api_route' );
    }
 
    // REST API route
    public function ads_api_route($prefix, $name)
    {
        register_rest_route( $prefix, '/' . $name,
            array(
                'methods'  => 'GET',
                'callback' => [$this, 'get_all_ads_api_endpoint']
            )
        );
    }
 
    // REST API endpoint query for tfs-ads.
    // This will retrive all ads from the site.
    public function get_all_ads_api_endpoint($params)
    {
        $prefix = $params->get_param('prefix'); // Prefix for post ID - default NULL
        $paged = $params->get_param('paged'); // Page number - default 1
        $post_type = $params->get_param('post_type'); // Post types to be pulled - can be comma separated | default post
        $quantity = $params->get_param('quantity'); // Number of posts per page - default -1
 
        $args = array(
            'post_type' => array(
                isset($post_type) ? $post_type : 'post'
            ),
            'orderby' => 'date',
            'post_status' => 'publish',
            'posts_per_page' => isset($quantity) ? $quantity : 1000,
            'paged' => isset($paged) ? $paged : 1,
        );
 
        $feed = array();
        $query = new WP_Query($args);
 
        if($query->have_posts()) {
            while($query->have_posts()) {
 
                $query->the_post();
                $id = get_the_ID();
 
                $taxonomy_names = get_object_taxonomies(isset($post_type) ? $post_type : 'post');
 
                $categories = '';
                $tags = '';
 
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
                                        $categories .= $term->name . '|';
                                    }
                                    if($term->parent != 0) {
                                        $parent = get_category($term->parent);

                                        if($parent->parent != 0) {
                                            $has_parent = get_category($parent->parent);
                                            $has_parent = $has_parent->name;
                                        }

                                        if(!isset($has_parent)) {
                                            $categories .= $parent->name . '|';
                                        } else {
                                            $categories .= $has_parent . '|';
                                        }
                                    }
                                }
                            }
                        }

                        if(!is_taxonomy_hierarchical($taxonomy)) {
                            foreach($terms as $term) {
                                if(!empty($term)) {
                                    $tags .= $term->name . '|';
                                }
                            }
                        }
                        
                    }
 
					$categories = rtrim($categories, '|');
					$tags = rtrim($tags, '|');
 
                }
 
                if(has_post_thumbnail()) {
                    $featured_img = get_the_post_thumbnail_url();
                    $thumbnail = get_the_post_thumbnail_url($id,'thumbnail');
                } else {
                    /*
                    * This is for alternative image if post/article image is not present
                    */
                    $featured_img = get_site_url().'/wp-content/themes/websplash/images/logo.png';
                    $thumbnail = get_site_url().'/wp-content/themes/websplash/images/logo.png';
                }
 
                $content = get_the_content();
                if(empty($content)) {
                    $content = 'No content found';
                }
 
                if(get_post_meta($id, 'tfs_ads_start_date') != NULL ) {
                    $start_date = get_post_meta($id, 'tfs_ads_start_date', true);
                } else {
                    $start_date = get_the_date('Y-m-d') . 'T' . get_the_date('H:i:s') . 'Z';
                }
 
                if(get_post_meta($id, 'tfs_ads_end_date') != NULL ) {
                    $end_date = get_post_meta($id, 'tfs_ads_end_date', true);
                } else {
                    $end_date = '';
                }
 
                $post = (object) [
                    'id' => $prefix . get_the_ID(),
                    'title' => get_the_title(),
                    'start_date' => $start_date,
                    'end_date' => $end_date,
                    'description' => $content,
                    'url' => get_the_permalink(),
                    'main_image' => $featured_img,
                    'website_name' => get_bloginfo('name'),
                    'categories' => $categories,
                    'tags' => $tags,
                    'thumbnail' => $thumbnail,
                ];
 
                array_push($feed, $post);
            }
            wp_reset_postdata();
        }
 
        if(empty($feed)) {
            return new WP_Error( 'empty_category', 'No Posts in the feed!', array('status' => 404) );
        }
 
        $response = new WP_REST_Response($feed);
        $response->set_status(200);
 
        return $response;
    }
}
 
// Function to register our new routes from the controller.
function init_rest_api_endpoint2() {
 
    $endpoints = get_option('_restAPI_endpoints'); // Get all routes
 
    foreach($endpoints as $v) {
        $endpoint = new restAPIendpoint10();
 
        $endpoint->endpointPrefix = $v->prefix;
        $endpoint->endpointName = $v->name;
 
        $endpoint->ads_api_route($endpoint->endpointPrefix, $endpoint->endpointName); // Create each route
    }
}
add_action( 'rest_api_init', 'init_rest_api_endpoint2' );

/*
* Create all routes for the REST API
* You can create as many of endpoints as you like - you will have to change the name for each one - there can't be duplicates!
* The prefix can be also changed if you wish
*/
 
$restAPI[] = (object) array('prefix' => '/blueshift/v1', 'name' => 'test-feed'); // Route /wp-json/blueshift/v1/all-feed

/*
* Don't change lines below
*/
if(get_option('_restAPI_endpoints') != $restAPI) {
    update_option('_restAPI_endpoints', $restAPI); // Save routes
} else {
    add_option('_restAPI_endpoints', $restAPI); // Save routes
}