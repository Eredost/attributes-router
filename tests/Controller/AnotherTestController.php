<?php

namespace AttributesRouter\Tests\Controller;

use AttributesRouter\Attribute\Route;

class AnotherTestController
{
    #[Route('/about')]
    public function about(): void
    {
    }
}
