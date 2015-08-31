<?php
namespace ORA\ActivityStreamBundle\Handler;

use ORA\ActivityStreamBundle\Handler\RecipeHandlerInterface;
use ORA\ActivityStreamBundle\Entity\Recipe;
use ORA\ActivityStreamBundle\Form\RecipeType;
use ORA\ActivityStreamBundle\Exception\InvalidFormException;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Form\FormFactoryInterface;

class RecipeHandler implements RecipeHandlerInterface
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
    
    /**
    * Edit a Recipe, or create if not exist.
    *
    * @param Recipe $recipe
    * @param array  $parameters
    *
    * @return Recipe
    */
    public function put(Recipe $recipe, array $parameters)
    {
        return $this->processForm($recipe, $parameters, 'PUT');
    }

    
    

    public function get($id)
    {
        return $this->repository->find($id);
    }
    
    public function post(array $parameters)
    {
        $recipe = new $this->entityClass();
        return $this->processForm($recipe, $parameters, "POST");
    }
    
    /**
     * Processes the form.
     *
     * @param Recipe $recipe
     * @param array         $parameters
     * @param String        $method
     *
     * @return Recipe
     *
     * @throws \ORA\ActivityStreamBundle\Exception\InvalidFormException
     */
    private function processForm(Recipe $recipe, array $parameters, $method = "PUT")
    {
        $form = $this->formFactory->create(new RecipeType(), $recipe, array('method' => $method));
        $form->submit($parameters, 'PATCH' !== $method);
        if ($form->isValid()) {

            $recipe = $form->getData();
            $this->om->persist($recipe);
            $this->om->flush($recipe);
            
            return $recipe;
        }

        throw new InvalidFormException('Invalid submitted data', $form);
    }
}
