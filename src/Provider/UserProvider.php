<?php

declare(strict_types=1);

namespace Knp\DoctrineBehaviors\Provider;

use Knp\DoctrineBehaviors\Contract\Provider\UserProviderInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Security\Core\User\UserInterface;

final class UserProvider implements UserProviderInterface
{
    public function __construct(
        private Security $security,
        private ?string $blameableUserEntity = null
    ) {
    }

    /**
     * @return UserInterface|null
     */
    public function provideUser()
    {
        $token = $this->security->getToken();
        if ($token !== null) {
            $user = $token->getUser();
            if ($this->blameableUserEntity) {
                if ($user instanceof $this->blameableUserEntity) {
                    return $user;
                }
            } else {
                return $user;
            }
        }

        return null;
    }

    public function provideUserEntity(): ?string
    {
        $user = $this->provideUser();
        if ($user === null) {
            return null;
        }

        return $user::class;
    }
}
