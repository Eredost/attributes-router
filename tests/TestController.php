<?php

use AttributesRouter\Attribute\Route;

class TestController
{
    public const HOMEPAGE_METHOD = 'index';
    public const CONTACT_METHOD = 'contact';

    #[Route('/')]
    public function index(): void
    {
    }

    #[Route('/contact')]
    public function contact(): void
    {

    }
}
