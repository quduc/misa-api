<?php

namespace App\Http\Controllers\User;

use App\Helpers\MetaSeoRender;
use App\Http\Controllers\Controller;
use App\Services\Api\SearchHistoryService;
use App\Traits\HasResponse;
use Illuminate\Http\Request;

class SearchHistoryController extends Controller
{
    use HasResponse;

    public function __construct(private SearchHistoryService $searchHistoryService)
    {
    }

    public function index()
    {
        $params['user_id'] = auth()->id();
        $searchHistories   = $this->searchHistoryService->getList($params);
        $viewData          = [
            'meta_seo'        => MetaSeoRender::setMetaSeo(['meta_title' => '最近検索した条件']),
            'searchHistories' => $searchHistories
        ];
        return view('pages.search_history.index', $viewData);
    }

    public function delete($id)
    {
        $result = $this->searchHistoryService->delete($id);
        return $result ? $this->responseSuccess() : $this->responseError();
    }
}
