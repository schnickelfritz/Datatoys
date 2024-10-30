<?php

declare(strict_types=1);

namespace App\Service\Email;

use App\Entity\UserCandidate;
use App\Service\User\CreateSignedUrl;

final readonly class SendInviteEmail
{
    public function __construct(
        private SendEmailFromNoreply $emailFromNoreply,
        private CreateSignedUrl $createSignedUrl,
    ) {
    }

    public function send(UserCandidate $userCandidate): bool
    {
        $signedUrl = $this->createSignedUrl->getSignedUrl($userCandidate, 'app_user_create');

        return $this->emailFromNoreply->send(
            $userCandidate->getEmail(),
            'Einladung zur Teilnahme',
            'email/invite_email.html.twig',
            ['signed_url' => $signedUrl, 'user_name' => $userCandidate->getName()],
        );
    }
}
