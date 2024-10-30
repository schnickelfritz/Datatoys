<?php

declare(strict_types=1);

namespace App\Service\Email;

use Psr\Log\LoggerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;

final readonly class SendEmailFromNoreply
{
    public function __construct(
        private MailerInterface $mailer,
        private LoggerInterface $logger,
        #[Autowire('%app.noreply_email%')]
        private string $noreplyEmail,
        #[Autowire('%app.title%')]
        private string $appTitle,
    ) {
    }

    /**
     * @param array<string, string> $context
     */
    public function send(string $to, string $subject, string $template, array $context): bool
    {
        $from = new Address($this->noreplyEmail, $this->appTitle);
        $mail = (new TemplatedEmail())
            ->from($from)
            ->to($to)
            ->subject('[' . $this->appTitle . '] ' . $subject)
            ->htmlTemplate($template)
            ->context($context)
        ;
        try {
            $this->mailer->send($mail);

            return true;
        } catch (TransportExceptionInterface $e) {
            $this->logger->error('email send fail');

            return false;
        }
    }
}
