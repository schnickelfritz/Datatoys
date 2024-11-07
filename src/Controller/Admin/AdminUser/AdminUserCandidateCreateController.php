<?php

declare(strict_types=1);

namespace App\Controller\Admin\AdminUser;

use App\Entity\UserCandidate;
use App\Form\User\UserCandidateFormType;
use App\Repository\UserCandidateRepository;
use App\Repository\UserRepository;
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

use function sprintf;
use function Symfony\Component\Clock\now;

#[AsController]
#[IsGranted('ROLE_USERMANAGER')]
#[Route('/admin/user/create-candidate', name: 'app_admin_user_candidate_create', methods: [Request::METHOD_GET, Request::METHOD_POST])]
final readonly class AdminUserCandidateCreateController
{

    use FlashMessageTrait;

    public function __construct(
        private EntityManagerInterface $entityManager,
        private UserCandidateRepository $userCandidateRepository,
        private UserRepository $userRepository,
        private FormFactoryInterface $formFactory,
        private UrlGeneratorInterface $urlGenerator,
        private CheckUserCandidate $checkUserCandidate,
        private Environment $twig,
    ) {
    }

    public function __invoke(Request $request): Response
    {
        $userCandidate = new UserCandidate();
        $form = $this->formFactory->create(UserCandidateFormType::class, $userCandidate);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $credentialsAvailableResult = $this->checkUserCandidate->isCredentialsAvailable($userCandidate);
            if ($credentialsAvailableResult === true) {
                $userCandidate->setCreatedAt(now());
                $this->entityManager->persist($userCandidate);
                $this->entityManager->flush();
                $this->addFlash($request, 'success', 'flash.success.create');

                return new RedirectResponse($this->urlGenerator->generate('app_admin_user_list'));
            }
            $this->addFlash($request, 'fail', sprintf('admin.candidate.update.flash.abort_%s', $credentialsAvailableResult));
        }

        $candidates = $this->userCandidateRepository->findAll();
        $users = $this->userRepository->allUsersFiltered();

        return new Response($this->twig->render('admin/user/candidate_create.html.twig', [
            'form_candidate' => $form->createView(),
            'candidates' => $candidates,
            'users' => $users,
        ]));
    }
}
