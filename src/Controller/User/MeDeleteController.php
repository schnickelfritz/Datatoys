<?php

namespace App\Controller\User;

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

#[AsController]
#[IsGranted('IS_AUTHENTICATED_REMEMBERED')]
#[Route('/admin/user/me-delete', name: 'app_me_delete', methods: [Request::METHOD_POST])]
final readonly class MeDeleteController
{
    use FlashMessageTrait;

    public function __construct(
        private EntityManagerInterface  $entityManager,
        private Me $me,
        private UrlGeneratorInterface   $urlGenerator,
    ) {
    }

    public function __invoke(Request $request): Response
    {
        $user = $this->me->user();
        if ($user instanceof User) {
            $this->entityManager->remove($user);
            $this->entityManager->flush();
            //TODO 2024-10-23 ME: Fehler vermeiden, der dadurch entsteht, dass gelöschter User noch authentifiziert gilt
            $this->addFlash($request, 'success', 'flash.success.bye');
        }

        return new RedirectResponse($this->urlGenerator->generate('app_home'));
    }
}