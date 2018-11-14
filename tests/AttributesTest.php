<?php
/**
 * Created by PhpStorm.
 * User: diedegulpers
 * Date: 14/11/2018
 * Time: 16:48
 */

namespace Cell0\LGT\Tests;

use Cell0\LGT\ResourceTest\ResourceTestCase;
use Tests\SupportingFiles\Laser;
use Tests\SupportingFiles\LaserResource;

class AttributesTest extends TestCase
{
    /**
     * @test
     */
    public function our_first_test()
    {
        $resourceTester = new class extends ResourceTestCase {

            protected $resourceClass = LaserResource::class;

            protected $modelClass = Laser::class;

            protected $attributes = [
                'heat',
                'color',
                'thickness'
            ];
        };

        $resourceTester->it_passes_the_spec();
    }
}
