<?php
namespace ORA\ActivityStreamBundle\Model;

use ORA\ActivityStreamBundle\Model\ActivityInterface;

interface ActivityStreamInterface 
{
    /**
     * @param ActivityInterface $activity
     * @return ActivityStreamInterface
     * 
     * Save an object's activity.
     */
    public function save(ActivityInterface $activity);
    
    /**
     * @param int $limit how many activity to fetch,
     *   default to 20.
     * 
     * @return Array an array of ActivityStream objects.
     */
    public function fetch($limit = 20);
}
