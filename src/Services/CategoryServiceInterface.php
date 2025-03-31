<?php

namespace App\Services;

use App\Entity\Category;

interface CategoryServiceInterface extends EntityServicesInterface
{
    /**
      * Get category by category id
      * Ensures type safety
       * @param int $id
       * @return \App\Entity\Category
      */
    public function getCategory(int $id): Category;
}
