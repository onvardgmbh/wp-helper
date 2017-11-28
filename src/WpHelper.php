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
}
