<?php

namespace Diside\ProfileBundle\Doctrine;

use Doctrine\Common\EventSubscriber;
use Doctrine\Common\Persistence\Event\LifecycleEventArgs;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use Doctrine\ORM\Events;
use Diside\ProfileBundle\Model\UserInterface;
use Diside\ProfileBundle\Model\UserManager;
use Symfony\Component\DependencyInjection\ContainerInterface;

class UserListener implements EventSubscriber
{
    /**
     * @var UserManager
     */
    private $userManager;

    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * Constructor
     *
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function getSubscribedEvents()
    {
        return array(
            Events::prePersist,
            Events::preUpdate,
        );
    }

    /**
     * @param LifecycleEventArgs $args
     */
    public function prePersist($args)
    {
        $object = $args->getEntity();
        if ($object instanceof UserInterface) {
            $this->updateUserFields($object);
        }
    }

    /**
     * @param PreUpdateEventArgs $args
     */
    public function preUpdate($args)
    {
        $object = $args->getEntity();
        if ($object instanceof UserInterface) {
            $this->updateUserFields($object);
            // We are doing a update, so we must force Doctrine to update the
            // changeset in case we changed something above
            $em   = $args->getEntityManager();
            $uow  = $em->getUnitOfWork();
            $meta = $em->getClassMetadata(get_class($object));
            $uow->recomputeSingleEntityChangeSet($meta, $object);
        }
    }

    /**
     * This must be called on prePersist and preUpdate if the event is about a
     * user.
     *
     * @param UserInterface $user
     */
    protected function updateUserFields(UserInterface $user)
    {
        if (null === $this->userManager) {
            $this->userManager = $this->container->get('profile.user_manager');
        }

        $this->userManager->updatePassword($user);
    }
}
