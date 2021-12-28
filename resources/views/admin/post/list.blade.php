@extends('layouts.admin')
@section('title')
    Trang danh sách bài viết
@endsection
@section('content')
    <div id="content" class="container-fluid">
        <div class="card">
            <div class="card-header font-weight-bold d-flex justify-content-between align-items-center">
                <h5 class="m-0 ">Danh sách bài viết</h5>
                <div class="form-search form-inline">
                    <form action="#">
                        <input type="" value=" {{ request()->input('keyword') }}" name="keyword"
                            class="form-control form-search" placeholder="Tìm kiếm">
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
                        động <span class="text-muted">({{ $count_active }})</span></a>
                    <a href="{{ request()->fullUrlWithQuery(['status' => 'inactive']) }}" class="text-primary">Vô hiệu
                        hóa<span class="text-muted">({{ $count_inactive }})</span></a>
                </div>
                <form action="{{ url('/admin/post/action') }}" method="POST">
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
                                <th scope="col">Ảnh</th>
                                <th scope="col">Tiêu đề</th>
                                <th scope="col">Danh mục</th>
                                <th scope="col">Trạng thái</th>
                                <th scope="col">Ngày tạo</th>
                                <th scope="col">Tác vụ</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if ($posts->total() > 0)


                                @php
                                    $stt = 0;
                                @endphp
                                @foreach ($posts as $post)
                                    @php
                                        $stt++;
                                    @endphp
                                    <tr>
                                        <td>
                                            <input type="checkbox" name="listCheck[]" value="{{ $post->id }}">
                                        </td>
                                        <td scope="row">{{ $stt }}</td>
                                        <td><img src="{!! URL::to($post->thumbnail) !!}" alt=""></td>
                                        <td><a href="">{{ $post->title }}</a>
                                        </td>
                                        <td>@php
                                            $cat_name = $cat->where('id', $post->cat_id);
                                            $cat_name = json_decode($cat_name, true);
                                            // echo '<pre>';
                                            // print_r($cat_name);
                                            // echo '<pre>';
                                            echo $cat_name[$post->cat_id - 1]['title'];
                                        @endphp
                                        </td>
                                        <td> {{ $post->status == 'active' ? 'Công khai' : 'Chờ duyệt' }} </td>
                                        <td>{{ $post->created_at }}</td>
                                        <td><a href="{{ route('edit.post', $post->id) }}"
                                                class="btn btn-success btn-sm rounded-0" type="button" data-toggle="tooltip"
                                                data-placement="top" title="Edit"><i class="fa fa-edit"></i></button>
                                                <a href=@if ($status == 'inactive')
                                                    " {{ route('forceDelete.post', $post->id) }}"
                                                @else
                                                    " {{ route('delete.post', $post->id) }}"
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
                {{ $posts->links() }}
            </div>
        </div>
    </div>
@endsection
