<?php

declare(strict_types=1);

namespace App\Controller\Admin\AdminUser;

use App\Entity\UserCandidate;
use App\Trait\FlashMessageTrait;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Contracts\Translation\TranslatorInterface;

#[AsController]
#[IsGranted('ROLE_USERMANAGER')]
#[Route('/admin/user/delete-candidate/{id}', name: 'app_admin_user_candidate_delete', methods: [Request::METHOD_POST])]
final readonly class AdminUserCandidateDeleteController
{
    use FlashMessageTrait;

    public function __construct(
        private EntityManagerInterface $entityManager,
        private UrlGeneratorInterface $urlGenerator,
        private TranslatorInterface $translator,
    ) {
    }

    public function __invoke(Request $request, UserCandidate $userCandidate): Response
    {
        $this->entityManager->remove($userCandidate);
        $this->entityManager->flush();
        $this->addFlash($request, 'success', $this->translator->trans('admin.candidate.delete.flash', ['name' => $userCandidate->getName(), 'email' => $userCandidate->getEmail()]));

        return new RedirectResponse($this->urlGenerator->generate('app_admin_user_list'));
    }
}
