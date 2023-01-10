<?php

namespace App\Models;

use App\Helpers\Constants;
use Illuminate\Database\Eloquent\Model;

class Ads extends Model
{
    protected $table = Constants::TABLE_ADS;
    public $timestamps = true;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'link',
        'url',
        'start_date',
        'end_date',
        'sort',
        'status',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [];

    /**
     * Trả về đường dẫn full cho ảnh
     *
     * @param $value
     * @return string
     */
    public function getImageAttribute($value)
    {
        if (empty($value)) {
            $value = Constants::DEFAULT_APP_ICON;
        }

        return asset($value);
    }
}
