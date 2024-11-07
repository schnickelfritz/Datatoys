<?php

namespace App\Service\UserSetting;

use App\Entity\UserSetting;
use App\Enum\UserSettingEnum;
use App\Repository\UserSettingRepository;
use App\Service\User\Me;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Webmozart\Assert\Assert;

final readonly class SetUserSetting
{
    private const SESSION_PREFIX = 'USERSETTING_';

    public function __construct(
        private RequestStack $requestStack,
        private UserSettingRepository $userSettingRepository,
        private EntityManagerInterface $entityManager,
        private Me $me,
    )
    {
    }

    public function setSetting(UserSettingEnum $key, string|int $value): void
    {
        $user = $this->me->user();
        if ($user === null) {
            return;
        }
        
        $value = (string) $value;
        $setting = $this->userSettingRepository->findOneBy(['user' => $user, 'settingKey' => $key->value]);

        if (!$setting instanceof UserSetting) {
            $setting = new UserSetting();
            $setting
                ->setUser($user)
                ->setSettingKey($key->value)
            ;
        }

        if ($setting->getSettingValue() !== $value) {
            $setting->setSettingValue($value);
            $this->entityManager->persist($setting);
            $this->entityManager->flush();
        }

        $sessionkey = sprintf('%s%s', self::SESSION_PREFIX, $key->value);
        $value = (string) $value;
        $session = $this->requestStack->getSession();
        $session->set($sessionkey, $value);
    }

}