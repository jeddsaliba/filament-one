<?php

namespace App\Http\Controllers;

use App\Models\PageBuilder;

class PageBuilderController extends Controller
{
    /**
     * Fetch a page from PageBuilder and render it.
     *
     * @param string $slug The slug of the page to fetch.
     *
     * @return \Illuminate\View\View
     */
    public function __invoke(string $slug)
    {
        $page = PageBuilder::where(['slug' => $slug, 'is_active' => true])->firstOrFail();
        return view('page-builder', $page);
    }
}
