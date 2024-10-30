<?php

declare(strict_types=1);

namespace App\Controller\Home;

use App\Form\Home\HomeContactFormType;
use App\Service\Email\SendContactEmail;
use App\Trait\FlashMessageTrait;
use App\Trait\FormStringValueTrait;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Twig\Environment;

#[AsController]
#[Route('/contact', name: 'app_contact', methods: [Request::METHOD_GET, Request::METHOD_POST])]
final readonly class HomeContactController
{
    /*
     * Let any user send an email message to the app admin email address
     */

    use FlashMessageTrait;
    use FormStringValueTrait;

    public function __construct(
        private FormFactoryInterface $formFactory,
        private UrlGeneratorInterface $urlGenerator,
        private SendContactEmail $contactEmail,
        private Environment $twig,
    ) {
    }

    public function __invoke(Request $request): Response
    {
        $form = $this->formFactory->create(HomeContactFormType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->contactEmail->send(
                $this->formStringValue($form, 'message'),
                $this->formStringValue($form, 'contactEmail'),
                $this->formStringValue($form, 'about'),
            );
            // if ($form->sendcopy) { $this->contactEmailCopy->send(message, contactEmail) }
            $this->addFlash($request, 'success', 'Your message was received.');

            return new RedirectResponse($this->urlGenerator->generate('app_contact'));
        }

        return new Response($this->twig->render('home/contact.html.twig', [
            'form_contact' => $form->createView(),
        ]));
    }
}
