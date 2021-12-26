<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProductController extends Controller
{
    function __construct()
    {
        $this->middleware(function ($request, $next) {
            session(['module_active' => 'product']);

            return $next($request);
        });
    }
    function list()
    {
        return view('admin.product.list');
    }
    function add()
    {
        return view('admin.product.add');
    }
    function cat_list()
    {
        return view('admin.product.cat_list');
    }
}
