<?php
/**
 * Created by PhpStorm.
 * User: igorweigel
 * Date: 04.04.19
 * Time: 13:13
 */

namespace Igoooor\UserBundle\Listener;

use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Igoooor\UserBundle\Model\UserInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * Class HashPasswordListener
 */
class HashPasswordListener implements EventSubscriber
{
    /**
     * @var UserPasswordEncoderInterface
     */
    private $passwordEncoder;

    /**
     * HashPasswordListener constructor.
     *
     * @param UserPasswordEncoderInterface $passwordEncoder
     */
    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    /**
     * @return array
     */
    public function getSubscribedEvents(): array
    {
        return [
            'prePersist',
            'preUpdate',
        ];
    }

    /**
     * @param LifecycleEventArgs $args
     */
    public function prePersist(LifecycleEventArgs $args): void
    {
        $entity = $args->getEntity();
        if (!$entity instanceof UserInterface) {
            return;
        }

        $this->encodePassword($entity);
    }

    /**
     * @param LifecycleEventArgs $args
     */
    public function preUpdate(LifecycleEventArgs $args): void
    {
        $entity = $args->getEntity();
        if (!$entity instanceof UserInterface) {
            return;
        }

        $this->encodePassword($entity);
        // necessary to force the update to see the change
        $em = $args->getEntityManager();
        $meta = $em->getClassMetadata(get_class($entity));
        $em->getUnitOfWork()->recomputeSingleEntityChangeSet($meta, $entity);
    }

    /**
     * @param UserInterface $entity
     */
    private function encodePassword(UserInterface $entity): void
    {
        $plainPassword = $entity->getPlainPassword();
        if (null === $plainPassword) {
            return;
        }
        $encoded = $this->passwordEncoder->encodePassword(
            $entity,
            $plainPassword
        );
        $entity->setPassword($encoded);
    }
}
