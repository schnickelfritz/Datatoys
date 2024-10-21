<?php

namespace App\Controller\Admin\AdminUser;

use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Twig\Environment;

#[AsController]
#[IsGranted('ROLE_USERMANAGER')]
#[Route('/admin/user/list', name: 'app_admin_user_list', methods: [Request::METHOD_GET])]
final readonly class AdminUserListController
{
    public function __construct(
        private UserRepository $userRepository,
        private Environment $twig,
    ) {
    }

    public function __invoke(Request $request): Response
    {
        $candidates = $this->userRepository->findAll();

        return new Response($this->twig->render('admin/user/list.html.twig', [
            'users' => $candidates,
        ]));
    }
}