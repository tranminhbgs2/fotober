<?php

namespace App\Repositories\Slide;

use App\Models\Ads;
use App\Repositories\BaseRepo;

class SlideRepo extends BaseRepo
{

    public function __construct()
    {
        parent::__construct();
        //
    }

    /**
     * API tìm kiếm HS theo SSCID
     * URL: {{url}}/api/v1/students/search-by-sscid
     *
     * @param $params
     * @return array|null
     */
    public function listing($params)
    {
        $query = Ads::select(['id', 'image', 'action_type', 'record_id', 'redirect_to'])
            ->where('status', 1)
            ->take(10)
            ->skip(0)
            ->get();

        return $query->toArray();

    }


}
