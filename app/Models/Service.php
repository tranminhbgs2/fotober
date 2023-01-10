<?php

namespace App\Models;

use App\Helpers\Constants;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    protected $table = Constants::TABLE_SERVICES;
    public $timestamps = true;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'code',
        'number',
        'image',
        'description',
        'link',
        'from_price',
        'group_code',
        'group_name',
        'before_photo',
        'after_photo',
        'video_link',
        'video_src',
        'read_more',
        'sort',
        'type',
        'status',
    ];

    /**
     * Trả về đường dẫn full cho ảnh đại diện
     *
     * @param $key
     * @return string|null
     */
    public function getImageAttribute($key)
    {
        if ($key) {
            if (strpos($key, 'http://') !== false || strpos($key, 'https://') !== false) {
                return $key;
            } else {
                return asset($key);
            }
        }

        return null;
    }

    /**
     * Trả về đường dẫn full cho ảnh trước
     *
     * @param $key
     * @return string|null
     */
    public function getBeforePhotoAttribute($key)
    {
        if ($key) {
            if (strpos($key, 'http://') !== false || strpos($key, 'https://') !== false) {
                return $key;
            } else {
                return asset($key);
            }
        }

        return null;
    }

    /**
     * Trả về đường dẫn full cho ảnh sau
     *
     * @param $key
     * @return string|null
     */
    public function getAfterPhotoAttribute($key)
    {
        if ($key) {
            if (strpos($key, 'http://') !== false || strpos($key, 'https://') !== false) {
                return $key;
            } else {
                return asset($key);
            }
        }

        return null;
    }

    /**
     * Trả về đường dẫn full cho video
     *
     * @param $key
     * @return string|null
     */
    public function getVideoLinkAttribute($key)
    {
        if ($key) {
            if (strpos($key, 'http://') !== false || strpos($key, 'https://') !== false) {
                return $key;
            } else {
                return asset($key);
            }
        }

        return null;
    }
}
