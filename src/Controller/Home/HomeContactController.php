<?php

namespace App\Controller\Home;

use App\Form\Home\HomeContactFormType;
use App\Trait\FlashMessageTrait;
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
     *
     * More or less a controller for trying out things, we don't really need a contact form
     * at least not in the first couple of sprints
     *
     */

    use FlashMessageTrait;

    public function __construct(
        private FormFactoryInterface $formFactory,
        private UrlGeneratorInterface $urlGenerator,
        private Environment $twig,
    )
    {
    }

    public function __invoke(Request $request): Response
    {
        $form = $this->formFactory->create(HomeContactFormType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->addFlash($request, 'success', 'Your message was received.');
            return new RedirectResponse($this->urlGenerator->generate('app_comment'));
        }
        return new Response($this->twig->render('home/contact.html.twig', [
            'form_contact' => $form->createView(),
        ]));
    }

}