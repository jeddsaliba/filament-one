<?php

namespace App\Http\Controllers;

use App\Models\PageBuilder;
use Illuminate\Http\Request;

class PageBuilderController extends Controller
{
    public function __invoke(string $slug)
    {
        $page = PageBuilder::where(['slug' => $slug, 'is_active' => true])->firstOrFail();
        return view('page-builder', $page);
    }
}
