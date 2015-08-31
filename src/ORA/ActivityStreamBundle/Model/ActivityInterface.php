<?php
namespace ORA\ActivityStreamBundle\Model;
use Doctrine\Common\Persistence\ObjectManager;
interface ActivityInterface 
{
    public function saveIntoActivityStream(ObjectManager $om, $reason);
}
