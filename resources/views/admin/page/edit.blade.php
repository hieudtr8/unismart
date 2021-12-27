@extends('layouts.admin')
@section('title')
    Trang chỉnh sửa trang
@endsection
@section('content')
    <div id="content" class="container-fluid">
        <div class="card">
            <div class="card-header font-weight-bold">
                Chỉnh sửa trang
            </div>
            @if (session('status'))
                <div class="alert alert-success">
                    {{ session('status') }}
                </div>
            @endif
            <div class="card-body">
                <form action="{{ url('admin/page/update') }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label for="title">Tiêu đề trang</label>
                        <input type="hidden" name="id" value="{{ $page->id }}">
                        <input class="form-control" type="text" name="title" value="{{ $page->title }}" id="name">
                        @error('title')
                            <small class="form-text text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="content">Nội dung trang</label>
                        <textarea name="content" class="form-control" id="content" cols="30"
                            rows="5"> {{ $page->content }}</textarea>
                        @error('content')
                            <small class="form-text text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="">Danh mục</label>
                        <select name="cat" class="form-control" id="">
                            <option value="0">Chọn danh mục</option>
                            @foreach ($cats as $cat)
                                {{-- <option @php
                                    if ($page->cat_id == $cats->id) {
                                        echo "selected = 'selected' ";
                                    }
                                @endphp --}}
                                <option {{ $page->cat_id == $cat->id ? 'selected = "selected"' : '' }}
                                    value="{{ $cat->id }}">
                                    {{ $cat->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('cat')
                            <small class="form-text text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="">Trạng thái</label>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="status" id="exampleRadios1" value="inactive"
                                @php
                                  echo  $page->status == 'inactive' ? 'checked' : '';
                                @endphp>
                            <label class="form-check-label" for="exampleRadios1">
                                Chờ duyệt
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="status" id="exampleRadios2" value="active"
                                @php
                                  echo  $page->status == 'active' ? 'checked' : '';
                                @endphp>
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
