<?php
namespace ORA\ActivityStreamBundle\Handler;
use ORA\ActivityStreamBundle\Entity\Recipe;

interface RecipeHandlerInterface
{
    /**
     * Get a recipe
     * @api
     *
     * @param mixed $id
     *
     * @return Recipe
     */
    public function get($id);


    /**
     * Post Recipe
     *
     * @param array $parameters
     *
     * @return Recipe
     */
    public function post(array $parameters);
}
