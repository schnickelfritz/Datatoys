<?php

declare(strict_types=1);

namespace App\Controller\Admin\AdminUser;

use App\Entity\UserCandidate;
use App\Service\Email\SendInviteEmail;
use App\Trait\FlashMessageTrait;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Http\Attribute\IsGranted;

use function Symfony\Component\Clock\now;

#[AsController]
#[IsGranted('ROLE_USERMANAGER')]
#[Route('/admin/user/invite-candidate/{id}', name: 'app_admin_user_candidate_invite', methods: [Request::METHOD_GET])]
final readonly class AdminUserCandidateInviteController
{
    use FlashMessageTrait;

    public function __construct(
        private SendInviteEmail $sendInviteEmail,
        private EntityManagerInterface $entityManager,
        private UrlGeneratorInterface $urlGenerator,
    ) {
    }

    public function __invoke(Request $request, UserCandidate $userCandidate): Response
    {
        $emailSuccessfullySent = $this->sendInviteEmail->send($userCandidate);

        if ($emailSuccessfullySent) {
            $userCandidate->setInviteSentAt(now());
            $this->entityManager->persist($userCandidate);
            $this->entityManager->flush();
            $this->addFlash($request, 'success', 'flash.success.done');
        } else {
            $this->addFlash($request, 'fail', 'flash.fail.email_not_sent');
        }

        return new RedirectResponse($this->urlGenerator->generate('app_admin_user_list'));
    }
}
