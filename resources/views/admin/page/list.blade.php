@extends('layouts.admin')
@section('title', 'Trang danh sách trang')
@section('content')
    <div id="content" class="container-fluid">
        <div class="card">
            <div class="card-header font-weight-bold d-flex justify-content-between align-items-center">
                <h5 class="m-0 ">Danh sách bài viết</h5>
                <div class="form-search form-inline">
                    <form action="#">
                        <input type="text" class="form-control form-search" name="keyword"
                            value=" {{ request()->input('keyword') }} " placeholder="Tìm kiếm">
                        <input type="submit" name="btn-search" value="Tìm kiếm" class="btn btn-primary">
                    </form>
                </div>
            </div>
            <div class="card-body">
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
                <div class="analytic">
                    <a href="{{ request()->fullUrlWithQuery(['status' => 'active']) }}" class="text-primary">Đang hoạt
                        động<span class="text-muted">({{ $count_active }})</span></a>
                    <a href="{{ request()->fullUrlWithQuery(['status' => 'inactive']) }}" class="text-primary">Đã vô
                        hiệu hóa<span class="text-muted">({{ $count_inactive }})</span></a>
                </div>
                <form action="{{ url('/admin/page/action') }}" method="POST">
                    @csrf
                    <div class="form-action form-inline py-3">
                        <select class="form-control mr-1" name="action" id="">
                            <option>Chọn</option>
                            @if ($status == 'inactive')
                                <option value="force_delete">Xóa vĩnh viễn</option>
                                <option value="restore">Khôi phục</option>
                            @else
                                <option value="delete">Xóa tạm thời</option>
                                <option value="active">Công khai</option>
                                <option value="inactive">Chờ duyệt</option>
                            @endif
                        </select>
                        <input type="submit" name="btn-search" value="Áp dụng" class="btn btn-primary">
                    </div>
                    <table class="table table-striped table-checkall">
                        <thead>
                            <tr>
                                <th scope="col">
                                    <input name="checkall" type="checkbox">
                                </th>
                                <th scope="col">#</th>
                                <th scope="col">Tiêu đề</th>
                                <th scope="col">Danh mục</th>
                                <th scope="col">Tình trạng</th>
                                <th scope="col">Ngày tạo</th>
                                <th scope="col">Tác vụ</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if ($pages->total() > 0)
                                @php
                                    $stt = 0;
                                @endphp
                                @foreach ($pages as $page)
                                    <tr>
                                        <td>
                                            <input type="checkbox" name="listCheck[]" value="{{ $page->id }}">
                                        </td>
                                        @php
                                            $stt++;
                                        @endphp
                                        <td scope="row">{{ $stt }}</td>
                                        <td><a href="">{!! $page->title !!}</a>
                                        </td>
                                        <td>@php
                                            $cat_name = $cat->where('id', $page->cat_id);
                                            $cat_name = json_decode($cat_name, true);
                                            echo $cat_name[$page->cat_id - 1]['name'];
                                        @endphp
                                        </td>
                                        <td> {{ $page->status == 'active' ? 'Công khai' : 'Chờ duyệt' }} </td>
                                        <td>{{ $page->created_at }}</td>
                                        <td><a href="{{ route('edit.page', $page->id) }}"
                                                class="btn btn-success btn-sm rounded-0" type="button" data-toggle="tooltip"
                                                data-placement="top" title="Edit"><i class="fa fa-edit"></i></button>
                                                <a href=@if ($status == 'inactive')
                                                    " {{ route('forceDelete.page', $page->id) }}"
                                                @else
                                                    " {{ route('delete.page', $page->id) }}"
                                @endif
                                onclick="return confirm('Bạn có chắc chắn xóa bản ghi này?')"
                                class="btn btn-danger btn-sm rounded-0" type="button" data-toggle="tooltip"
                                data-placement="top" title="Delete"><i class="fa fa-trash"></i></a>
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
                {{ $pages->links() }}
            </div>
        </div>
    </div>
@endsection
