<?php
declare(strict_types = 1);

use Onvardgmbh\WpHelper\Svg;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Onvardgmbh\WpHelper\Svg
 */
final class SvgTest extends TestCase
{
    public function testAddMime()
    {
        $mimes = [];
        $mimes = Svg::addMime($mimes);
        $this->assertArrayHasKey('svg', $mimes);
        $this->assertEquals('image/svg+xml', $mimes['svg']);
    }
}
