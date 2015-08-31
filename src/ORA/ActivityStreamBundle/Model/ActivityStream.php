<?php
namespace ORA\ActivityStreamBundle\Model;

use ORA\ActivityStreamBundle\Model\ActivityStreamInterface;
use ORA\ActivityStreamBundle\Entity\ActivityStream;
class ActivityStream implements ActivityStreamInterface
{
    private $stream;
    public function __construct(ActivityStream $stream)
    {
        $this->stream = $stream;
    }
    
    public function save(ActivityInterface $activity)
    {
        
    }
    public function fetch($limit = 20)
    {
        
    }
}
