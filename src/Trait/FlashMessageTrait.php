<?php

declare(strict_types=1);

namespace App\Trait;

use RuntimeException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\FlashBagAwareSessionInterface;

use function is_string;

trait FlashMessageTrait
{
    /**
     * @param string|string[] $message
     */
    protected function addFlash(Request $request, string $type, string|array $message): void
    {
        $session = $request->getSession();
        if (!$session instanceof FlashBagAwareSessionInterface) {
            throw new RuntimeException('Session must support flashBag');
        }
        if (is_string($message)) {
            $session->getFlashBag()->add($type, $message);

            return;
        }
        $html = '';
        foreach ($message as $messageItem) {
            $html .= '<li>' . $messageItem . '</li>';
        }
        $session->getFlashBag()->add($type, '<ul>' . $html . '</ul>');
    }
}
