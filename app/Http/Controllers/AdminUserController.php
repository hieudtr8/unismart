<?php

namespace App\Http\Controllers;

use App\User;
use App\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AdminUserController extends Controller
{
    //
    function __construct()
    {
        $this->middleware(function ($request, $next) {
            session(['module_active' => 'user']);

            return $next($request);
        });
    }
    function list(Request $request)
    {
        $status =  $request->input('status');
        if ($status == 'inactive') {
            $users = User::onlyTrashed()->paginate(10);
        } else {
            $keyword = "";
            if ($request->input('keyword')) {
                $keyword = $request->input('keyword');
            }
            $users = User::where(
                'name',
                'LIKE',
                "%{$keyword}%"
            )->paginate(10);
        }
        $count_active =  User::count();
        $count_inactive =  User::onlyTrashed()->count();
        $roles = Role::all();
        return view('admin.user.list', compact('users', 'roles', 'count_active', 'count_inactive', 'status'));
    }
    function add()
    {
        $roles = Role::all();
        return view('admin.user.add', compact('roles'));
    }
    function store(Request $request)
    {
        $request->validate(
            [
                'name' => 'required|string|max:255',
                'email' => 'required|unique:users|email|string|max:255',
                'password' => 'min:6|confirmed|string',
                'password_confirmation' => 'required',
                'role' => 'gt:0'
            ],
            [
                'required' => ":attribute không được để trống",
                'min' => ":attribute phải ít nhất 6 ký tự",
                'confirmed' => ":attribute và mật khẩu xác nhận cần phải trùng khớp",
                'unique' => 'Email đã được đăng ký',
                'gt' => 'Vui lòng chọn quyền'
            ],
            [
                'name' => "Họ và tên",
                'email' => "Email",
                'password' => "Mật khẩu",
                'password_confirmation' => "Mật khẩu xác nhận",
            ],
        );
        User::create([
            'name' => $request->input('name'),
            'email' =>  $request->input('email'),
            'role' => $request->input('role'),
            'password' => Hash::make($request->input('password')),
        ]);
        return redirect('/admin/user/list')->with('status', "Thêm thành viên thành công!");
    }
    function delete($id)
    {
        if (Auth::id() != $id) {
            $user = User::find($id)->delete();
            return redirect('admin/user/list')->with('status', "Xóa thánh viên thành công!");
        } else {
            return redirect('admin/user/list')->with('error', "Không thể tự xóa mình khỏi hệ thống!");
        }
    }
    function forceDelete($id)
    {
        User::withTrashed()->where('id', $id)->forceDelete();
        return redirect('admin/user/list')->with('status', "Xóa hoàn toàn thành viên thành công!");
    }
    function action(Request $request)
    {
        $list_check = $request->input('listCheck');
        if ($list_check) {
            foreach ($list_check as $k => $v) {
                if (Auth::id() == $v) {
                    unset($list_check[$k]);
                }
            }
            if (!empty($list_check)) {
                $action = $request->input('action');
                if ($action == "delete") {
                    User::destroy($list_check);
                    return redirect('/admin/user/list')->with('status', 'Xoá các bản ghi được chọn thành công!');
                }
                if ($action == "force_delete") {
                    User::withTrashed()->whereIn('id', $list_check)->forceDelete();
                    return redirect('/admin/user/list')->with('status', 'Xoá vĩnh viễn các bản ghi được chọn thành công!');
                }
                if ($action == "restore") {
                    User::withTrashed()->whereIn('id', $list_check)->restore();
                    return redirect('/admin/user/list')->with('status', 'Khôi phục các bản ghi được chọn thành công!');
                }
            }
            return redirect('/admin/user/list')->with('error', 'Không thể thao tác trên tài khoản của mình');
        } else {
            return redirect('/admin/user/list')->with('error', 'Cần chọn bản ghi để thực hiện');
        }
    }
    function edit($id)
    {
        $user = User::find($id);
        $roles = Role::all();
        return view('admin.user.edit', compact('user', 'roles'));
    }
    function update(Request $request)
    {
        $request->validate(
            [
                'name' => 'string|max:255',
                'email' => 'email|string|max:255',
                'password' => 'min:6|confirmed|string',
                'password_confirmation' => 'required',
                'role' => 'gt:0'
            ],
            [
                'required' => ":attribute không được để trống",
                'min' => ":attribute phải ít nhất 6 ký tự",
                'confirmed' => ":attribute và mật khẩu xác nhận cần phải trùng khớp",
                'unique' => 'Email đã được đăng ký',
                'gt' => 'Vui lòng chọn quyền'
            ],
            [
                'name' => "Họ và tên",
                'email' => "Email",
                'password' => "Mật khẩu",
                'password_confirmation' => "Mật khẩu xác nhận",
            ],
        );
        User::where('id', $request->id)
            ->update([
                'name' => $request->input('name'),
                'email' => $request->input('email'),
                'role' => $request->input('role'),
                'password' => Hash::make($request->input('password'))
            ]);
        $url = "/admin/user/edit/" . $request->id;
        return redirect($url)->with('status', "Chỉnh sửa thông tin thành viên thành công!");
    }
}
