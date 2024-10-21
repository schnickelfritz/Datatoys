<?php

namespace App\Service\User;

use App\Entity\UserCandidate;
use SymfonyCasts\Bundle\VerifyEmail\VerifyEmailHelperInterface;

final readonly class CreateSignedUrl
{
    public function __construct(
        private VerifyEmailHelperInterface $verifyEmailHelper,
    )
    {
    }

    public function getSignedUrl(UserCandidate $userCandidate, string $routeName): string
    {
        $userEmail = $userCandidate->getEmail();
        if (!$userEmail) {
            return '';
        }
        $signatureComponents = $this->verifyEmailHelper->generateSignature(
            $routeName,
            $userCandidate->getEmail(),
            $userEmail,
            ['id' => $userCandidate->getId()]
        );

        return $signatureComponents->getSignedUrl();
    }
}