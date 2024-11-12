<?php

namespace App\Controller\Grid\Gridfile;

use App\Service\Grid\Gridfile\UploadGridfile;
use App\Service\User\Me;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Webmozart\Assert\Assert;

#[AsController]
#[IsGranted('ROLE_GRIDADMIN')]
#[Route(path: '/grid/fileupload', methods: [Request::METHOD_POST])]
final readonly class GridfileUploadController
{
    public function __construct(
        private UploadGridfile $uploadGridfile,
        private Me $me,
    ) {
    }

    public function __invoke(Request $request): Response
    {
        $user = $this->me->user();
        if ($user === null) {
            return new Response('Denied', Response::HTTP_UNAUTHORIZED);
        }
        $uploadedFile = $request->files->get('file');
        Assert::isInstanceOf($uploadedFile, UploadedFile::class);
        $allowReplace = $request->request->getBoolean('allowReplace');
        $tableId = $request->request->getInt('tableId');
        $error = $this->uploadGridfile->upload($uploadedFile, $tableId, $allowReplace);
        if ($error !== null) {
            return new Response(sprintf('Fehlschlag: %s', $error), Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        return new Response('created', Response::HTTP_OK);
    }
}