<?php

namespace Fdv\Plugin;

class FdvRepository
{
    /**
     * Get plants from the API.
     *
     * @return array Returns array of plants on success, empty array on failure
     */
    public static function getPlants(): array
    {
        // Check if API URL is configured
        if (empty($_ENV['FDV_API_URL'])) {
            error_log('FDV Plugin: FDV_API_URL environment variable is not set');
            return [];
        }

        $url = $_ENV['FDV_API_URL'] . '/plants/list';

        // Make the API request
        $response = wp_remote_get($url, [
            'timeout' => 15,
            'headers' => [
                'Accept' => 'application/json',
            ],
        ]);

        // Check if the request returned a WP_Error
        if (is_wp_error($response)) {
            error_log('FDV Plugin: API request failed - ' . $response->get_error_message());
            return [];
        }

        // Check HTTP response code
        $response_code = wp_remote_retrieve_response_code($response);
        if ($response_code !== 200) {
            error_log('FDV Plugin: API returned HTTP ' . $response_code);
            return [];
        }

        // Get the response body
        $body = wp_remote_retrieve_body($response);
        if (empty($body)) {
            error_log('FDV Plugin: API returned empty response');
            return [];
        }

        // Decode JSON
        $data = json_decode($body, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            error_log('FDV Plugin: JSON decode error - ' . json_last_error_msg());
            return [];
        }

        // Validate data structure
        if (!is_array($data)) {
            error_log('FDV Plugin: API response is not an array');
            return [];
        }

        return $data;
    }
}