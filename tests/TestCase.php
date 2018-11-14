<?php
/**
 * Created by PhpStorm.
 * User: diedegulpers
 * Date: 14/11/2018
 * Time: 16:51
 */

namespace Cell0\LGT\Tests;

use \Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use \Illuminate\Contracts\Console\Kernel;

class TestCase extends BaseTestCase
{
    /**
     * Creates the application.
     *
     * @return \Illuminate\Foundation\Application
     */
    public function createApplication()
    {
        dd('ddddd');
        $app = require __DIR__ . '/../bootstrap/app.php';

        $app->make(Kernel::class)->bootstrap();

        return $app;
    }

    /**
     * Register the Eloquent factory instance in the container.
     *
     * @return void
     */
    protected function setUp()
    {
        parent::setUp();

        $this->app->singleton(EloquentFactory::class, function ($app) {
            return EloquentFactory::construct(
                $app->make(FakerGenerator::class), __DIR__.'/factories'
            );
        });
    }
}
