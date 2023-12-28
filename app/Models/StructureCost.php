<?php

namespace App\Models;

use Carbon\Carbon;
use App\Traits\HasCompany;
use App\Scopes\ActiveScope;
use App\Models\ProjectCategory;
use App\Traits\CustomFieldsTrait;
use Illuminate\Support\Facades\DB;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * App\Models\StructureCost
 *
 * @property \Illuminate\Support\Carbon $valid_till

 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\StructureCostItem[] $items
 * @property-read int|null $items_count
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[] $notifications
 * @mixin \Eloquent
 * @property string|null $hash
 * @property int|null $unit_id
 * @method static \Illuminate\Database\Eloquent\Builder|StructureCost whereHash($value)
 * @property string $calculate_tax
 * @method static \Illuminate\Database\Eloquent\Builder|StructureCost whereCalculateTax($value)
 * @property string|null $description
 * @method static \Illuminate\Database\Eloquent\Builder|StructureCost whereDescription($value)
 * @property int|null $company_id
 * @property-read \App\Models\Company|null $company
 * @method static \Illuminate\Database\Eloquent\Builder|StructureCost whereCompanyId($value)
 */
class StructureCost extends BaseModel
{
    use Notifiable, HasCompany;

    protected $table = 'structure_costs';

    const CUSTOM_FIELD_MODEL = 'App\Models\StructureCost';

    public function items(): HasMany
    {
        return $this->hasMany(StructureCostItme::class, 'structure_cost_id');
    }

}
