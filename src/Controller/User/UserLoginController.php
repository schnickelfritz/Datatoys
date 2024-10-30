<?php

declare(strict_types=1);

namespace App\Controller\User;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Twig\Environment;

#[AsController]
#[Route('/login', name: 'app_login', methods: [Request::METHOD_GET, Request::METHOD_POST])]
final readonly class UserLoginController
{
    public function __construct(
        private AuthenticationUtils $authenticationUtils,
        private Environment $twig,
    ) {
    }

    public function __invoke(): Response
    {
        $error = $this->authenticationUtils->getLastAuthenticationError();
        $lastUsername = $this->authenticationUtils->getLastUsername();

        return new Response($this->twig->render('user/login.html.twig', [
            'last_username' => $lastUsername,
            'error' => $error,
        ]));
    }
}
