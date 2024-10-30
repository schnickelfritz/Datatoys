<?php

declare(strict_types=1);

namespace App\Service\Email;

use Symfony\Component\DependencyInjection\Attribute\Autowire;

use function sprintf;

final readonly class SendContactEmail
{
    public function __construct(
        private SendEmailFromNoreply $emailFromNoreply,
        #[Autowire('%app.admin_email%')]
        private string $adminEmail,
    ) {
    }

    public function send(string $emailText, string $userEmailAddress, string $about): bool
    {
        return $this->emailFromNoreply->send(
            $this->adminEmail,
            sprintf('[Admin] Kontakt: %s', $about),
            'email/contact_email.html.twig',
            ['text' => $emailText, 'user_email_address' => $userEmailAddress, 'about' => $about],
        );
    }
}
