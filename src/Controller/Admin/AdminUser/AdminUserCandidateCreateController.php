<?php

namespace App\Controller\Admin\AdminUser;

use App\Entity\UserCandidate;
use App\Form\User\UserCandidateFormType;
use App\Repository\UserCandidateRepository;
use App\Service\User\CheckUserCandidate;
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
use function Symfony\Component\Clock\now;

#[AsController]
#[IsGranted('ROLE_USERMANAGER')]
#[Route('/admin/user/create-candidate', name: 'app_admin_user_candidate_create', methods: [Request::METHOD_GET, Request::METHOD_POST])]
final readonly class AdminUserCandidateCreateController
{

    /*
     * User Candidates are like Users without password. They cannot login, but can receive an invitation for
     * confirmation. If they set a valid password, they become a "real" User, who are able to login.
     */

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

    public function __invoke(Request $request): Response
    {
        $candidates = $this->userCandidateRepository->findAll();
        $userCandidate = new UserCandidate();
        $form = $this->formFactory->create(UserCandidateFormType::class, $userCandidate);
        $form->handleRequest($request);

        if (!$form->isSubmitted()) {
            return new Response($this->twig->render('admin/user/candidate_create.html.twig', [
                'form_candidate' => $form->createView(),
                'candidates' => $candidates,
            ]));
        }

        if (!$form->isValid()) {
            $this->addFlash($request, 'fail', 'flash.fail.invalid_inputs');

            return new RedirectResponse($this->urlGenerator->generate('app_admin_user_candidate_create'));
        }

        $credentialsAvailableResult = $this->checkUserCandidate->isCredentialsAvailable($userCandidate);
        if ($credentialsAvailableResult !== true) {
            //TODO 2024-10-21 ME: this should also render template, so restructuring of this class is neccessary!
            $this->addFlash($request, 'fail', sprintf('Abort: %s', $credentialsAvailableResult));

            return new RedirectResponse($this->urlGenerator->generate('app_admin_user_candidate_create'));
        }

        $userCandidate->setCreatedAt(now());
        $this->entityManager->persist($userCandidate);
        $this->entityManager->flush();
        $this->addFlash($request, 'success', 'flash.success.create');

        return new RedirectResponse($this->urlGenerator->generate('app_admin_user_candidate_list'));
    }

}