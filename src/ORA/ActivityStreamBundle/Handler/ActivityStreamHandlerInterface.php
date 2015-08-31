<?php
namespace ORA\ActivityStreamBundle\Handler;
use ORA\UserBundle\Entity\User;
interface ActivityStreamHandlerInterface
{
    public function save(User $currentUser, $entity, $reason, $routeName);
}
