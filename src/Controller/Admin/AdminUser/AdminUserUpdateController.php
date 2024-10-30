<?php

declare(strict_types=1);

namespace App\Controller\Admin\AdminUser;

use App\Entity\User;
use App\Form\User\UserFormType;
use App\Repository\UserCandidateRepository;
use App\Repository\UserRepository;
use App\Service\User\CheckUser;
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

#[AsController]
#[IsGranted('ROLE_USERMANAGER')]
#[Route('/admin/user/update/{id}', name: 'app_admin_user_update', methods: [Request::METHOD_GET, Request::METHOD_POST])]
final readonly class AdminUserUpdateController
{
    use FlashMessageTrait;

    public function __construct(
        private EntityManagerInterface $entityManager,
        private UserCandidateRepository $userCandidateRepository,
        private UserRepository $userRepository,
        private FormFactoryInterface $formFactory,
        private UrlGeneratorInterface $urlGenerator,
        private CheckUser $checkUser,
        private Environment $twig,
    ) {
    }

    public function __invoke(Request $request, User $user): Response
    {
        $form = $this->formFactory->create(UserFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $credentialsAvailableResult = $this->checkUser->isCredentialsAvailable($user);
            if ($credentialsAvailableResult === true) {
                $this->entityManager->persist($user);
                $this->entityManager->flush();
                $this->addFlash($request, 'success', 'flash.success.update');

                return new RedirectResponse($this->urlGenerator->generate('app_admin_user_update', ['id' => $user->getId()]));
            }
            $this->addFlash($request, 'fail', sprintf('Abort: %s', $credentialsAvailableResult));
        }

        $users = $this->userRepository->allUsersFiltered();
        $candidates = $this->userCandidateRepository->findAll();

        return new Response($this->twig->render('admin/user/update.html.twig', [
            'form_user' => $form->createView(),
            'users' => $users,
            'candidates' => $candidates,
            'user_selected' => $user,
        ]));
    }
}
