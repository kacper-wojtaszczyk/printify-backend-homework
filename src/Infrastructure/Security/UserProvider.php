<?php

declare(strict_types=1);

namespace KacperWojtaszczyk\PrintifyBackendHomework\Infrastructure\Security;

use KacperWojtaszczyk\PrintifyBackendHomework\Model\User\Exception\ProductNotFoundException;
use KacperWojtaszczyk\PrintifyBackendHomework\Model\User\UserRepositoryInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

final class UserProvider implements UserProviderInterface
{
    /**
     * @var UserRepositoryInterface
     */
    private $userRepository;

    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function loadUserByUsername($username): UserInterface
    {
        if ($user = $this->userRepository->findOneByEmailAddress($username)) {
            return new User((string)$user->getEmail(), (string)$user->getPassword());
        }

        throw ProductNotFoundException::withEmail($username);
    }

    public function refreshUser(UserInterface $user): UserInterface
    {
        return $this->loadUserByUsername($user->getUsername());
    }

    public function supportsClass($class): bool
    {
        return $class instanceof User;
    }
}
