<?php

namespace App\Http\Controllers;

use App\CatProduct;
use App\Product;
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
    function list(Request $request)
    {
        $status =  $request->input('status');
        if ($status == 'inactive') {
            $products = Product::onlyTrashed()->paginate(10);
        } else {
            $keyword = "";
            if ($request->input('keyword')) {
                $keyword = $request->input('keyword');
            }
            $products = Product::where(
                'title',
                'LIKE',
                "%{$keyword}%"
            )->paginate(10);
        }
        $count_active =  Product::count();
        $count_inactive =  Product::onlyTrashed()->count();
        $cat = CatProduct::all();
        return view('admin.product.list', compact('products', 'cat', 'count_active', 'count_inactive', 'status'));
    }
    function add()
    {
        $cats = CatProduct::all();
        return view('admin.product.add', compact('cats'));
    }
    function store(Request $request)
    {
        $request->validate(
            [
                'title' => 'required|string|max:255',
                'price' => 'required|integer',
                'content' => 'required|string',
                'desc' => 'required|string',
                'cat' => 'gt:0'
            ],
            [
                'required' => ":attribute không được để trống",
                'string' => "Không sử dụng kí tự đặc biệt",
                'integer' => "Chỉ điền số",
                'min' => ":attribute phải ít nhất 6 ký tự",
                'gt' => 'Vui lòng chọn danh mục'
            ],
            [
                'title' => "Tên sản phẩm",
                'content' => "Chi tiết sản phẩm",
                'desc' => "Mô tả sản phẩm",
                'price' => "Giá",
                'cat' => "Danh mục",
            ],
        );
        if ($request->hasFile('file')) {
            $image = $request->file;
            $image_name = $image->getClientOriginalName();
            $image->move('public/uploads', $image_name);
            $thumbnail = 'public/uploads/' . $image_name;
        }
        // return $request->input('desc');
        Product::create([
            'title' => $request->input('title'),
            'content' =>  $request->input('content'),
            'cat_id' => $request->input('cat'),
            'status' => $request->input('status'),
            'price' => $request->input('price'),
            'desc' => $request->input('desc'),
            'thumbnail' => isset($thumbnail) ? $thumbnail : "",
            'created_at' => now(),
        ]);
        return redirect('/admin/product/list')->with('status', "Thêm sản phẩm mới thành công!");
    }
    function delete($id)
    {
        Product::find($id)->delete();
        return redirect('admin/product/list')->with('status', "Xóa sản phẩm tạm thời thành công!");
    }
    function forceDelete($id)
    {
        Product::withTrashed()->where('id', $id)->forceDelete();
        return redirect('admin/product/list')->with('status', "Xóa hoàn toàn sản phẩm thành công!");
    }
    function action(Request $request)
    {
        $list_check = $request->input('listCheck');
        if ($list_check) {
            if (!empty($list_check)) {
                $action = $request->input('action');
                if ($action == "delete") {
                    Product::destroy($list_check);
                    return redirect('/admin/product/list')->with('status', 'Xoá các bản ghi được chọn thành công!');
                }
                if ($action == "force_delete") {
                    Product::withTrashed()->whereIn('id', $list_check)->forceDelete();
                    return redirect('/admin/product/list')->with('status', 'Xoá vĩnh viễn các bản ghi được chọn thành công!');
                }
                if ($action == "restore") {
                    Product::withTrashed()->whereIn('id', $list_check)->restore();
                    return redirect('/admin/product/list')->with('status', 'Khôi phục các bản ghi được chọn thành công!');
                }
                if ($action == "active") {
                    Product::whereIn('id', $list_check)->update(
                        ['status' => 'active']
                    );
                    return redirect('/admin/product/list')->with('status', 'Công khai các bản ghi được chọn thành công!');
                }
                if ($action == "inactive") {
                    Product::whereIn('id', $list_check)->update(
                        ['status' => 'inactive']
                    );
                    return redirect('/admin/product/list')->with('status', 'Chờ duyệt các bản ghi được chọn thành công!');
                }
            }
        } else {
            return redirect('/admin/product/list')->with('error', 'Cần chọn bản ghi để thực hiện');
        }
    }
    function edit($id)
    {
        $product = Product::find($id);
        $cats = CatProduct::all();
        return view('admin.product.edit', compact('product', 'cats'));
    }
    function update(Request $request)
    {
        $request->validate(
            [
                'title' => 'required|string|max:255',
                'content' => 'required|string|max:255',
                'cat' => 'gt:0'
            ],
            [
                'required' => ":attribute không được để trống",
                'gt' => 'Vui lòng danh mục'
            ],
            [
                'title' => "Tiêu đề trang",
                'content' => "Nội dung trang",
                'cat' => 'Danh mục',
            ],
        );
        if ($request->hasFile('file')) {
            $image = $request->file;
            $image_name = $image->getClientOriginalName();
            $image->move('public/uploads', $image_name);
            $thumbnail = 'public/uploads/' . $image_name;
            Product::where('id', $request->id)
                ->update([
                    'title' => $request->input('title'),
                    'content' =>  $request->input('content'),
                    'cat_id' => $request->input('cat'),
                    'status' => $request->input('status'),
                    'thumbnail' => $thumbnail,
                    'updated_at' => now(),
                ]);
        } else {
            Product::where('id', $request->id)
                ->update([
                    'title' => $request->input('title'),
                    'content' =>  $request->input('content'),
                    'cat_id' => $request->input('cat'),
                    'status' => $request->input('status'),
                    'updated_at' => now(),
                ]);
        }
        $url = "/admin/product/edit/" . $request->id;
        return redirect($url)->with('status', "Chỉnh sửa thông tin sản phẩm thành công!");
    }
    // CAT
    function cat_list()
    {
        $cats =  CatProduct::all();
        return view('admin.product.cat_list', compact('cats'));
    }
    function store_cat_list(Request $request)
    {
        $request->validate(
            [
                'title' => 'required|string|max:255',
                'id' => 'required|string',
            ],
            [
                'required' => ":attribute không được để trống",
            ],
            [
                'title' => "Tên danh mục",
            ],
        );

        CatProduct::create([
            'title' => $request->input('title'),
            'id' => $request->input('id'),
        ]);
        return redirect('/admin/product/cat/list')->with('status', "Thêm danh mục mới thành công!");
        // if ($create) {
        // } else {
        //     return redirect('/admin/product/cat/list')->with('error', "Thêm danh mục mới thất bại!");
        // }
    }
    function delete_cat_list($id)
    {
        CatProduct::where('id', $id)->delete();
        return redirect('/admin/product/cat/list')->with('status', "Xóa danh mục thành công!");
    }
}
