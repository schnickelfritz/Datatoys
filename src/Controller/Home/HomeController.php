<?php

namespace App\Controller\Home;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Attribute\Route;
use Twig\Environment;

#[AsController]
#[Route('/', name: 'app_home', methods: [Request::METHOD_GET])]
final readonly class HomeController
{
    public function __construct(
        private Environment $twig,
    ) {
    }

    public function __invoke(Request $request): Response
    {
        return new Response($this->twig->render('home/home.html.twig'));
    }
}