<?php

/*
* REST API endpoint class
*/ 
class restApiEndpoint3 {

    const REST_API_CONFIG = YC_PREFIX . '_api_endpoint_config';
    const CONFIG = [];

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



/*$api = new restApiEndpoint3();

$api->set_config($cfg);*/

/*echo '<pre>';
print_r($api->get_config());
echo '</pre>';*/

/*foreach($api->get_config()[ENDPOINT] as $c) {
    echo $c[ENDPOINT_NAME] . '<br>';
    echo $c[INCLUDED_FIELDS][0] . '<br>';
}*/