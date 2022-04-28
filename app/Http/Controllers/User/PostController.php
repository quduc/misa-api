<?php

namespace App\Http\Controllers\User;

use App\Helpers\MetaSeoRender;
use App\Http\Controllers\Controller;
use App\Services\Api\PostService;
use App\Services\Api\TagService;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function __construct(
        private PostService $postService,
        private TagService  $tagService)
    {
    }

    public function index(Request $request)
    {
        $params   = $request->query();
        $metaSeo  = [
            'meta_title' => 'コラム一覧'
        ];
        $viewData = [
            'meta_seo'      => MetaSeoRender::setMetaSeo($metaSeo),
            'tags'          => $this->tagService->getList(),
            'posts'         => $this->postService->getListPaginate($params),
            'popular_posts' => $this->postService->getListPopular(),
        ];
        return view('pages.post.index')->with($viewData);
    }

    public function show(int $id)
    {
        $post = $this->postService->show($id);
        $post->countView();
        $metaSeo  = [
            'meta_title' => $post->title,
        ];
        $params   = [];
        $viewData = [
            'meta_seo'      => MetaSeoRender::setMetaSeo($metaSeo),
            'tags'          => $this->tagService->getList(),
            'post'          => $post,
            'post_relateds' => $this->postService->getListRelated($params, 5),
        ];
        return view('pages.post.detail')->with($viewData);
    }
}
