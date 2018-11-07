<?php

namespace Cell0\LGT\ResourceTest\FactoryResources;

use PHPUnit\Framework\TestCase;

/**
 * Class PositiveResource, the type of resource that we all want and need in our life, the type that is always
 * positive.
 *
 * @package Cell0\LGT\ResourceTest\FactoryResources
 *
 * @author Diede Gulpers <diede@cell-0.com>
 */
class PositiveResource extends TestCase
{
    public function assert_specs_met()
    {
        $this->assertTrue(true);
    }
}
