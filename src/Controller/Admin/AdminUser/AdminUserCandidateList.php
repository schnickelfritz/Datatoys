<?php

namespace App\Controller\Admin\AdminUser;

use App\Repository\UserCandidateRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Twig\Environment;

#[AsController]
#[IsGranted('ROLE_USERMANAGER')]
#[Route('/admin/user/candidate-list', name: 'app_admin_user_candidate_list', methods: [Request::METHOD_GET])]
final readonly class AdminUserCandidateList
{
    public function __construct(
        private UserCandidateRepository $userCandidateRepository,
        private Environment $twig,
    ) {
    }

    public function __invoke(Request $request): Response
    {
        $candidates = $this->userCandidateRepository->findAll();

        return new Response($this->twig->render('admin/user/candidate_list.html.twig', [
            'users' => $candidates,
        ]));
    }
}