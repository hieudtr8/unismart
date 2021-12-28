<?php

namespace App\Http\Controllers;

use App\CatPage;
use App\Page;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class PageController extends Controller
{
    function __construct()
    {
        $this->middleware(function ($request, $next) {
            session(['module_active' => 'page']);

            return $next($request);
        });
    }
    function list(Request $request)
    {
        $status =  $request->input('status');
        if ($status == 'inactive') {
            $pages = Page::onlyTrashed()->paginate(10);
        } else {
            $keyword = "";
            if ($request->input('keyword')) {
                $keyword = $request->input('keyword');
            }
            $pages = Page::where(
                'title',
                'LIKE',
                "%{$keyword}%"
            )->paginate(10);
        }
        $count_active =  Page::count();
        $count_inactive =  Page::onlyTrashed()->count();
        $cat = CatPage::all();
        return view('admin.page.list', compact('pages', 'cat', 'count_active', 'count_inactive', 'status'));
    }
    function add()
    {
        $cats = CatPage::all();
        return view('admin.page.add', compact('cats'));
    }
    function store(Request $request)
    {
        $request->validate(
            [
                'title' => 'required|string|max:255',
                'content' => 'required|string',
                'cat' => 'gt:0'
            ],
            [
                'required' => ":attribute không được để trống",
                'min' => ":attribute phải ít nhất 6 ký tự",
                'gt' => 'Vui lòng chọn danh mục'
            ],
            [
                'title' => "Tiêu đề trang",
                'content' => "Nội dung trang",
                'cat' => "Danh mục",
            ],
        );
        Page::create([
            'title' => $request->input('title'),
            'content' =>  $request->input('content'),
            'cat_id' => $request->input('cat'),
            'status' => $request->input('status'),
            'created_at' => now(),
        ]);
        return redirect('/admin/page/list')->with('status', "Thêm trang mới thành công!");
    }
    function delete($id)
    {
        Page::find($id)->delete();
        return redirect('admin/page/list')->with('status', "Xóa trang tạm thời thành công!");
    }
    function forceDelete($id)
    {
        Page::withTrashed()->where('id', $id)->forceDelete();
        return redirect('admin/user/list')->with('status', "Xóa hoàn toàn trang thành công!");
    }
    function action(Request $request)
    {
        $list_check = $request->input('listCheck');
        if ($list_check) {
            if (!empty($list_check)) {
                $action = $request->input('action');
                if ($action == "delete") {
                    Page::destroy($list_check);
                    return redirect('/admin/page/list')->with('status', 'Xoá các bản ghi được chọn thành công!');
                }
                if ($action == "force_delete") {
                    Page::withTrashed()->whereIn('id', $list_check)->forceDelete();
                    return redirect('/admin/page/list')->with('status', 'Xoá vĩnh viễn các bản ghi được chọn thành công!');
                }
                if ($action == "restore") {
                    Page::withTrashed()->whereIn('id', $list_check)->restore();
                    return redirect('/admin/page/list')->with('status', 'Khôi phục các bản ghi được chọn thành công!');
                }
                if ($action == "active") {
                    Page::whereIn('id', $list_check)->update(
                        ['status' => 'active']
                    );
                    return redirect('/admin/page/list')->with('status', 'Công khai các bản ghi được chọn thành công!');
                }
                if ($action == "inactive") {
                    Page::whereIn('id', $list_check)->update(
                        ['status' => 'inactive']
                    );
                    return redirect('/admin/page/list')->with('status', 'Chờ duyệt các bản ghi được chọn thành công!');
                }
            }
        } else {
            return redirect('/admin/page/list')->with('error', 'Cần chọn bản ghi để thực hiện');
        }
    }
    function edit($id)
    {
        $page = Page::find($id);
        $cats = CatPage::all();
        return view('admin.page.edit', compact('page', 'cats'));
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
        Page::where('id', $request->id)
            ->update([
                'title' => $request->input('title'),
                'content' => $request->input('content'),
                'cat_id' => $request->input('cat'),
                'status' => $request->input('status'),
                'updated_at' => now(),
            ]);
        $url = "/admin/page/edit/" . $request->id;
        return redirect($url)->with('status', "Chỉnh sửa thông tin trang thành công!");
    }
}
