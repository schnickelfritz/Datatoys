<?php

namespace App\Controller\Admin\AdminUser;

use App\Entity\UserCandidate;
use App\Form\UserCandidate\UserCandidateFormType;
use App\Repository\UserCandidateRepository;
use App\Service\User\CheckUserCandidate;
use App\Trait\FlashMessageTrait;
use App\Trait\FormStringValueTrait;
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
#[IsGranted('ROLE_USERMANAGER')]
#[Route('/admin/user/update-candidate/{id}', name: 'app_admin_user_candidate_update', methods: [Request::METHOD_GET, Request::METHOD_POST])]
final readonly class AdminUserCandidateUpdateController
{
    use FlashMessageTrait;

    public function __construct(
        private EntityManagerInterface  $entityManager,
        private UserCandidateRepository $userCandidateRepository,
        private FormFactoryInterface    $formFactory,
        private UrlGeneratorInterface   $urlGenerator,
        private CheckUserCandidate      $checkUserCandidate,
        private Environment             $twig,
    ) {
    }

    public function __invoke(Request $request, int $id): Response
    {
        $userCandidate = $this->userCandidateRepository->find($id);
        if (!$userCandidate instanceof UserCandidate) {
            return new RedirectResponse($this->urlGenerator->generate('app_admin_user_candidate_list'));
        }

        $form = $this->formFactory->create(UserCandidateFormType::class, $userCandidate);
        $form->handleRequest($request);

        if (!$form->isSubmitted()) {
            $candidates = $this->userCandidateRepository->findAll();
            return new Response($this->twig->render('admin/user/candidate_update.html.twig', [
                'form_candidate' => $form->createView(),
                'users' => $candidates,
                'selected' => $userCandidate,
            ]));
        }

        if (!$form->isValid()) {
            $this->addFlash($request, 'fail', 'flash.fail.invalid_inputs');

            return new RedirectResponse($this->urlGenerator->generate('app_admin_user_candidate_update', ['id' => $userCandidate->getId()]));
        }

        $credentialsAvailableResult = $this->checkUserCandidate->isCredentialsAvailable($userCandidate);
        if ($credentialsAvailableResult !== true) {
            $this->addFlash($request, 'fail', sprintf('Abort: %s', $credentialsAvailableResult));

            return new RedirectResponse($this->urlGenerator->generate('app_admin_user_candidate_update', ['id' => $userCandidate->getId()]));
        }

        $this->entityManager->persist($userCandidate);
        $this->entityManager->flush();
        $this->addFlash($request, 'success', 'flash.success.update');

        return new RedirectResponse($this->urlGenerator->generate('app_admin_user_candidate_update', ['id' => $userCandidate->getId()]));
    }

}