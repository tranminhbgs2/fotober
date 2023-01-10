<?php

namespace App\Http\Controllers\Cms;

use App\Http\Controllers\Controller;
use App\Repositories\Service\ServiceRepo;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    protected $serviceRepo;

    public function __construct(ServiceRepo $serviceRepo)
    {
        $this->middleware('auth');
        $this->serviceRepo = $serviceRepo;
    }

    public function listing(Request $request)
    {
        //
    }

    /**
     * /services/
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function listingAjax(Request $request)
    {
        $params['keyword'] = request('keyword', null);
        $params['status'] = request('status', -1);
        $params['page_index'] = request('page_index', 1);
        $params['page_size'] = request('page_size', 10);
        //
        $data['data'] = $this->serviceRepo->listing($params);
        return view('themes/cms/ace/pages/dashboard/ajax/customer-ajax-service', $data);
    }

}
