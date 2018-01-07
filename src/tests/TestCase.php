<?php
namespace Tests;
use App\Models\BadDomain;
use App\Models\Click;
use \Laravel\Lumen\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    function setUp()
    {
        parent::setUp();
        Click::query()->delete();
        BadDomain::query()->delete();
    }

    /**
     * Creates the application.
     *
     * @return \Laravel\Lumen\Application
     */
    public function createApplication()
    {
        return require __DIR__.'/../bootstrap/app.php';
    }
}
