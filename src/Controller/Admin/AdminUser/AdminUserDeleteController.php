<?php

declare(strict_types=1);

namespace App\Controller\Admin\AdminUser;

use App\Entity\User;
use App\Service\User\Me;
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
#[Route('/admin/user/delete/{id}', name: 'app_admin_user_delete', methods: [Request::METHOD_POST])]
final readonly class AdminUserDeleteController
{
    use FlashMessageTrait;

    public function __construct(
        private EntityManagerInterface $entityManager,
        private Me $me,
        private UrlGeneratorInterface $urlGenerator,
        private TranslatorInterface $translator,
    ) {
    }

    public function __invoke(Request $request, User $user): Response
    {
        if ($user === $this->me->user()) {
            $this->addFlash($request, 'fail', 'admin.user.delete.flash.fail_me');

            return new RedirectResponse($this->urlGenerator->generate('app_admin_user_update', ['id' => $user->getId()]));
        }

        $this->entityManager->remove($user);
        $this->entityManager->flush();
        $this->addFlash($request, 'success', $this->translator->trans('admin.user.delete.flash.success', ['name' => $user->getName(), 'email' => $user->getEmail()]));

        return new RedirectResponse($this->urlGenerator->generate('app_admin_user_list'));
    }
}
