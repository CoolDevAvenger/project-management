<?php

namespace App\Observers;

use App\Models\ProjectSubCategory;

class ProjectSubCategoryObserver
{

    public function creating(ProjectSubCategory $projectSubCategory)
    {
        if (company()) {
            $projectSubCategory->company_id = company()->id;
        }
    }

}
