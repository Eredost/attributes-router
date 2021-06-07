<?php

namespace AttributesRouter\Tests;

use InvalidArgumentException;
use OutOfRangeException;
use PHPUnit\Framework\TestCase;
use AttributesRouter\Tests\Controller\{TestController, AnotherTestController};
use AttributesRouter\Router;

class RouterTest extends TestCase
{
    private ?Router $router;

    protected function setUp(): void
    {
        $this->router = new Router([TestController::class]);
        $this->router->addRoutes([AnotherTestController::class]);
    }

    public function tearDown(): void
    {
        $this->router = null;
    }

    protected function setRequestGlobals(string $uri, string $method = 'GET'): void
    {
        $_SERVER['REQUEST_URI'] = $uri;
        $_SERVER['REQUEST_METHOD'] = $method;
    }

    public function testSuccessfulMatch(): void
    {
        $this->setRequestGlobals('/');
        $match = $this->router->match();

        self::assertEquals(TestController::class, $match['class']);
        self::assertEquals(TestController::HOMEPAGE_METHOD, $match['method']);
    }

    public function testBadPath(): void
    {
        $this->setRequestGlobals('/non-existing-page');
        $match = $this->router->match();

        self::assertNull($match);
    }

    public function testCorrectPathBadMethod(): void
    {
        $this->setRequestGlobals('/', 'POST');
        $match = $this->router->match();

        self::assertNull($match);
    }

    public function testSuccessfulMatchWithBaseURI(): void
    {
        $this->setRequestGlobals('/subfolder/another-subfolder/contact');
        $this->router->setBaseURI('/subfolder/another-subfolder');
        $match = $this->router->match();

        self::assertEquals(TestController::class, $match['class']);
        self::assertEquals(TestController::CONTACT_METHOD, $match['method']);
    }

    public function testSuccessfulMatchOnDynamicPath(): void
    {
        $this->setRequestGlobals('/blog/hello-world/comment/5');
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
        $this->setRequestGlobals('/blog/hello-world/comment/id');
        $match = $this->router->match();

        self::assertNull($match);
    }

    public function testNoMatchOnDynamicRoute(): void
    {
        $this->setRequestGlobals('/blog/hello-world/comment/5/edit');
        $match = $this->router->match();

        self::assertNull($match);
    }

    public function testUrlGeneration(): void
    {
        $path = $this->router->generateUrl('homepage');

        self::assertEquals('/', $path);
    }

    public function testUrlGenerationOnDynamicRoute(): void
    {
        $path = $this->router->generateUrl('blog-comment', ['slug' => 'hello-world', 'id' => 15]);

        self::assertEquals('/blog/hello-world/comment/15', $path);
    }

    public function testUrlGenerationWithInvalidRouteName(): void
    {
        $this->expectException(OutOfRangeException::class);
        $this->router->generateUrl('non-existing-page');
    }

    public function testUrlGenerationWithMissingParams(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->router->generateUrl('blog-comment');
    }

    public function testUrlGenerationWithInvalidParamValue(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->router->generateUrl('blog-comment', ['slug' => 'hello-world', 'id' => 'invalid-id']);
    }
}
