<?php

namespace App\Models;

use App\Traits\HasCompany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * App\Models\Product
 *
 * @property int $id
 * @property string $name
 * @property string $price
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @mixin \Eloquent
 * @property-read \App\Models\CostCategory|null $category
 */
class Cost extends BaseModel
{

    use HasCompany;
    protected $table = 'costs';

    public function category(): BelongsTo
    {
        return $this->belongsTo(CostCategory::class, 'category_id');
    }

}
