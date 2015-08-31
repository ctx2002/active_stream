<?php

namespace ORA\ActivityStreamBundle\Handler;
use ORA\ActivityStreamBundle\Handler\ActivityStreamHandlerInterface;
use ORA\UserBundle\Entity\User;
use ORA\ActivityStreamBundle\Model\ActivityInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Form\FormFactoryInterface;
use ORA\ActivityStreamBundle\Entity\ActivityStream;

class ActivityStreamHandler implements ActivityStreamHandlerInterface
{
    private $om;
    private $entityClass;
    private $repository;
    private $formFactory;

    public function __construct(ObjectManager $om, $entityClass, FormFactoryInterface $formFactory)
    {
        $this->om = $om;
        $this->entityClass = $entityClass;
        $this->repository = $this->om->getRepository($this->entityClass);
        $this->formFactory = $formFactory;
    }
    
    public function fetch($limit,$controller)
    {
        $list = $this->repository->findBy(array(),array('whenDidIt' => "DESC"),$limit);
        //var_dump($list);
//        $temp = array();
//        if ($list) {
//            foreach ($list as $item) {
//                $obj = new \stdClass();
//                $obj->whenDidIt = $item->getWhenDidIt();
//                $obj->whoDidIt = $item->getWhoDidIt()->getUsername();
//                $obj->class = $item->getClass();
//                $obj->whatTheyDid = $item->getWhatTheyDid();
//                
//                $repo = $this->om->getRepository($item->getClass());
//                if (!$repo) {
//                    continue;
//                } else {
//                    //$entity = $repo->find($item->getEntityId());
//                    $url = $controller->generateUrl(
//                        $item->getEntityRouteName(),
//                        array('id' => $item->getEntityId())
//                    );
//                    $obj->url = $url;
//                }
//                $temp[] = $obj;
//            }
//        }
        
        //var_dump($temp);
        return $list;
    }
    
    public function save(User $currentUser, $entity, $reason, $routeName)
    {
        $as = new ActivityStream();
        $as->setWhatTheyDid($reason);
        $as->setWhoDidIt($currentUser);
        $as->setWhenDidIt();
        $as->setClass(get_class($entity) );
        $as->setEntityId($entity->getId());
        $as->setEntityRouteName($routeName);
        $this->om->persist($as);
        $this->om->flush();
        return $as;
    }
}
