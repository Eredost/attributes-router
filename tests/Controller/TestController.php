<?php

namespace AttributesRouter\Tests\Controller;

use AttributesRouter\Attribute\Route;

class TestController
{
    public const HOMEPAGE_METHOD = 'index';
    public const CONTACT_METHOD = 'contact';
    public const BLOG_COMMENT_METHOD = 'blogComment';

    #[Route('/', name: 'homepage')]
    public function index(): void
    {
    }

    #[Route('/contact')]
    public function contact(): void
    {
    }

    #[Route('/blog/{slug}/comment/{id<\d+>}', name: 'blog-comment', methods: ['GET'])]
    public function blogComment(): void
    {
    }
}
