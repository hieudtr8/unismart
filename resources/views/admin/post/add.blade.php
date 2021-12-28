@extends('layouts.admin')
@section('title')
    Trang thêm bài viết
@endsection
@section('content')
    <div id="content" class="container-fluid">
        <div class="card">
            <div class="card-header font-weight-bold">
                Thêm bài viết
            </div>
            <div class="card-body">
                    <form action="{{ url('admin/post/store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group">
                            <label for="title">Tiêu đề bài viết</label>
                            <input class="form-control" type="text" name="title" id="name">
                            @error('title')
                                <small class="form-text text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="content">Nội dung bài viết</label>
                            <textarea name="content" class="form-control" id="content" cols="30" rows="5"></textarea>
                            @error('content')
                                <small class="form-text text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="">Danh mục</label>
                            <select class="form-control" name="cat" id="">
                                <option>Chọn danh mục</option>
                                @foreach ($cats as $cat)
                                    <option value="{{ $cat->id }}"> {{ $cat->title }}</option>
                                @endforeach
                            </select>
                            @error('cat')
                                <small class="form-text text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="form-group">
                            <div class="custom-file">
                                <label for="custom-file-label">Tải lên ảnh bìa </label> <br>
                                <input type="file" name="file" class="form-control-file" id="custom-file-label">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="">Trạng thái</label>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="status" id="exampleRadios1"
                                    value="inactive" checked>
                                <label class="form-check-label" for="exampleRadios1">
                                    Chờ duyệt
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="status" id="exampleRadios2"
                                    value="active">
                                <label class="form-check-label" for="exampleRadios2">
                                    Công khai
                                </label>
                            </div>
                        <button type="submit" class="btn btn-primary">Thêm mới</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
