@extends('layouts.admin')
@section('title')
    Trang danh sách danh mục bài viết
@endsection
@section('content')
    <div id="content" class="container-fluid">
        <div class="row">
            <div class="col-4">
                <div class="card">
                    <div class="card-header font-weight-bold">
                        Thêm danh mục
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
                        <form action="{{ url('admin/post/cat/list/store') }}" method="POST">
                            @csrf
                            <div class="form-group">
                                <label for="title">Tên danh mục</label>
                                <input class="form-control" type="text" name="title" id="name">
                            </div>
                            <button type="submit" class="btn btn-primary">Thêm mới</button>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-8">
                <div class="card">
                    <div class="card-header font-weight-bold">
                        Danh mục
                    </div>
                    <div class="card-body">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">ID danh mục</th>
                                    <th scope="col">Tên danh mục</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $stt = 0;
                                @endphp
                                @if (count($cats) > 0)
                                    @foreach ($cats as $cat)
                                        @php
                                            $stt++;
                                        @endphp
                                        <tr>
                                            <th scope="row">{{ $stt }}</th>
                                            <td>{{ $cat->id }}</td>
                                            <td>{{ $cat->title }}</td>
                                            <td> <a href=" {{ route('delete.cat.post', $cat->id) }}"
                                                    onclick="return confirm('Bạn có chắc chắn xóa bản ghi này?')"
                                                    class="btn btn-danger btn-sm rounded-0" type="button"
                                                    data-toggle="tooltip" data-placement="top" title="Delete"><i
                                                        class="fa fa-trash"></i></a> </td>
                                        </tr>
                                    @endforeach
                                @else
                                    <td class="bg-white">Không có bản ghi nào</td>
                                @endif

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

    </div>
@endsection
