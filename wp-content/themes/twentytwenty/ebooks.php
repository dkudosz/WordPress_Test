<?php
/**
 * Manage eBooks/listing in the plugin.
 */
class eBooks {

    /**
     * CSS_eBooks constructor.
     */
    public function __construct()
    {
        // Register eBooks custom post type
        add_action( 'init', array( $this, 'register_ebooks_cpt' ), 0 );

        // Add meta boxes for eBooks
        add_action( 'add_meta_boxes', array( $this, 'metaboxes_ebooks_add' ) );
        add_action( 'save_post', array( $this, 'metaboxes_ebooks_save' ), 10, 2);
    }

    /**
     * Register eBooks custom post type
     */
    public function register_ebooks_cpt()
    {
        $labels = array(
            'name'                  => _x( 'eBooks', 'Post Type General Name', 'text_domain' ),
            'singular_name'         => _x( 'eBooks', 'Post Type Singular Name', 'text_domain' ),
            'menu_name'             => __( 'eBooks', 'text_domain' ),
            'name_admin_bar'        => __( 'eBooks', 'text_domain' ),
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
            'label'                 => __( 'eBooks', 'text_domain' ),
            'description'           => __( 'Post Type Description', 'text_domain' ),
            'labels'                => $labels,
            'supports'              => array( 'title' ),
            'taxonomies'            => array(),
            'hierarchical'          => false,
            'public'                => false,
            'show_ui'               => true,
            'show_in_menu'          => true,
            'menu_position'         => 80,
            'menu_icon'             => 'dashicons-book-alt',
            'show_in_admin_bar'     => true,
            'show_in_nav_menus'     => false,
            'can_export'            => true,
            'has_archive'           => false,
            'exclude_from_search'   => true,
            'publicly_queryable'    => true,
            'rewrite'               => true,
            'capability_type'       => 'post',
        );

        register_post_type( 'csmt-eBooks', $args );
    }

    /**
     * Add metabox to eBooks custom post type
     */
    public function metaboxes_ebooks_add()
    {
        add_meta_box( 'csmt_meta_ebooks_box', 'eBook Details', array( $this, 'metaboxes_ebooks_display' ), 'csmt-eBooks', 'normal', 'high' );
    }

    /**
     *  Add a metabox for eBooks page edit screen 
	 * **/
    public function metaboxes_ebooks_display()
    {
        global $post;
        $values = get_post_custom( $post->ID );

        wp_nonce_field( 'csmt_css_ebooks_nonce', 'csmt_css_ebooks_nonce' );
        
        // Value for eBooks unique indentificator
        $eBooks_email_id = isset( $values['csmt_ebooks_email_id'] ) ? esc_attr( $values['csmt_ebooks_email_id'][0] ) : "";
        $eBooks_status = isset( $values['csmt_ebooks_status'] ) ? esc_attr( $values['csmt_ebooks_status'][0] ) : "Active";
        $eBooks_fetch_status = isset( $values['csmt_ebooks_fetch_status'] ) ? esc_attr( $values['csmt_ebooks_fetch_status'][0] ) : "";
        ?>

        <p>Email ID the code was sent to.</p>
        <label for="csmt_ebooks_email_id"><b>Email ID:</b> </label><br />
        <input type="text" name="csmt_ebooks_email_id" id="csmt_ebooks_email_id" value="<?php echo $eBooks_email_id; ?>" >

        <p></p>
        <label for="csmt_ebooks_status"><b>Status:</b> </label><br />
        <input type="text" name="csmt_ebooks_status" id="csmt_ebooks_status" value="<?php echo $eBooks_status;  ?>" >

        <p></p>
        <label for="csmt_ebooks_fetch_status"><b>Fetch Status:</b> </label><br />
        <input type="text" name="csmt_ebooks_fetch_status" id="csmt_ebooks_fetch_status" value="<?php echo $eBooks_fetch_status; ?>" >

        <style>
            
        </style>
        <?php
    }

    /**
     *  Save metabox content for eBooks page edit screen
     **/
    public function metaboxes_ebooks_save( $post_id )
    {
        if ( !isset($_POST['csmt_css_ebooks_nonce']) || !wp_verify_nonce($_POST['csmt_css_ebooks_nonce'], 'csmt_css_ebooks_nonce')){
            error_log('nonce issue');
            return;
        }

        // Bail if we're doing an auto save
        if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
            return;
        }

        // If our current user can't edit this post, bail
        if ( ! current_user_can( 'edit_post' ) ) {
            return;
        }

        extract($_POST, EXTR_IF_EXISTS);

        update_post_meta( $post_id, 'csmt_ebooks_email_id', sanitize_text_field($_POST['csmt_ebooks_email_id']));
        update_post_meta( $post_id, 'csmt_ebooks_status', sanitize_text_field($_POST['csmt_ebooks_status']));
        update_post_meta( $post_id, 'csmt_ebooks_fetch_status', sanitize_text_field($_POST['csmt_ebooks_fetch_status']));
        
    }
}

/**
 * eBooks Endpoint
 */
class customRestApiEndpoint {

    public $bsft_email_id;
    private $auth_key = '9feuCrMTTkrj8KcMpBAbCWy6egrXs6Qv6XsSsMruyZzgwX8vznu6NYHBN3ExHYW4Pq7MHrcFTTtkzA3hGmdqQ5UuJEZm86wDhFUuJRn7ajgY8Q558PwfCvBARTTpWKLa';

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
                'methods'  => 'POST',
                'callback' => [$this, 'get_all_ads_api_endpoint'],
                'permission_callback' => array( $this, 'check_access' )
            )
        );
    }

    // Check the token is valid
    public function check_access(WP_REST_Request $request)
    {
        $key = $request->get_header('auth');
        $body = json_decode($request->get_body());

        if($key == $this->auth_key && isset($body->email_id)){
            $this->bsft_email_id = $body->email_id;
            return true;
        }
        
        return false;
    }

    // REST API endpoint query for tfs-ads.
    // This will retrive all ads from the site.
    public function get_all_ads_api_endpoint($params) 
    {

        $args = array(
            'post_type' => 'csmt-ebooks',
            'post_status' => 'publish',
            'posts_per_page' => 1,
            'meta_query' => array(
                array(
                    'key'     => 'csmt_ebooks_status',
                    'value'   => 'Active',
                ),
            ),
        );

        $feed = array();
        $query = new WP_Query($args);

        if($query->have_posts()) {
            while($query->have_posts()) {

                $query->the_post();
                $id = get_the_ID();

                $post = (object) [
                    'id' => $id,
                    'ebook_code' => get_the_title(),
                ];

                array_push($feed, $post);
            }
            wp_reset_postdata();
        }

        if(empty($feed)) {
            return new WP_Error( 'empty', 'No Posts in the feed!', array('status' => 404) );
        }
        
        $response = new WP_REST_Response($feed);
        $response->set_status(200);
        
        update_post_meta( $response->data[0]->id, 'csmt_ebooks_email_id', $this->bsft_email_id);
        update_post_meta( $response->data[0]->id, 'csmt_ebooks_status', 'Inactive');
        update_post_meta( $response->data[0]->id, 'csmt_ebooks_fetch_status', 'Success');
        
        return $response;
    }
}

// Function to register our new routes from the controller.
function init_rest_api_endpoint() {

    $eBook_endpoint = new customRestApiEndpoint();
    $eBook_endpoint->ads_api_route('ebooks/v1', 'ebook'); // Create each route

}
add_action( 'rest_api_init', 'init_rest_api_endpoint' );

$ebook = new eBooks();