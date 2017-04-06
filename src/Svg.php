<?php
namespace Onvardgmbh\WpHelper;

class Svg
{
    public static function setup()
    {
        // Allow SVG uploads
        add_filter('upload_mimes', [__CLASS__, 'addMime']);
        add_filter('wp_check_filetype_and_ext', [__CLASS__,'fixMime'], 75, 4);
        add_filter('wp_prepare_attachment_for_js', [__CLASS__, 'fixMediaLibrary'], 10, 3);
        add_filter('wp_get_attachment_image_src', [__CLASS__, 'fixSize'], 10, 4);
    }

    /**
     * Add SVG to the array of allowed mime types.
     *
     * @wp_filter 'upload_mimes'
     * @param  array $mimes
     * @return array
     */
    public static function addMime($mimes) {
        $mimes['svg'] = 'image/svg+xml';

        return $mimes;
    }

    /**
     * Fix issue in WordPress 4.7.1 with identifying SVGs
     *
     * @return mixed
     */
    public static function fixMime($data = null, $file = null, $filename = null, $mimes = null) {
        $ext = isset($data['ext']) ? $data['ext'] : '';
        if (strlen($ext) < 1) {
            $ext = strtolower(end(explode('.', $filename)));
        }

        if ($ext === 'svg') {
            $data['type'] = 'image/svg+xml';
            $data['ext']  = 'svg';
        }

        return $data;
    }

    /**
     * Correctly display SVGs in the media library
     *
     * @param  $response
     * @param  $attachment
     * @param  $meta
     * @return mixed
     */
    public static function fixMediaLibrary($response, $attachment, $meta) {
        if ($response['mime'] == 'image/svg+xml') {
            $possible_sizes = apply_filters( 'image_size_names_choose', [
                'thumbnail' => __('Thumbnail', 'wptheme'),
                'medium'    => __('Medium', 'wptheme'),
                'large'     => __('Large', 'wptheme'),
                'full'      => __('Full Size', 'wptheme'),
            ]);

            $sizes = [];

            foreach ($possible_sizes as $size => $string) {
                $sizes[$size] = [
                    'height'      => 2000,
                    'width'       => 2000,
                    'url'         => $response['url'],
                    'orientation' => 'portrait',
                ];
            }

            $response['sizes'] = $sizes;
            $response['icon'] = $response['url'];
        }

        return $response;
    }

    public static function fixSize($image, $attachment_id, $size, $icon) {
        if (get_post_mime_type($attachment_id) == 'image/svg+xml') {
            $image['1'] = 200;
            $image['2'] = 200;
        }

        return $image;
    }
}
