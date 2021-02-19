<?php
/**
 * Manage ads/listing in the plugin.
 */
class adsPosts {

    /**
     * CSS_Ads constructor.
     */
    public function __construct()
    {
        // Register ads custom post type
        add_action( 'init', array( $this, 'register_ads_cpt' ), 0 );

        // Register ads custom category and tag
        add_action( 'init', array( $this, 'tfs_ads_taxonomy' ), 0 );
        add_action( 'init', array( $this, 'tfs_ads_tags' ), 0 );

        // Add meta boxes for Ads
        add_action( 'add_meta_boxes', array( $this, 'metaboxes_ads_add' ) );
        add_action( 'save_post', array( $this, 'metaboxes_ads_save' ), 11, 3);

    }

    /**
     * Register ads custom post type
     */
    public function register_ads_cpt()
    {
        $labels = array(
            'name'                  => _x( 'Ads', 'Post Type General Name', 'text_domain' ),
            'singular_name'         => _x( 'Ads', 'Post Type Singular Name', 'text_domain' ),
            'menu_name'             => __( 'Ads', 'text_domain' ),
            'name_admin_bar'        => __( 'Advertisements', 'text_domain' ),
            'archives'              => __( 'Item Archives', 'text_domain' ),
            'parent_item_colon'     => __( 'Parent Item:', 'text_domain' ),
            'all_items'             => __( 'All Items', 'text_domain' ),
            'add_new_item'          => __( 'Add New Item', 'text_domain' ),
            'add_new'               => __( 'Add New', 'text_domain' ),
            'new_item'              => __( 'New Item', 'text_domain' ),
            'edit_item'             => __( 'Edit Item', 'text_domain' ),
            'update_item'           => __( 'Update Item', 'text_domain' ),
            'view_item'             => __( 'View Item', 'text_domain' ),
            'search_items'          => __( 'Search Item', 'text_domain' ),
            'not_found'             => __( 'Not found', 'text_domain' ),
            'not_found_in_trash'    => __( 'Not found in Trash', 'text_domain' ),
            'featured_image'        => __( 'Featured Image', 'text_domain' ),
            'set_featured_image'    => __( 'Set featured image', 'text_domain' ),
            'remove_featured_image' => __( 'Remove featured image', 'text_domain' ),
            'use_featured_image'    => __( 'Use as featured image', 'text_domain' ),
            'insert_into_item'      => __( 'Insert into item', 'text_domain' ),
            'uploaded_to_this_item' => __( 'Uploaded to this item', 'text_domain' ),
            'items_list'            => __( 'Items list', 'text_domain' ),
            'items_list_navigation' => __( 'Items list navigation', 'text_domain' ),
            'filter_items_list'     => __( 'Filter items list', 'text_domain' ),
        );

        $args = array(
            'label'                 => __( 'Ads', 'text_domain' ),
            'description'           => __( 'Post Type Description', 'text_domain' ),
            'labels'                => $labels,
            'supports'              => array( 'title', 'editor', 'thumbnail' ),
            'taxonomies'            => array( 'tfs-ads-taxonomy', 'tfs-ads-tag' ),
            'hierarchical'          => false,
            'public'                => false,
            'show_ui'               => true,
            'show_in_menu'          => true,
            'menu_position'         => 80,
            'menu_icon'             => 'dashicons-email',
            'show_in_admin_bar'     => true,
            'show_in_nav_menus'     => false,
            'can_export'            => true,
            'has_archive'           => false,
            'exclude_from_search'   => true,
            'publicly_queryable'    => true,
            'rewrite'               => true,
            'capability_type'       => 'post',
        );

        register_post_type( 'tfs-ads', $args );
    }

