<?php
/**
 * Created by PhpStorm.
 * User: igorweigel
 * Date: 06.10.2019
 * Time: 15:01
 */

namespace Igoooor\UserBundle\Factory;

use Igoooor\UserBundle\Model\UserInterface;
use Igoooor\UserBundle\Utils\PasswordGenerator;

/**
 * Class SuperAdminUserFactory
 */
class SuperAdminUserFactory
{
    /**
     * @var PasswordGenerator
     */
    private $passwordGenerator;
    /**
     * @var string
     */
    private $userClass;

    /**
     * SuperAdminUserFactory constructor.
     *
     * @param PasswordGenerator $passwordGenerator
     * @param string            $userClass
     */
    public function __construct(PasswordGenerator $passwordGenerator, string $userClass)
    {
        $this->passwordGenerator = $passwordGenerator;
        $this->userClass = $userClass;
    }

    /**
     * @return UserInterface
     */
    public function create(): UserInterface
    {
        /** @var UserInterface $user */
        $user = new $this->userClass();
        $user->setEmail('admin@exemple.fr');
        $user->setPlainPassword($this->passwordGenerator->generate(18));
        $user->addRole(UserInterface::ROLE_SUPER_ADMIN);

        return $user;
    }
}
