<?php
/**
 * Custom Post Types
 */
class cpt_PostTypes {

    // CPT vars
    public $singular_post_name;
    public $plural_post_name;
    public $post_type_key;
    public $post_type_description;
    public $post_type_text_domain;
    public $post_type_menu_position;
    public $admin_sidebar_icon;

    // Category and Tag vars
    public $post_type_category;
    public $post_type_category_name_singular;
    public $post_type_category_name_plural;
    public $post_type_tag;
    public $post_type_tag_name_singular;
    public $post_type_tag_name_plural;

    // Metaboxes vars
    public $metabox_key;
    public $metabox_name;
    public $meta_array;

    public function __construct()
    {
        // Register custom post type
        add_action( 'init', array( $this, 'register__cpt' ), 0 );

        // Register CPT custom category and tag
        add_action( 'init', array( $this, 'cpt_create_taxonomy' ), 0 );
        add_action( 'init', array( $this, 'cpt_create_tags' ), 0 );

        // Add meta boxes for CPT
        add_action( 'add_meta_boxes', array( $this, 'metaboxes_cpt_add' ) );
        add_action( 'save_post' , array( $this, 'metaboxes_cpt_save' ), 11, 3);

    }

    /**
     * Register ads custom post type
     */
    public function register__cpt()
    {
        $labels = array(
            'name'                  => _x( $this->plural_post_name, 'Post Type General Name', 'text_domain' ),
            'singular_name'         => _x( $this->singular_post_name, 'Post Type Singular Name', 'text_domain' ),
            'menu_name'             => __( $this->plural_post_name, 'text_domain' ),
            'name_admin_bar'        => __( $this->plural_post_name, 'text_domain' ),
            'archives'              => __( 'Item Archives', 'text_domain' ),
            'parent_item_colon'     => __( 'Parent Item:', 'text_domain' ),
            'all_items'             => __( 'All Items', 'text_domain' ),
            'add_new_item'          => __( 'Add New '.$this->singular_post_name, 'text_domain' ),
            'add_new'               => __( 'Add New '.$this->singular_post_name, 'text_domain' ),
            'new_item'              => __( 'New Item', 'text_domain' ),
            'edit_item'             => __( 'Edit '.$this->singular_post_name, 'text_domain' ),
            'update_item'           => __( 'Update '.$this->singular_post_name, 'text_domain' ),
            'view_item'             => __( 'View '.$this->singular_post_name, 'text_domain' ),
            'search_items'          => __( 'Search', 'text_domain' ),
            'not_found'             => __( 'Not found', 'text_domain' ),
            'not_found_in_trash'    => __( 'Not found in Trash', 'text_domain' ),
            'featured_image'        => __( 'Featured Image', 'text_domain' ),
            'set_featured_image'    => __( 'Set featured image', 'text_domain' ),
            'remove_featured_image' => __( 'Remove featured image', 'text_domain' ),
            'use_featured_image'    => __( 'Use as featured image', 'text_domain' ),
            'insert_into_item'      => __( 'Insert into '.$this->singular_post_name, 'text_domain' ),
            'uploaded_to_this_item' => __( 'Uploaded to this '.$this->singular_post_name, 'text_domain' ),
            'items_list'            => __( 'Items list', 'text_domain' ),
            'items_list_navigation' => __( 'Items list navigation', 'text_domain' ),
            'filter_items_list'     => __( 'Filter items list', 'text_domain' ),
        );

        $args = array(
            'label'                 => __( $this->singular_post_name, 'text_domain' ),
            'description'           => __( $this->post_type_description, 'text_domain' ),
            'labels'                => $labels,
            'supports'              => array('title', 'editor', 'excerpt', 'thumbnail', 'revisions', 'author', 'page-attributes', 'post-formats', 'custom-fields'),
            'taxonomies'            => array( $this->post_type_category, $this->post_type_tag ),
            'hierarchical'          => true,
            'public'                => true,
            'show_ui'               => true,
            'show_in_menu'          => true,
            'menu_position'         => $this->post_type_menu_position,
            'menu_icon'             => $this->admin_sidebar_icon,
            'show_in_admin_bar'     => true,
            'show_in_nav_menus'     => true,
            'can_export'            => true,
            'has_archive'           => true,
            'exclude_from_search'   => true,
            'publicly_queryable'    => true,
            'rewrite'               => true,
            'capability_type'       => 'post',
        );

        register_post_type( $this->post_type_key, $args );
    }