    // Register Custom Category
    public function tfs_ads_taxonomy() 
    {

        $labels = array(
            'name'                       => _x( 'Ads Categories', 'Taxonomy General Name', 'text_domain' ),
            'singular_name'              => _x( 'Ads Category', 'Taxonomy Singular Name', 'text_domain' ),
            'menu_name'                  => __( 'Ads Category', 'text_domain' ),
            'all_items'                  => __( 'All Items', 'text_domain' ),
            'parent_item'                => __( 'Parent Item', 'text_domain' ),
            'parent_item_colon'          => __( 'Parent Item:', 'text_domain' ),
            'new_item_name'              => __( 'New Item Name', 'text_domain' ),
            'add_new_item'               => __( 'Add New Item', 'text_domain' ),
            'edit_item'                  => __( 'Edit Item', 'text_domain' ),
            'update_item'                => __( 'Update Item', 'text_domain' ),
            'view_item'                  => __( 'View Item', 'text_domain' ),
            'separate_items_with_commas' => __( 'Separate items with commas', 'text_domain' ),
            'add_or_remove_items'        => __( 'Add or remove items', 'text_domain' ),
            'choose_from_most_used'      => __( 'Choose from the most used', 'text_domain' ),
            'popular_items'              => __( 'Popular Items', 'text_domain' ),
            'search_items'               => __( 'Search Items', 'text_domain' ),
            'not_found'                  => __( 'Not Found', 'text_domain' ),
            'no_terms'                   => __( 'No items', 'text_domain' ),
            'items_list'                 => __( 'Items list', 'text_domain' ),
            'items_list_navigation'      => __( 'Items list navigation', 'text_domain' ),
        );
        $args = array(
            'labels'                     => $labels,
            'hierarchical'               => true,
            'public'                     => false,
            'show_ui'                    => true,
            'show_admin_column'          => true,
            'show_in_nav_menus'          => true,
            'show_tagcloud'              => true,
        );
        register_taxonomy( 'tfs-ads-taxonomy', array( 'tfs-ads' ), $args );

    }

    // Register Custom Tag
    public function tfs_ads_tags() 
    {

        $labels = array(
            'name'                       => _x( 'Ads Tags', 'Taxonomy General Name', 'text_domain' ),
            'singular_name'              => _x( 'Ads Tag', 'Taxonomy Singular Name', 'text_domain' ),
            'menu_name'                  => __( 'Ads Tags', 'text_domain' ),
            'all_items'                  => __( 'All Items', 'text_domain' ),
            'parent_item'                => __( 'Parent Item', 'text_domain' ),
            'parent_item_colon'          => __( 'Parent Item:', 'text_domain' ),
            'new_item_name'              => __( 'New Item Name', 'text_domain' ),
            'add_new_item'               => __( 'Add New Item', 'text_domain' ),
            'edit_item'                  => __( 'Edit Item', 'text_domain' ),
            'update_item'                => __( 'Update Item', 'text_domain' ),
            'view_item'                  => __( 'View Item', 'text_domain' ),
            'separate_items_with_commas' => __( 'Separate items with commas', 'text_domain' ),
            'add_or_remove_items'        => __( 'Add or remove items', 'text_domain' ),
            'choose_from_most_used'      => __( 'Choose from the most used', 'text_domain' ),
            'popular_items'              => __( 'Popular Items', 'text_domain' ),
            'search_items'               => __( 'Search Items', 'text_domain' ),
            'not_found'                  => __( 'Not Found', 'text_domain' ),
            'no_terms'                   => __( 'No items', 'text_domain' ),
            'items_list'                 => __( 'Items list', 'text_domain' ),
            'items_list_navigation'      => __( 'Items list navigation', 'text_domain' ),
        );
        $args = array(
            'labels'                     => $labels,
            'hierarchical'               => false,
            'public'                     => false,
            'show_ui'                    => true,
            'show_admin_column'          => true,
            'show_in_nav_menus'          => true,
            'show_tagcloud'              => true,
        );
        register_taxonomy( 'tfs-ads-tag', array( 'tfs-ads' ), $args );

    }

    /**
     * Add metabox to ads custom post type
     */
    public function metaboxes_ads_add()
    {
        add_meta_box( 'tfs_meta_ads_box', 'Ads Unique Identifier', array( $this, 'metaboxes_ads_display' ), 'tfs-ads', 'normal', 'high' );
    }

    /**
     *  Add a metabox for ads page edit screen 
	 * **/
    public function metaboxes_ads_display()
    {
        global $post;
        $values = get_post_custom( $post->ID );

        wp_nonce_field( 'tfs_css_ads_nonce', 'tfs_css_ads_nonce' );

        // Value for ads unique indentificator
        $ads_url = isset( $values['tfs_ads_url'] ) ? esc_attr( $values['tfs_ads_url'][0] ) : "";
        $ads_start_date = isset( $values['tfs_ads_start_date'] ) ? esc_attr( $values['tfs_ads_start_date'][0] ) : "";
        $ads_end_date = isset( $values['tfs_ads_end_date'] ) ? esc_attr( $values['tfs_ads_end_date'][0] ) : "";
        ?>

        <p>Please enter the advertisement URL</p>
        <label for="tfs_ads_url"><b>URL:</b> </label><br />
        <input name="tfs_ads_url" id="tfs_ads_url" value="<?php echo $ads_url; ?>">

        <p>Please enter the Start Date for this advertisement</p>
        <label for="tfs_ads_start_date"><b>Start Date:</b> </label><br />
        <input type="datetime-local" name="tfs_ads_start_date" id="tfs_ads_start_date" value="<?php echo $ads_start_date; ?>">

        <p>Please enter the End Date for this advertisement</p>
        <label for="tfs_ads_end_date"><b>End Date:</b> </label><br />
        <input type="datetime-local" name="tfs_ads_end_date" id="tfs_ads_end_date" value="<?php echo $ads_end_date; ?>">

        <style>
            #agora-pubcode-picker{ display:none; }
        </style>
        <?php
    }

