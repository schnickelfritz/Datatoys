<?php

declare(strict_types=1);

namespace App\Controller\User;

use LogicException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Attribute\Route;

#[AsController]
#[Route('/logout', name: 'app_logout', methods: [Request::METHOD_GET])]
final readonly class UserLogoutController
{
    public function __invoke(): void
    {
        throw new LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }
}
