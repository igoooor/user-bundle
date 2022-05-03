<?php
/**
 * Created by PhpStorm.
 * User: igorweigel
 * Date: 07.10.2019
 * Time: 05:51
 */

namespace Igoooor\UserBundle\Repository;

use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\ORMException;
use Igoooor\UserBundle\Model\UserInterface;

/**
 * Class UserRepositoryInterface
 */
interface UserRepositoryInterface
{
    /**
     * @return bool
     *
     * @throws NonUniqueResultException
     */
    public function hasSuperAdmin(): bool;

    public function save(...$entities): void;

    /**
     * Finds a single entity by a set of criteria.
     *
     * @param array      $criteria
     * @param array|null $orderBy
     *
     * @return object|null The entity instance or NULL if the entity can not be found.
     */
    public function findOneBy(array $criteria, array $orderBy = null);
}
