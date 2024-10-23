<?php

namespace App\Controller\User;

use App\Entity\UserCandidate;
use App\Form\User\UserCreateFormType;
use App\Repository\UserCandidateRepository;
use App\Service\User\CreateUser;
use App\Trait\FlashMessageTrait;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use SymfonyCasts\Bundle\VerifyEmail\Exception\VerifyEmailExceptionInterface;
use SymfonyCasts\Bundle\VerifyEmail\VerifyEmailHelperInterface;
use Twig\Environment;
use Webmozart\Assert\Assert;

#[AsController]
#[Route('/admin/user/create', name: 'app_user_create', methods: [Request::METHOD_GET, Request::METHOD_POST])]
final readonly class UserCreateController
{
    use FlashMessageTrait;

    public function __construct(
        private CreateUser $createUser,
        private VerifyEmailHelperInterface $verifyEmailHelper,
        private UserCandidateRepository $userCandidateRepository,
        private UrlGeneratorInterface $urlGenerator,
        private FormFactoryInterface    $formFactory,
        private Environment             $twig,
    )
    {
    }

    public function __invoke(Request $request): Response
    {
        $userCandidate = $this->userCandidateRepository->find($request->query->get('id'));
        if (!$userCandidate instanceof UserCandidate) {
            return new RedirectResponse($this->urlGenerator->generate('app_home'));
        }

        try {
            $this->verifyEmailHelper->validateEmailConfirmation(
                $request->getUri(),
                $userCandidate->getEmail(),
                $userCandidate->getEmail(),
            );
        } catch (VerifyEmailExceptionInterface $e) {
            $this->addFlash($request, 'fail', 'flash.fail.invalid_url');

            return new RedirectResponse($this->urlGenerator->generate('app_home'));
        }

        $form = $this->formFactory->create(UserCreateFormType::class);
        $form->handleRequest($request);
        if (!$form->isSubmitted() || !$form->isValid()) {
            return new Response($this->twig->render('user/create.html.twig', [
                'form_create' => $form->createView(),
                'user_candidate' => $userCandidate,
            ]));
        }

        $password = $form->get('password')->getData();
        Assert::string($password, 'string expected');
        $this->createUser->create($userCandidate, $password);
        $this->addFlash($request, 'success', 'user.flash.created');

        return new RedirectResponse($this->urlGenerator->generate('app_login'));
    }
}