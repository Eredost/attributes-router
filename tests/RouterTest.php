<?php

use AttributesRouter\Router;

require_once 'TestController.php';

class RouterTest extends \PHPUnit\Framework\TestCase
{
    private ?Router $router;

    protected function setUp(): void
    {
        $this->router = new Router();
        $this->router->addControllers([TestController::class]);
    }

    public function tearDown(): void
    {
        $this->router = null;
    }

    public function testSuccessfulMatch(): void
    {
        $_SERVER['REQUEST_URI'] = '/';
        $_SERVER['REQUEST_METHOD'] = 'GET';
        $match = $this->router->match();

        self::assertEquals(TestController::class, $match['class']);
        self::assertEquals(TestController::HOMEPAGE_METHOD, $match['method']);
    }

    public function testNoMatch(): void
    {
        $_SERVER['REQUEST_URI'] = '/non-existing-page';
        $_SERVER['REQUEST_METHOD'] = 'GET';
        $match = $this->router->match();

        self::assertNull($match);
    }

    public function testSuccessfulMatchWithBaseURI(): void
    {
        $_SERVER['REQUEST_URI'] = 'subfolder/another-subfolder/contact';
        $_SERVER['REQUEST_METHOD'] = 'GET';
        $this->router->setBaseURI('subfolder/another-subfolder');
        $match = $this->router->match();

        self::assertEquals(TestController::class, $match['class']);
        self::assertEquals(TestController::CONTACT_METHOD, $match['method']);
    }
}
