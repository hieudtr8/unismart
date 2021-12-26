<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PageController extends Controller
{
    function __construct()
    {
        $this->middleware(function ($request, $next) {
            session(['module_active' => 'page']);

            return $next($request);
        });
    }
    function list()
    {
        return view('admin.page.list');
    }
    function add(){
        return view('admin.page.add');
    }
}
