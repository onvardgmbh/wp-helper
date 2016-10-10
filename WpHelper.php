<?php
namespace Onvardgmbh;

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
     * @param $string
     *
     * @return string
     */
    public static function ascii_esc_string($string)
    {
        $return = '';
        foreach (str_split($string) as $char) {
            $return .= '&#' . ord($char) . ';';
        }

        return $return;
    }
}
