<?php

/*
* REST API endpoint class
*/ 
class restApiEndpoint {

    const REST_API_CONFIG = YC_PREFIX . 'api_endpoint_config';
    const CONFIG = [];

    public $api_prefix;
    public $api_name;

    /* SAMPLE CONFIG
        [
            'ENDPOINT' => [
                [
                    'BASE_ROUTE' => '/bs/v1/', // base name for the route
                    'ENDPOINT_NAME' => 'articles', // name of the endpoint 
                    'PARAMS' => [ // URL params to be passed and used in the endpoint
                    ],
                ]
        ]

        // example above will create endpoint http://domain.com/wp-json/bs/v1/articles
    */

    public function __construct()
    {
        add_option(self::REST_API_CONFIG, self::CONFIG);
    }

    public function set_config($config)
    {
        update_option(self::REST_API_CONFIG, $config);
    }

    public function get_config()
    {
        return get_option(self::REST_API_CONFIG);
    }

    public function delete_config() {
        update_option(self::REST_API_CONFIG, '');
    }
}