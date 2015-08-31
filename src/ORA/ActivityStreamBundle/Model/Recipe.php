<?php
namespace ORA\ActivityStreamBundle\Model;
use ORA\ActivityStreamBundle\Model\ActivityInterface;
use ORA\ActivityStreamBundle\Entity\Recipe;
use ORA\ActivityStreamBundle\Entity\ActivityStream;
use Doctrine\Common\Persistence\ObjectManager;
use ORA\UserBundle\Entity\User;
class Recipe implements ActivityInterface 
{
    private $recipe;
    public function __construct(Recipe $recipe)
    {
        $this->recipe = $recipe;
    }
    
    public function saveIntoActivityStream(ObjectManager $om,$reason, User $user)
    {
        $as = new ActivityStream();
        $as->setWhatTheyDid($reason);
        $as->setWhoDidIt($user);
        $as->setWhenDidIt();
        $om->persist($as);
        $om->flush();
    }
}