    /**
     *  Save metabox content for ads page edit screen
     **/
    public function metaboxes_ads_save( $post_id )
    {
        // Bail if we're doing an auto save
        if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
            return;
        }

        // If our current user can't edit this post, bail
        if ( ! current_user_can( 'edit_post' ) ) {
            return;
        }

        // Save ads url
        if ( isset( $_POST['tfs_ads_url'] ) ) {
            $ads_url = sanitize_text_field( $_POST['tfs_ads_url']);
            update_post_meta( $post_id, 'tfs_ads_url', $ads_url);
        }

        // Save ads start date
        if ( isset( $_POST['tfs_ads_start_date'] ) ) {
            $ads_start_date = sanitize_text_field($_POST['tfs_ads_start_date']);
            update_post_meta( $post_id, 'tfs_ads_start_date', $ads_start_date);
        }

        // Save ads end date
        if ( isset( $_POST['tfs_ads_end_date'] ) ) {
            $ads_end_date = sanitize_text_field($_POST['tfs_ads_end_date']);
            update_post_meta( $post_id, 'tfs_ads_end_date', $ads_end_date);
        }
    }
}

/*
* REST API endpoint class
*/
class restAPIendpoint1 {

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
            'post_status' => 'publish',
            'posts_per_page' => isset($quantity) ? $quantity : -1,
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
                                        $categories .= $term->name . ', ';
                                    }
                                    if($term->parent != 0) {
                                        $parent = get_category($term->parent);

                                        if($parent->parent != 0) {
                                            $has_parent = get_category($parent->parent);
                                            $has_parent = $has_parent->name;
                                        }

                                        if(isset($has_parent)) {
                                            $categories .= $has_parent . ' > ' . $parent->name . ' > ' . $term->name . ', ';
                                        } else {
                                            $categories .= $parent->name . ' > ' . $term->name . ', ';
                                        }
                                    }
                                }
                            }
                        }

                        if(!is_taxonomy_hierarchical($taxonomy)) {
                            foreach($terms as $term) {
                                if(!empty($term)) {
                                    $tags .= $term->name . ',';
                                }
                            }
                        }
                        
                    }

                    $categories = preg_replace('/\+|,+\s*$/', '', $categories);
                    $tags = preg_replace('/\+|,+\s*$/', '', $tags);

                }

                if(has_post_thumbnail()) {
                    $featured_img = get_the_post_thumbnail_url();
                    $thumbnail = get_the_post_thumbnail_url($id,'thumbnail');
                } else {
                    $featured_img = get_site_url().'/wp-content/themes/websplash/images/av_logo.png';
                    $thumbnail = get_site_url().'/wp-content/themes/websplash/images/av_logo.png';
                }

                $content = get_the_content();
                if(empty($content)) {
                    $content = 'No content found';
                }

                if(get_post_meta($id, 'tfs_ads_start_date') != NULL ) {
                    $start_date = get_post_meta($id, 'tfs_ads_start_date', true);
                } else {
                    $start_date = get_the_date('Y-m-dTH:i:sZ');
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
function init_rest_api_endpoint() {

    $endpoints = get_option('_restAPI_endpoints'); // Get all routes

    foreach($endpoints as $v) {
        $endpoint = new restAPIendpoint1();

        $endpoint->endpointPrefix = $v->prefix;
        $endpoint->endpointName = $v->name;

        $endpoint->ads_api_route($endpoint->endpointPrefix, $endpoint->endpointName); // Create each route
    }
}
add_action( 'rest_api_init', 'init_rest_api_endpoint' );

/*
* Init Ads post type
*/
$ads = new adsPosts();

/*
* Create all routes for the REST API
*/
$restAPI[] = (object) array('prefix' => '/blueshift/v1', 'name' => 'articles'); // Route /wp-json/blueshift/v1/ads

if(get_option('_restAPI_endpoints') != $restAPI) {
    update_option('_restAPI_endpoints', $restAPI); // Save routes
} else {
    add_option('_restAPI_endpoints', $restAPI); // Save routes
}