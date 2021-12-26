<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PostController extends Controller
{
    function __construct()
    {
        $this->middleware(function ($request, $next) {
            session(['module_active' => 'post']);

            return $next($request);
        });
    }
    function list()
    {
        return view('admin.post.list');
    }
    function add()
    {
        return view('admin.post.add');
    }
    function cat_list()
    {
    return view('admin.post.cat_list');
    }
}
