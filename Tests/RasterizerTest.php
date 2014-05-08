<?php

namespace Padam87\RasterizeBundle\Tests;

use Padam87\RasterizeBundle\ConfigHelper;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class RasterizerTest extends WebTestCase
{
    public function setUp()
    {
        parent::createClient();

        $this->rasterizer = self::$kernel->getContainer()->get('padam87_rasterize.rasterizer');
    }

    /**
     * @test
     */
    public function isServiceRegisteredCorrectly()
    {
        $this->assertInstanceOf('Padam87\RasterizeBundle\Rasterizer', $this->rasterizer);
    }
}
