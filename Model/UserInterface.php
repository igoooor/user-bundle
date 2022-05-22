<?php

namespace Igoooor\UserBundle\Model;

use App\Entity\User;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface as BaseUserInterface;

interface UserInterface extends BaseUserInterface, PasswordAuthenticatedUserInterface
{
    public const ROLE_SUPER_ADMIN = 'ROLE_SUPER_ADMIN';
    public const ROLE_ADMIN = 'ROLE_ADMIN';

    /**
     * @return string|null
     */
    public function getId(): ?string;

    /**
     * @param string $plainPassword
     *
     * @return UserInterface
     */
    public function setPlainPassword(string $plainPassword): self;

    /**
     * @return string|null
     */
    public function getPlainPassword(): ?string;

    /**
     * @param string $email
     *
     * @return UserInterface
     */
    public function setEmail(string $email): UserInterface;

    /**
     * @param string $role
     *
     * @return UserInterface
     */
    public function addRole(string $role): UserInterface;

    /**
     * @param string $password
     *
     * @return User
     */
    public function setPassword(string $password): UserInterface;
}
