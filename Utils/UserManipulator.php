<?php
/**
 * Created by PhpStorm.
 * User: igorweigel
 * Date: 05.02.2020
 * Time: 09:28
 */

namespace Igoooor\UserBundle\Utils;

use Igoooor\UserBundle\Repository\UserRepositoryInterface;
use Igoooor\UserBundle\Model\UserInterface;

/**
 * Class UserManipulator
 */
class UserManipulator
{
    /**
     * @var UserRepositoryInterface
     */
    private $userRepository;
    /**
     * @var string
     */
    private $userClass;

    /**
     * UserManipulator constructor.
     *
     * @param UserRepositoryInterface $userRepository
     * @param string                  $userClass
     */
    public function __construct(UserRepositoryInterface $userRepository, string $userClass)
    {
        $this->userRepository = $userRepository;
        $this->userClass = $userClass;
    }

    /**
     * Creates a user and returns it.
     *
     * @param string $email
     * @param string $password
     * @param array  $roles
     *
     * @return UserInterface
     *
     * @throws \Doctrine\ORM\ORMException
     */
    public function create($email, $password, array $roles): UserInterface
    {
        $class = $this->userClass;
        /** @var UserInterface $user */
        $user = $class::create($email, $password);
        foreach ($roles as $role) {
            $user->addRole($role);
        }

        $this->userRepository->save($user);

        return $user;
    }

    /**
     * Changes the password for the given user.
     *
     * @param string $email
     * @param string $password
     *
     * @throws \Doctrine\ORM\ORMException
     */
    public function changePassword($email, $password): void
    {
        $user = $this->findUserByEmailOrThrowException($email);
        $user->setPlainPassword($password);
        $this->userRepository->save($user);
    }

    /**
     * Finds a user by his email and throws an exception if we can't find it.
     *
     * @param string $email
     *
     * @throws \InvalidArgumentException When user does not exist
     *
     * @return UserInterface|object
     */
    private function findUserByEmailOrThrowException($email): UserInterface
    {
        $user = $this->userRepository->findOneBy([
            'email' => $email,
        ]);

        if (!$user) {
            throw new \InvalidArgumentException(sprintf('User identified by "%s" email does not exist.', $email));
        }

        return $user;
    }
}
