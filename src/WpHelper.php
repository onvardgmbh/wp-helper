<?php
namespace Onvardgmbh\WpHelper;

/**
 * Wordpress helper class
 */
class WpHelper
{
    /**
     * ASCII escape a string.
     * Can be used to obfuscate email addresses.
     *
     * @since 0.0.1
     * @author André Schwarzer <schwarzer@onvard.de>
     *
     * @param String $string
     *
     * @return String
     */
    public static function asciiEscString($string)
    {
        $return = '';
        foreach (str_split($string) as $char) {
            $return .= '&#' . ord($char) . ';';
        }

        return $return;
    }

    /**
     * Get excerpt from string. Auto-removes Wordpress shortcodes.
     *
     * @since 0.0.2
     * @author André Schwarzer <schwarzer@onvard.de>
     *
     * @param String $string String to get an excerpt from
     * @param Integer $startPos Position int string to start excerpt from
     * @param Integer $maxLength Maximum length the excerpt may be
     * @param Bool $stripTags Remove all HTML tags from string
     * @param Bool $addHellip Add … to the end of the excerpt string
     *
     * @return String excerpt
     */
    public static function excerptString(
        $string,
        $startPos = 0,
        $maxLength = 100,
        $stripTags = false,
        $addHellip = true
    ) {
        if (strlen($string) > $maxLength) {
            $excerpt   = substr($string, $startPos, $maxLength - 3);
            $lastSpace = strrpos($excerpt, ' ');
            $excerpt   = substr($excerpt, 0, $lastSpace);
        } else {
            $excerpt = $string;
        }

        if ($stripTags) {
            $excerpt = strip_tags($excerpt, '<p><br><br/>');
            $excerpt = preg_replace('/(class|style)=".*?"/', '', $excerpt);
        }
        if ($addHellip) {
            $excerpt .= '&hellip;';
        }

        // @see https://github.com/WordPress/WordPress/blob/master/wp-includes/shortcodes.php#L596
        return strip_shortcodes($excerpt);
    }

    /**
     * Returns a file size limit in bytes based on the PHP upload_max_filesize and post_max_size
     * @see https://stackoverflow.com/questions/13076480/php-get-actual-maximum-upload-size
     * @return int
     */
    public static function fileUploadMaxSize() {
        static $max_size = -1;

        if ($max_size < 0) {
            // Start with post_max_size.
            $post_max_size = static::parseSize(ini_get('post_max_size'));
            if ($post_max_size > 0) {
                $max_size = $post_max_size;
            }

            // If upload_max_size is less, then reduce. Except if upload_max_size is
            // zero, which indicates no limit.
            $upload_max = static::parseSize(ini_get('upload_max_filesize'));
            if ($upload_max > 0 && $upload_max < $max_size) {
                $max_size = $upload_max;
            }
        }
        return $max_size;
    }

    /**
     * Parses the ini filesize string and returns the number of bytes
     * @see https://stackoverflow.com/questions/13076480/php-get-actual-maximum-upload-size
     *
     * @param $size
     * @return int
     */
    public static function parseSize($size) {
        $unit = preg_replace('/[^bkmgtpezy]/i', '', $size); // Remove the non-unit characters from the size.
        $size = preg_replace('/[^0-9\.]/', '', $size); // Remove the non-numeric characters from the size.
        if ($unit) {
            // Find the position of the unit in the ordered string which is the power of magnitude to multiply a kilobyte by.
            return (int) round($size * pow(1024, stripos('bkmgtpezy', $unit[0])));
        }
        return (int) round($size);
    }
}
