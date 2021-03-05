<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Interfaces\Frontend\Repositories\IPageRepository;

class PagesController extends Controller
{
    private $page_repo;

    /**
     * Construct pages repository interface.
     *
     * @param IPageRepository $page_repo
     */
    public function __construct(IPageRepository $page_repo)
    {
        $this->page_repo = $page_repo;
    }
    /**
     * Display all static pages view.
     *
     * @param string $slug
     * @return Illuminate\Support\Facades\View (frontend.page, compact('page'))
     */
    public function index($slug)
    {
        try {
            $page = $this->page_repo->page_type($slug);
            return ((!$page)? redirect_to(
                'frontend.index',
                'Oops, No such page',
                'danger'
            ): view('frontend.page', compact('page')));
        } catch (\Throwable $th) {
            return redirect_with_msg(
                'frontend.index',
                'Oops, Something went wrong',
                'danger'
            );
        }
    }
}
