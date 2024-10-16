<?php

namespace App\Controller\Admin\AdminUser;

use App\Entity\UserCandidate;
use App\Form\UserCandidate\UserCandidateCreateFormType;
use App\Service\User\CreateUserCandidate;
use App\Trait\FlashMessageTrait;
use App\Trait\FormStringValueTrait;
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
#[IsGranted('ROLE_USERMANAGER')]
#[Route('/admin/user/create-candidate', name: 'app_admin_user_candidate_create', methods: [Request::METHOD_GET, Request::METHOD_POST])]
final readonly class AdminUserCandidateCreate
{

    /*
     * A User Candidate is like a User without password. They cannot login, but can receive an invitation for
     * confirmation. If they set a valid password, they become a "real" User, who is able to login.
     */

    use FlashMessageTrait;
    use FormStringValueTrait;

    public function __construct(
        private FormFactoryInterface $formFactory,
        private UrlGeneratorInterface $urlGenerator,
        private CreateUserCandidate $createUserCandidate,
        private Environment $twig,
    ) {
    }

    public function __invoke(Request $request): Response
    {
        $userCandidate = new UserCandidate();
        $form = $this->formFactory->create(UserCandidateCreateFormType::class, $userCandidate);
        $form->handleRequest($request);
        if (!$form->isSubmitted()) {
            return new Response($this->twig->render('admin/user/candidate_create.html.twig', [
                'form_candidate_create' => $form->createView(),
            ]));
        }
        if (!$form->isValid()) {
            $this->addFlash($request, 'fail', 'Invalid inputs.');

            return new RedirectResponse($this->urlGenerator->generate('app_admin_user_candidate_create'));
        }

        $candidateCreateResult = $this->createUserCandidate->create($userCandidate);
        if ($candidateCreateResult !== true) {
            $this->addFlash($request, 'fail', 'Name or email already in use.');

            return new RedirectResponse($this->urlGenerator->generate('app_admin_user_candidate_create'));
        }

        $this->addFlash($request, 'success', 'Candidate created.');

        return new RedirectResponse($this->urlGenerator->generate('app_admin_user_candidate_list'));
    }

}