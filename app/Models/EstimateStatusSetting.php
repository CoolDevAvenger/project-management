<?php

namespace App\Models;

use App\Traits\HasCompany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * App\Models\EstimateStatusSetting
 *
 * @property int $id
 * @property int|null $company_id
 * @property string $status_name
 * @property string $color
 * @property string $status
 * @property string $default_status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Company|null $company
 * @method static \Illuminate\Database\Eloquent\Builder|EstimateStatusSetting newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|EstimateStatusSetting newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|EstimateStatusSetting query()
 * @method static \Illuminate\Database\Eloquent\Builder|EstimateStatusSetting whereColor($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EstimateStatusSetting whereCompanyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EstimateStatusSetting whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EstimateStatusSetting whereDefaultStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EstimateStatusSetting whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EstimateStatusSetting whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EstimateStatusSetting whereStatusName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EstimateStatusSetting whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class EstimateStatusSetting extends BaseModel
{

    use HasFactory, HasCompany;

    const ACTIVE = '1';
    const INACTIVE = '0';

    const COLUMNS = [
        ['status_name' => 'waiting', 'color' => '#FFC303', 'status' => 'active', 'default_status' => self::ACTIVE],
        ['status_name' => 'accepted', 'color' => '#679c0d', 'status' => 'active', 'default_status' => self::INACTIVE],
        ['status_name' => 'canceled', 'color' => '#d21010', 'status' => 'active', 'default_status' => self::INACTIVE],
    ];

    protected $fillable = ['status_name', 'color', 'status', 'default_status'];

}
