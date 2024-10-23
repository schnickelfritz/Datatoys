<?php

namespace App\Controller\User;

use App\Entity\User;
use App\Form\User\MeUpdateFormType;
use App\Service\User\Me;
use App\Trait\FlashMessageTrait;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Twig\Environment;

#[AsController]
#[IsGranted('IS_AUTHENTICATED_REMEMBERED')]
#[Route('/user/me-update', name: 'app_me_update', methods: [Request::METHOD_GET, Request::METHOD_POST])]
final readonly class MeUpdateController
{
    use FlashMessageTrait;

    public function __construct(
        private EntityManagerInterface  $entityManager,
        private FormFactoryInterface    $formFactory,
        private UrlGeneratorInterface   $urlGenerator,
        private Me $me,
        private Environment             $twig,
    ) {
    }

    public function __invoke(Request $request): Response
    {
        $user = $this->me->user();
        if (!$user instanceof User) {
            return new RedirectResponse($this->urlGenerator->generate('app_home'));
        }
        $form = $this->formFactory->create(MeUpdateFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            //TODO 2024-10-23 ME: Passwort setzen (altes überprüfen)
            $this->entityManager->persist($user);
            $this->entityManager->flush();
            $this->addFlash($request, 'success', 'flash.success.update');
            return new RedirectResponse($this->urlGenerator->generate('app_me_update'));
        }

        return new Response($this->twig->render('user/me_update.html.twig', [
            'form_update' => $form->createView(),
            'user' => $user,
        ]));
    }

}