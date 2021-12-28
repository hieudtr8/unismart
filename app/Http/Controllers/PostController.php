<?php

namespace App\Http\Controllers;

use App\CatPost;
use App\Post;
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
    function list(Request $request)
    {
        $status =  $request->input('status');
        if ($status == 'inactive') {
            $posts = Post::onlyTrashed()->paginate(10);
        } else {
            $keyword = "";
            if ($request->input('keyword')) {
                $keyword = $request->input('keyword');
            }
            $posts = Post::where(
                'title',
                'LIKE',
                "%{$keyword}%"
            )->paginate(10);
        }
        $count_active =  Post::count();
        $count_inactive =  Post::onlyTrashed()->count();
        $cat = CatPost::all();
        return view('admin.post.list', compact('posts', 'cat', 'count_active', 'count_inactive', 'status'));
    }
    function add()
    {
        $cats = CatPost::all();
        return view('admin.post.add', compact('cats'));
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
        if ($request->hasFile('file')) {
            $image = $request->file;
            $image_name = $image->getClientOriginalName();
            $image->move('public/uploads', $image_name);
            $thumbnail = 'public/uploads/' . $image_name;
        }
        Post::create([
            'title' => $request->input('title'),
            'content' =>  $request->input('content'),
            'cat_id' => $request->input('cat'),
            'status' => $request->input('status'),
            'thumbnail' => $thumbnail,
            'created_at' => now(),
        ]);
        return redirect('/admin/post/list')->with('status', "Thêm bài viết mới thành công!");
    }
    function delete($id)
    {
        Post::find($id)->delete();
        return redirect('admin/post/list')->with('status', "Xóa bài viết tạm thời thành công!");
    }
    function forceDelete($id)
    {
        Post::withTrashed()->where('id', $id)->forceDelete();
        return redirect('admin/post/list')->with('status', "Xóa hoàn toàn bài viết thành công!");
    }
    function action(Request $request)
    {
        $list_check = $request->input('listCheck');
        if ($list_check) {
            if (!empty($list_check)) {
                $action = $request->input('action');
                if ($action == "delete") {
                    Post::destroy($list_check);
                    return redirect('/admin/post/list')->with('status', 'Xoá các bản ghi được chọn thành công!');
                }
                if ($action == "force_delete") {
                    Post::withTrashed()->whereIn('id', $list_check)->forceDelete();
                    return redirect('/admin/post/list')->with('status', 'Xoá vĩnh viễn các bản ghi được chọn thành công!');
                }
                if ($action == "restore") {
                    Post::withTrashed()->whereIn('id', $list_check)->restore();
                    return redirect('/admin/post/list')->with('status', 'Khôi phục các bản ghi được chọn thành công!');
                }
                if ($action == "active") {
                    Post::whereIn('id', $list_check)->update(
                        ['status' => 'active']
                    );
                    return redirect('/admin/post/list')->with('status', 'Công khai các bản ghi được chọn thành công!');
                }
                if ($action == "inactive") {
                    Post::whereIn('id', $list_check)->update(
                        ['status' => 'inactive']
                    );
                    return redirect('/admin/post/list')->with('status', 'Chờ duyệt các bản ghi được chọn thành công!');
                }
            }
        } else {
            return redirect('/admin/post/list')->with('error', 'Cần chọn bản ghi để thực hiện');
        }
    }
    function edit($id)
    {
        $post = Post::find($id);
        $cats = CatPost::all();
        return view('admin.post.edit', compact('post', 'cats'));
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
            Post::where('id', $request->id)
                ->update([
                    'title' => $request->input('title'),
                    'content' =>  $request->input('content'),
                    'cat_id' => $request->input('cat'),
                    'status' => $request->input('status'),
                    'thumbnail' => $thumbnail,
                    'updated_at' => now(),
                ]);
        } else {
            Post::where('id', $request->id)
                ->update([
                    'title' => $request->input('title'),
                    'content' =>  $request->input('content'),
                    'cat_id' => $request->input('cat'),
                    'status' => $request->input('status'),
                    'updated_at' => now(),
                ]);
        }
        $url = "/admin/post/edit/" . $request->id;
        return redirect($url)->with('status', "Chỉnh sửa thông tin trang thành công!");
    }
    // Cat
    function cat_list()
    {
        $cats =  CatPost::all();
        return view('admin.post.cat_list', compact('cats'));
    }
    function store_cat_list(Request $request)
    {
        $request->validate(
            [
                'title' => 'required|string|max:255',
            ],
            [
                'required' => ":attribute không được để trống",
            ],
            [
                'title' => "Tên danh mục",
            ],
        );
        CatPost::create([
            'title' => $request->input('title'),
        ]);
        return redirect('/admin/post/cat/list')->with('status', "Thêm danh mục mới thành công!");
    }
    function delete_cat_list($id)
    {
        CatPost::where('id', $id)->delete();
        return redirect('/admin/post/cat/list')->with('status', "Xóa danh mục thành công!");
    }
}
