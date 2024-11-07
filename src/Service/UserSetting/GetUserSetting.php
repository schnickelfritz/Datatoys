<?php

namespace App\Service\UserSetting;

use App\Entity\UserSetting;
use App\Enum\UserSettingEnum;
use App\Repository\UserSettingRepository;
use App\Service\User\Me;
use Symfony\Component\HttpFoundation\RequestStack;
use Webmozart\Assert\Assert;

final readonly class GetUserSetting
{
    private const SESSION_PREFIX = 'USERSETTING_';

    public function __construct(
        private RequestStack $requestStack,
        private UserSettingRepository $userSettingRepository,
        private Me $me,
    )
    {
    }

    public function getSetting(UserSettingEnum $key, ?string $returnIfNoValue = null): ?string
    {
        $sessionValue = $this->getSettingInSession($key);
        if ($sessionValue !== null) {
            return $sessionValue;
        }
        $user = $this->me->user();
        $setting = $this->userSettingRepository->findOneBy(['user' => $user, 'settingKey' => $key->value]);
        if (!$setting instanceof UserSetting) {
            return $returnIfNoValue;
        }

        return $setting->getSettingValue();
    }

    private function getSettingInSession(UserSettingEnum $key): ?string
    {
        $session = $this->requestStack->getSession();
        $sessionkey = sprintf('%s%s', self::SESSION_PREFIX, $key->value);
        if (!$session->has($sessionkey)) {
            return null;
        }
        $value = $session->get($sessionkey);
        Assert::string($value, 'supposed to be string');

        return $value;
    }
}