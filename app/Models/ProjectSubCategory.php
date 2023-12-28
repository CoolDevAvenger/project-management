<?php

namespace App\Models;

use App\Traits\HasCompany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * App\Models\ProjectSubCategory
 *
 * @property int $id
 * @property int $category_id
 * @property string $category_name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\ProjectCategory $category
 * @property-read mixed $icon
 * @method static \Illuminate\Database\Eloquent\Builder|ProjectSubCategory newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ProjectSubCategory newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ProjectSubCategory query()
 * @method static \Illuminate\Database\Eloquent\Builder|ProjectSubCategory whereCategoryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProjectSubCategory whereCategoryName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProjectSubCategory whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProjectSubCategory whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProjectSubCategory whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property int|null $company_id
 * @property-read \App\Models\Company|null $company
 * @method static \Illuminate\Database\Eloquent\Builder|ProjectSubCategory whereCompanyId($value)
 */

class ProjectSubCategory extends BaseModel
{

    use HasCompany;

    protected $table = 'project_sub_category';

    public function category(): BelongsTo
    {
        return $this->belongsTo(ProjectCategory::class, 'category_id');
    }

}
