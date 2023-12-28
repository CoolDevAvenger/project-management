<?php

namespace App\Models;

use App\Traits\HasCompany;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * App\Models\CostCategory
 *
 * @property int $id
 * @property string $category_name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read mixed $icon
 * @method static \Illuminate\Database\Eloquent\Builder|CostCategory newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CostCategory newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CostCategory query()
 * @method static \Illuminate\Database\Eloquent\Builder|CostCategory whereCategoryName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CostCategory whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CostCategory whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CostCategory whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property int|null $company_id
 * @property-read \App\Models\Company|null $company
 * @method static \Illuminate\Database\Eloquent\Builder|CostCategory whereCompanyId($value)
 */
class CostCategory extends BaseModel
{

    use HasCompany;

    protected $table = 'cost_category';

}
