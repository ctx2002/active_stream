<?php
namespace ORA\ActivityStreamBundle\Controller;

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;

use FOS\RestBundle\Request\ParamFetcher;
use FOS\RestBundle\Controller\Annotations\QueryParam;

class ActivityStreamController extends FOSRestController
{
    /**
     * Get many activities,
     *
     * @ApiDoc(
     *   resource = true,
     *   description = "Get many activities, number can be specified.",
     *   output = "Doctrine\Common\Collections\ArrayCollection",
     *   statusCodes = {
     *     200 = "Returned when successful",
     *     404 = "Returned when the recipe is not found"
     *   }
     * )
     *
     * @Annotations\View(templateVar="ActivityStreams")
     *
     *
     *
     * @return Doctrine\Common\Collections\ArrayCollection
     *
     * @throws NotFoundHttpException when recipe not exist
     * 
     *  @QueryParam(name="limit", requirements="\d+", strict=false, description="how many item to fetch")
     */
    public function getActivityStreamsAction(ParamFetcher $paramFetcher)
    {
        $limit = $paramFetcher->get('limit');
        if (!$limit) {
            $limit = 20;
        }
        
        return $this->getOr404($limit);
    }
    
    /**
     * Fetch a Recipe or throw an 404 Exception.
     *
     * @param int $limit
     *
     * @return Mixed
     *
     * @throws NotFoundHttpException
     */
    protected function getOr404($limit)
    {
        if (!($as = $this->container
                ->get('ora_activity_stream.activity_stream.handler')->fetch($limit,$this))) {
            throw new NotFoundHttpException(sprintf('Activity streams were not found.'));
        }

        return $as;
    }
}
