<?php

declare(strict_types=1);

namespace App\Controller\Grid\GridContent;

use App\Entity\Gridtable;
use App\Enum\UserSettingEnum;
use App\Form\Grid\GridContentCreateFormType;
use App\Repository\GridtableRepository;
use App\Service\Grid\Gridcontent\CreateGridContent;
use App\Service\Grid\Gridcontent\MapGridrowsContent;
use App\Service\UserSetting\SetUserSetting;
use App\Trait\FlashMessageTrait;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Twig\Environment;
use Webmozart\Assert\Assert;

#[AsController]
#[IsGranted('ROLE_GRIDADMIN')]
#[Route('/grid/content/create/{id}', name: 'app_grid_tablecontent_create', methods: [Request::METHOD_GET, Request::METHOD_POST])]
final readonly class GridContentCreateController
{
    use FlashMessageTrait;

    public function __construct(
        private CreateGridContent $createGridContent,
        private FormFactoryInterface $formFactory,
        private GridtableRepository $tableRepository,
        private MapGridrowsContent $mapGridrowsContent,
        private SetUserSetting $setUserSetting,
        private UrlGeneratorInterface $urlGenerator,
        private Environment $twig,
    ) {
    }

    public function __invoke(Request $request, Gridtable $table): Response
    {
        $this->setUserSetting->setSetting(UserSettingEnum::GRIDTABLE_ID, $table->getId());
        $form = $this->formFactory->create(GridContentCreateFormType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $content = $form->get('content')->getData();
            Assert::string($content);
            $separator = $form->get('separator')->getData();
            Assert::string($separator);
            $options = $form->get('options')->getData();
            Assert::isArray($options);
            $updateKey = $form->get('update_key')->getData();
            Assert::string($updateKey);

            $errorMessage = $this->createGridContent->processInputs($table, $content, $separator, $options, $updateKey);
            if ($errorMessage === null) {
                $this->addFlash($request, 'success', 'flash.success.done');

                return new RedirectResponse(
                    $this->urlGenerator->generate('app_grid_tablecontent_create', ['id' => $table->getId()])
                );
            }

            $this->addFlash($request, 'fail', $errorMessage);
        }

        $tables = $this->tableRepository->alltablesFiltered();
        $mappedRows = $this->mapGridrowsContent->mapRows($table);

        return new Response(
            $this->twig->render('grid/content/gridcontent_create.html.twig', [
                'form_content' => $form->createView(),
                'tables' => $tables,
                'mapped_rows' => $mappedRows,
                'table_selected' => $table,
            ])
        );
    }
}
