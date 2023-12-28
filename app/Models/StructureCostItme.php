<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * App\Models\StrutureCostItem
 *
 * @property int $id
 * @property int $estimate_id
 * @property string $item_name
 * @property string|null $item_summary
 * @property string $type
 * @property float $quantity
 * @property float $unit_price
 * @property float $amount
 * @property string|null $taxes
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $hsn_sac_code
 * @property-read mixed $icon
 * @method static \Illuminate\Database\Eloquent\Builder|StrutureCostItem newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|StrutureCostItem newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|StrutureCostItem query()
 * @method static \Illuminate\Database\Eloquent\Builder|StrutureCostItem whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StrutureCostItem whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StrutureCostItem whereEstimateId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StrutureCostItem whereHsnSacCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StrutureCostItem whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StrutureCostItem whereItemName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StrutureCostItem whereItemSummary($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StrutureCostItem whereQuantity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StrutureCostItem whereTaxes($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StrutureCostItem whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StrutureCostItem whereUnitPrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StrutureCostItem whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property-read mixed $tax_list
 */
class StructureCostItme extends BaseModel
{

    protected $guarded = ['id'];

    protected $table = 'structure_cost_items';
}