    // Register Custom Category
    public function cpt_create_taxonomy() 
    {

        $labels = array(
            'name'                       => _x( $this->post_type_category_name_plural, 'Taxonomy General Name', 'text_domain' ),
            'singular_name'              => _x( $this->post_type_category_name_singular, 'Taxonomy Singular Name', 'text_domain' ),
            'menu_name'                  => __( $this->post_type_category_name_plural, 'text_domain' ),
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
        register_taxonomy( $this->post_type_category, array( $this->post_type_key ), $args );

    }

    // Register Custom Tag
    public function cpt_create_tags() 
    {

        $labels = array(
            'name'                       => _x( $this->post_type_tag_name_plural, 'Taxonomy General Name', 'text_domain' ),
            'singular_name'              => _x( $this->post_type_tag_name_singular, 'Taxonomy Singular Name', 'text_domain' ),
            'menu_name'                  => __( $this->post_type_tag_name_plural, 'text_domain' ),
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
        register_taxonomy( $this->post_type_tag, array( $this->post_type_key ), $args );

    }

    /**
     * Add metabox to custom post type
     */
    public function metaboxes_cpt_add()
    {
        add_meta_box( $this->metabox_key, $this->metabox_name, array( $this, 'metaboxes_cpt_display' ), $this->post_type_key, 'normal', 'high' );
    }

    /**
     *  Add a metabox for CPT page edit screen 
	 * **/
    public function metaboxes_cpt_display()
    {
        global $post;
        $values = get_post_custom( $post->ID );

        wp_nonce_field( 'css_cpt_nonce', 'css_cpt_nonce' );

        foreach($this->meta_array[0] as $field) {            

            $description = $field->description;
            $name = $field->name;
            $key = isset( $values[$field->key] ) ? esc_attr( $values[$field->key][0] ) : '';

            ?>

            <p><?php echo $description; ?></p>
            <label for="<?php echo $key; ?>"><b><?php echo $name; ?></b> </label><br />
            <input name="<?php echo $key; ?>" id="<?php echo $key; ?>" placeholder="Value" value="<?php echo $key; ?>">

            <?php
        }
    }

    /**
     *  Save metabox content for cpt page edit screen
     **/
    public function metaboxes_cpt_save( $post_id )
    {
        // Bail if we're doing an auto save
        if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
            return;
        }

        // If our current user can't edit this post, bail
        if ( ! current_user_can( 'edit_post' ) ) {
            return;
        }
        
        foreach($_POST as $key => $value) {
            foreach($this->meta_array[0] as $field) { //ISSUE HERE
                if($key == $field->key) {

                    $value = sanitize_text_field($value);
                    update_post_meta( $post_id, $field->key, $value);

                }
            }
        }
        
    }

}

$locations = new cpt_PostTypes;
$locations->singular_post_name = 'Location';
$locations->plural_post_name = 'Locations';
$locations->post_type_key = 'cpt__locations';
$locations->post_type_description = 'Locations of Airsoft sites and shops';
$locations->post_type_text_domain = 'cpt__locations';
$locations->post_type_menu_position = 5;
$locations->admin_sidebar_icon = 'dashicons-admin-site';

$locations->post_type_category = 'locations_category';
$locations->post_type_category_name_singular = 'Location Category';
$locations->post_type_category_name_plural = 'Location Categories';
$locations->post_type_tag = 'locations_tag';
$locations->post_type_tag_name_singular = 'Location Tag';
$locations->post_type_tag_name_plural = 'Location Tags';

$locations->metabox_key = 'cpt__details';
$locations->metabox_name = 'Location Details';

$locations->meta_array = array( 
    array( 
        (object) [
            'key' => 'field1',
            'name' => 'Field 1',
            'description' => 'Extra desc'
        ],
        (object) [
            'key' => 'field2',
            'name' => 'Field 2',
            'description' => 'Some description'
        ],
        (object) [
            'key' => 'some_field',
            'name' => 'My Cool Field',
            'description' => 'Yet another successful story'
        ]
    )
);