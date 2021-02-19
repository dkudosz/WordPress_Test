<?php 
// BlueManager - Settings Page

// disable direct file access
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// display the plugin settings page
function yc_cae_display_settings_page() {
	
	// check if user is allowed access
	if ( ! current_user_can( 'manage_options' ) ) return;
    
    if(isset($_GET['page'])) {
        $page = $_GET['page'];
    }

	?>
	
	<div class="wrap">
		<h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
            
        <div class="wrap">
            <!-- Here are our tabs -->
            <nav class="nav-tab-wrapper">
                <a href="?page=_yc_cae_dashboard" class="nav-tab <?php if($page == '_yc_cae_dashboard'):?>nav-tab-active<?php endif; ?>"><?php _e( 'Dashboard', 'CustomApiEndpoint' ); ?></a>
                <a href="?page=_yc_cae_endpoints" class="nav-tab <?php if($page == '_yc_cae_endpoints'):?>nav-tab-active<?php endif; ?>"><?php _e( 'Endpoints', 'CustomApiEndpoint' ); ?></a>
            </nav>

            <div class="tab-content">
            <?php 
                // Get current tab content
                if($page == '_yc_cae_dashboard') {
                    ?>

                    <?php
                }

                if($page == '_yc_cae_endpoints') {
                    if(isset($_POST['enpoint_prefix']) && isset($_POST['enpoint_name'])) {
                        
                        $config = get_option(YC_PREFIX . 'api_endpoint_config');

                        $endpoint = new restApiEndpoint();

                        $endpoint->api_prefix = $_POST['enpoint_prefix'];
                        $endpoint->api_name = $_POST['enpoint_name'];

                        array_push($config, $endpoint);

                        $endpoint->set_config($config);

                    }

                    ?>
                    <div class="row">
                        <div class="col-md-6">
                            <p>Enter prefix and name of your endpoint.</p>
                            <form action="" method="post" id="new_endpoint">
                                <p><?php echo get_site_url() . '/wp-json/'; ?><input name="enpoint_prefix" type="text" value="" placeholder="Prefix" required> /v1/ <input name="enpoint_name" type="text" placeholder="Name" required> <?php submit_button('Add'); ?></p>
                            </form>
                        </div>
                        <div class="col-md-6">
                            <p>Existing endpoints.</p>
                            <?php
                                if($endpoints = get_option(YC_PREFIX . 'api_endpoint_config')) {
                                    print_r($endpoints);
                                    foreach($endpoints as $endpoint) {
                                        ?>
                                        <p><?php echo get_site_url(); ?>/wp-json/<?php echo $endpoint->api_prefix; ?>/v1/<?php echo $endpoint->api_name; ?></p>
                                        <?php
                                    }
                                } else {
                                    ?>
                                    <div class="alert alert-secondary" role="alert">
                                        There are no existing endpoints. You can add endpoints in the left column.
                                    </div>
                                    <?php
                                }
                            ?>
                        
                        </div>
                    </div>
                    
                    <?php
                }
            ?>
            </div>
        </div>
	</div>
	
	<?php
	
}