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

    protected function setServerGlobals(string $uri, string $method = 'GET'): void
    {
        $_SERVER['REQUEST_URI'] = $uri;
        $_SERVER['REQUEST_METHOD'] = $method;
    }

    public function testSuccessfulMatch(): void
    {
        $this->setServerGlobals('/');
        $match = $this->router->match();

        self::assertEquals(TestController::class, $match['class']);
        self::assertEquals(TestController::HOMEPAGE_METHOD, $match['method']);
    }

    public function testBadPath(): void
    {
        $this->setServerGlobals('/non-existing-page');
        $match = $this->router->match();

        self::assertNull($match);
    }

    public function testCorrectPathBadMethod(): void
    {
        $this->setServerGlobals('/', 'POST');
        $_SERVER['REQUEST_METHOD'] = 'POST';
        $match = $this->router->match();

        self::assertNull($match);
    }

    public function testSuccessfulMatchWithBaseURI(): void
    {
        $this->setServerGlobals('/subfolder/another-subfolder/contact');
        $this->router->setBaseURI('/subfolder/another-subfolder');
        $match = $this->router->match();

        self::assertEquals(TestController::class, $match['class']);
        self::assertEquals(TestController::CONTACT_METHOD, $match['method']);
    }

    public function testSuccessfulMatchOnDynamicPath(): void
    {
        $this->setServerGlobals('/blog/hello-world/comment/5');
        $match = $this->router->match();

        self::assertEquals(TestController::class, $match['class']);
        self::assertEquals(TestController::BLOG_COMMENT_METHOD, $match['method']);

        self::assertArrayHasKey('slug', $match['params']);
        self::assertArrayHasKey('id', $match['params']);
        self::assertEquals('hello-world', $match['params']['slug']);
        self::assertEquals('5', $match['params']['id']);
    }

    public function testInvalidParamOnDynamicRoute(): void
    {
        $this->setServerGlobals('/blog/hello-world/comment/id');
        $match = $this->router->match();

        self::assertNull($match);
    }

    public function testNoMatchOnDynamicRoute(): void
    {
        $this->setServerGlobals('/blog/hello-world/comment/5/edit');
        $match = $this->router->match();

        self::assertNull($match);
    }
}
