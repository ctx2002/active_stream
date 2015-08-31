<?php
namespace ORA\ActivityStreamBundle\Entity;

use ORA\ActivityStreamBundle\Model\ActivityStreamInterface;
use ORA\ActivityStreamBundle\Model\ActivityInterface;
use ORA\UserBundle\Entity\User;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="ora_activity_stream")
 */
class ActivityStream
{
     /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;
    
    /**
     * @ORM\Column(name="class", type="string", length=255, nullable=false)
     */
    protected $class;
    
    /**
     * @ORM\Column(name="entity_id", type="integer", nullable=false)
     */
    protected $entityId;
    
    /**
     * @ORM\Column(name="entity_route_name", type="string", length=255, nullable=false)
     */
    protected $entityRouteName;
    
    /**
     * @ORM\Column(name="when_did_it", type="datetime", nullable=true)
     */
    protected $whenDidIt;
    /**
     * @ORM\ManyToOne(targetEntity="\ORA\UserBundle\Entity\User")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    protected $whoDidIt;
    /**
     * @ORM\Column(name="what_they_did", type="text")
     */
    protected $whatTheyDid;
    
    function getWhenDidIt() {
        return $this->whenDidIt;
    }

    function getWhoDidIt() {
        return $this->whoDidIt;
    }

    function getWhatTheyDid() {
        return $this->whatTheyDid;
    }

    function setWhenDidIt(\DateTime $whenDidIt = null) {
        if (!is_null($whenDidIt)) {
            $this->whenDidIt = $whenDidIt;
        } else {
            $this->whenDidIt = new \DateTime("now");
        }
    }

    function setWhoDidIt(User $whoDidIt) {
        $this->whoDidIt = $whoDidIt;
    }

    function setWhatTheyDid($whatTheyDid) {
        $this->whatTheyDid = $whatTheyDid;
    }
    function getId() {
        return $this->id;
    }

    function getClass() {
        return $this->class;
    }

    function setId($id) {
        $this->id = $id;
    }

    function setClass($class) {
        $this->class = $class;
    }
    function getEntityId() {
        return $this->entityId;
    }

    function getEntityRouteName() {
        return $this->entityRouteName;
    }

    function setEntityId($entityId) {
        $this->entityId = $entityId;
    }

    function setEntityRouteName($entityRouteName) {
        $this->entityRouteName = $entityRouteName;
    }


}
