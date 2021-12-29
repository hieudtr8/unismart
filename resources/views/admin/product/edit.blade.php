@extends('layouts.admin')
@section('title')
    Trang thêm sản phẩm
@endsection
@section('content')
    <div id="content" class="container-fluid">
        <div class="card">
            <div class="card-header font-weight-bold">
                Chirnh sửa sản phẩm
            </div>
            @if (session('status'))
                <div class="alert alert-success">
                    {{ session('status') }}
                </div>
            @endif
            <div class="card-body">
                <form action="{{ url('admin/product/update') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <div class="col-6">
                            <div class="form-group">
                                <label for="title">Tên sản phẩm</label>
                                <input type="hidden" name="id" value="{{ $product->id }}">
                                <input class="form-control" type="text" name="title" value="{{ $product->title }}"
                                    id="title">
                                @error('title')
                                    <small class="form-text text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="price">Giá</label>
                                <input class="form-control" type="text" name="price" value="{{ $product->price }}"
                                    id="price">
                                @error('price')
                                    <small class="form-text text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <label for="desc">Mô tả sản phẩm</label>
                                <textarea name="desc" class="form-control" id="intro" cols="30"
                                    rows="5"> {{ $product->desc }} </textarea>
                                @error('desc')
                                    <small class="form-text text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>
                    </div>


                    <div class="form-group">
                        <label for="content">Chi tiết sản phẩm</label>
                        <textarea name="content" class="form-control" id="content" cols="30"
                            rows="5">  {{ $product->desc }}</textarea>
                        @error('content')
                            <small class="form-text text-danger">{{ $message }}</small>
                        @enderror
                    </div>


                    <div class="form-group">
                        <label for="">Danh mục</label>
                        <select class="form-control" name="cat" id="">
                            <option>Chọn danh mục</option>
                            @foreach ($cats as $cat)
                                <option {{ $product->cat_id == $cat->id ? 'selected = "selected"' : '' }}
                                    value="{{ $cat->id }}"> {{ $cat->title }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <div class="custom-file">
                            <label for="custom-file-label">Tải lên ảnh bìa </label> <br>
                            <img class="mb-3" src="{!! URL::to($product->thumbnail) !!}" alt="">
                            <input type="file" name="file" class="form-control-file" id="custom-file-label">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="">Trạng thái</label>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="status"
                                {{ $product->status == 'active' ? 'checked' : '' }} id="exampleRadios1" value="inactive"
                                checked>
                            <label class="form-check-label" for="exampleRadios1">
                                Chờ duyệt
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="status"
                                {{ $product->status == 'active' ? 'checked' : '' }} id="exampleRadios2" value="active">
                            <label class="form-check-label" for="exampleRadios2">
                                Công khai
                            </label>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary">Cập nhật</button>
                </form>
            </div>
        </div>
    </div>
@endsection
