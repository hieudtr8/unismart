@extends('layouts.admin')
@section('title', 'Danh sách quản trị viên')
@section('content')
    <div id="content" class="container-fluid">
        <div class="card">
            @if (session('status'))
                <div class="alert alert-success">
                    {{ session('status') }}
                </div>
            @endif
            @if (session('error'))
                <div class="alert alert-danger">
                    {{ session('error') }}
                </div>
            @endif
            <div class="card-header font-weight-bold d-flex justify-content-between align-items-center">
                <h5 class="m-0 ">Danh sách thành viên</h5>
                <div class="form-search form-inline">
                    <form action="#">
                        <input type="text" class="form-control form-search" name="keyword"
                            value="{{ request()->input('keyword') }}" placeholder="Tìm kiếm">
                        <input type="submit" name="btn-search" value="Tìm kiếm" class="btn btn-primary">
                    </form>
                </div>
            </div>
            <div class="card-body">
                <div class="analytic">
                    <a href="{{ request()->fullUrlWithQuery(['status' => 'active']) }}" class="text-primary">Đang hoạt
                        động<span class="text-muted">({{ $count_active }})</span></a>
                    <a href="{{ request()->fullUrlWithQuery(['status' => 'inactive']) }}" class="text-primary">Đã vô
                        hiệu hóa<span class="text-muted">({{ $count_inactive }})</span></a>
                    {{-- <a href="" class="text-primary">Trạng thái 3<span class="text-muted">(20)</span></a> --}}
                </div>
                <form action="{{ url('/admin/user/action') }}" method="POST">
                    @csrf
                    <div class="form-action form-inline py-3">
                        <select class="form-control mr-1" name="action" id="">
                            <option>Chọn</option>
                            @if ($status == 'inactive')
                                <option value="force_delete">Xóa vĩnh viễn</option>
                                <option value="restore">Khôi phục</option>
                            @else
                                <option value="delete">Xóa tạm thời</option>
                            @endif
                        </select>
                        <input type="submit" name="btn-search" value="Áp dụng" class="btn btn-primary">
                    </div>


                    <table class="table table-striped table-checkall">
                        <thead>
                            <tr>
                                <th>
                                    <input type="checkbox" name="checkall">
                                </th>
                                <th scope="col">#</th>
                                <th scope="col">Họ tên</th>
                                <th scope="col">Email</th>
                                <th scope="col">Quyền</th>
                                <th scope="col">Ngày tạo</th>
                                <th scope="col">Tác vụ</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if ($users->total() > 0)
                                @php
                                    $stt = 0;
                                @endphp
                                @foreach ($users as $user)
                                    @php
                                        $stt++;
                                    @endphp
                                    <tr>
                                        <td>
                                            <input type="checkbox" name="listCheck[]" value="{{ $user->id }}">
                                        </td>
                                        <th scope="row">{{ $stt }}</th>
                                        <td>{{ $user->name }}</td>
                                        <td>{{ $user->email }}</td>
                                        <td> @php
                                            $role_name = $roles->where('id', $user->role);
                                            $role_name = json_decode($role_name, true);
                                            echo $role_name[$user->role - 1]['title'];
                                        @endphp</td>
                                        <td>{{ $user->created_at }}</td>
                                        <td>
                                            <a href="{{ route('edit.user', $user->id) }} "
                                                class="btn btn-success btn-sm rounded-0 text-white" type="button"
                                                data-toggle="tooltip" data-placement="top" title="Edit"><i
                                                    class="fa fa-edit"></i></a>
                                            @if (Auth::id() != $user->id)
                                                <a href=@if ($status == 'inactive')
                                                    " {{ route('forceDelete.user', $user->id) }}"
                                                @else
                                                    " {{ route('delete.user', $user->id) }}"
                                            @endif
                                            onclick="return confirm('Bạn có chắc chắn xóa bản ghi này?')"
                                            class="btn btn-danger btn-sm rounded-0 text-white" type="button"
                                            data-toggle="tooltip" data-placement="top" title="Delete"><i
                                                class="fa fa-trash"></i></a>
                                @endif
                                </td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="7" class="bg-white" style="">Không tìm thấy bản ghi</td>
                            </tr>
                            @endif
                        </tbody>
                    </table>
                </form>

                {{ $users->links() }}
            </div>
        </div>
    </div>
@endsection
