<?php
// WooCommerce - Add WPML language slug to WooCommerce Rest API
// Last update: 2025-07-09

if (class_exists('WooCommerce') && WC() && defined('ICL_SITEPRESS_VERSION')) {
    add_filter('woocommerce_rest_product_object_query', function ($args, $request) {
        if ($request->get_param('lang')) {
            $args['lang'] = sanitize_text_field($request->get_param('lang'));
        }
        return $args;
    }, 10, 2);

    add_action('rest_api_init', function () {
        $post_types = ['product', 'shop_order'];

        foreach ($post_types as $post_type) {
            register_rest_field($post_type, 'lang', [
                'get_callback' => function ($object) {
                    $details = apply_filters('wpml_post_language_details', null, $object['id']);
                    return isset($details['language_code']) ? $details['language_code'] : null;
                },
                'schema' => ['description' => __('Language of the post', 'wpml'), 'type' => 'string'],
            ]);
        }
    }, 10, 1);
}
