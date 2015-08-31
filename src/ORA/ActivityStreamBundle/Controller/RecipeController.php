<?php
namespace ORA\ActivityStreamBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Util\Codes;
use FOS\RestBundle\Controller\Annotations;

use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use ORA\ActivityStreamBundle\Form\RecipeType;
use ORA\ActivityStreamBundle\Entity\Recipe;
use ORA\ActivityStreamBundle\Exception\InvalidFormException;


class RecipeController extends \FOS\RestBundle\Controller\FOSRestController
{
    /**
     * Presents the form to use to update a recipe.
     *
     * @ApiDoc(
     *   resource = true,
     *   statusCodes = {
     *     200 = "Returned when successful"
     *   }
     * )
     *
     * @Annotations\View()
     *
     * @return FormTypeInterface
     */
    public function editRecipeAction($id) 
    {
        $recipe = $this->getOr404($id);
        $form = $this->createForm(new RecipeType(),$recipe);
        
        return $form;
    }
    
   
    /**
    * Update existing Recipe from the submitted data or create a new Recipe at a specific location.
    *
    * @ApiDoc(
    *   resource = true,
    *   input = "ORA\ActivityStreamBundle\Form\RecipeType",
    *   statusCodes = {
    *     201 = "Returned when the Recipe is created",
    *     204 = "Returned when successful",
    *     400 = "Returned when the form has errors"
    *   }
    * )
    *
    * @Annotations\View(
    *  template = "ActivityStreamBundle:Recipe:editRecipe.html.twig",
    *  templateVar = "form"
    * )
    *
    * @param Request $request the request object
    * @param int     $id      the recipe id
    *
    * @return FormTypeInterface|View
    *
    * @throws NotFoundHttpException when page not exist
    */
   public function putRecipeAction(Request $request, $id)
   {
       try {
           if (!($recipe = $this->container->get('ora_activity_stream.recipe.handler')->get($id))) {
               $statusCode = Codes::HTTP_CREATED;
               $recipe = $this->container->get('ora_activity_stream.recipe.handler')->post(
                   $request->request->all()
               );
           } else {
               $statusCode = Codes::HTTP_NO_CONTENT;
               $recipe = $this->container->get('ora_activity_stream.recipe.handler')->put(
                   $recipe,
                   $request->request->all()
               );
           }

           $routeOptions = array(
               'id' => $recipe->getId(),
               '_format' => $request->get('_format')
           );
           $this->saveActivityStream($recipe,"update recipe");
           return $this->routeRedirectView('api_1_get_recipe', $routeOptions, $statusCode);

       } catch (InvalidFormException $exception) {

           return $exception->getForm();
       }
   }
    /**
     * Get single Recipe,
     *
     * @ApiDoc(
     *   resource = true,
     *   description = "Gets a Recipe for a given id",
     *   output = "ORA\ActivityStreamBundle\Entity\Recipe",
     *   statusCodes = {
     *     200 = "Returned when successful",
     *     404 = "Returned when the recipe is not found"
     *   }
     * )
     *
     * @Annotations\View(templateVar="recipe")
     *
     * @param int     $id      the recipe id
     *
     * @return array
     *
     * @throws NotFoundHttpException when recipe not exist
     */
    public function getRecipeAction($id)
    {
        $recipe = $this->getOr404($id);

        return $recipe;
    }
    
    /**
     * Fetch a Recipe or throw an 404 Exception.
     *
     * @param mixed $id
     *
     * @return Recipe
     *
     * @throws NotFoundHttpException
     */
    protected function getOr404($id)
    {
        if (!($recipe = $this->container->get('ora_activity_stream.recipe.handler')->get($id))) {
            throw new NotFoundHttpException(sprintf('The resource \'%s\' was not found.',$id));
        }

        return $recipe;
    }
    
    /**
     * Presents the form to use to create a new recipe.
     *
     * @ApiDoc(
     *   resource = true,
     *   statusCodes = {
     *     200 = "Returned when successful"
     *   }
     * )
     *
     * @Annotations\View()
     *
     * @return FormTypeInterface
     */
    public function newRecipeAction()
    {
        return $this->createForm(new RecipeType());
    }
    
    /**
     * Create a Recipe from the submitted data.
     *
     * @ApiDoc(
     *   resource = true,
     *   description = "Creates a new Recipe from the submitted data.",
     *   input = "ORA\ActivityStreamBundle\Form\RecipeType",
     *   statusCodes = {
     *     200 = "Returned when successful",
     *     400 = "Returned when the form has errors"
     *   }
     * )
     *
     * @Annotations\View(
     *  template = "ActivityStreamBundle:Recipe:newRecipe.html.twig",
     *  statusCode = Codes::HTTP_BAD_REQUEST
     * )
     *
     * @return FormTypeInterface|RouteRedirectView
     */
    public function postRecipeAction()
    {
        try {
            
            $newRecipe = $this->container->get('ora_activity_stream.recipe.handler')->post(
                    $this->container->get('request')->request->all()
            );
            //TODO: adding those activity to activity stream
            $this->saveActivityStream($newRecipe,"edit recipe");
            $routeOptions = array(
                'id' => $newRecipe->getId(),
                '_format' => $this->container->get('request')->get('_format')
            );

            return $this->routeRedirectView('api_1_get_recipe', $routeOptions, Codes::HTTP_CREATED);

        } catch (InvalidFormException $exception) {

            return array('form' => $exception->getForm());
        }
    }
    
    private function saveActivityStream($recipe,$reason)
    {
        $user = $this->getUser();

        if (!($as = $this->container
                ->get('ora_activity_stream.activity_stream.handler')
                ->save($user, $recipe, $reason, "api_1_get_recipe"))) {
            
            throw new NotFoundHttpException(sprintf('No activity streams were not found.'));
        }
    }
}
