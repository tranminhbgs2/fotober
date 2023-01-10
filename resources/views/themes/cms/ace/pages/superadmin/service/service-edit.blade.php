@extends(\App\Helpers\Constants::VIEW_LAYOUT_PATH . '.master-layout')
@section('title')
    {{ trans('fotober.sadmin.service.edit') }}
@endsection
@section('content')
    <!-- ============================================================== -->
    <!-- Bread crumb and right sidebar toggle -->
    <!-- ============================================================== -->
    <div class="row page-titles">
        <div class="col-md-5 align-self-center">
            <h4 class="text-themecolor">{{ trans('fotober.sadmin.service.edit') }}</h4>
        </div>
    </div>
    <!-- ============================================================== -->
    <!-- End Bread crumb and right sidebar toggle -->
    <!-- ============================================================== -->
    <!-- ============================================================== -->
    <!-- Over Visitor, Our income , slaes different and  sales prediction -->
    <!-- ============================================================== -->
    <div class="row">
        <!-- Column -->
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <form class="form-horizontal" role="form" method="post" action="{{ route('superadmin_update_service') }}" enctype="multipart/form-data">
                        {{-- CSRF --}}
                        <div class="form-group mb-0">
                            {{ csrf_field() }}
                            <input type="hidden" name="id" id="id" value="{{ $service->id }}" class="form-control">
                        </div>
                        {{-- Tên DV --}}
                        <div class="form-group row">
                            <label class="col-12 col-md-3 col-lg-3 col-form-label">
                                {{ trans('fotober.sadmin.service.name') }}
                                <span class="text-danger ml-1">*</span>
                            </label>
                            <div class="col-12 col-md-9 col-lg-5">
                                <input type="text" name="name" id="name" value="{{ isset($service->name ) ? $service->name : old('name') }}" class="form-control">
                                @if ($errors->has('name'))<span class="validate-error">{{ $errors->first('name') }}</span>@endif
                            </div>
                        </div>
                        {{-- Mã DV --}}
                        <div class="form-group row">
                            <label class="col-12 col-md-3 col-lg-3 col-form-label">
                                {{ trans('fotober.sadmin.service.code') }}
                                <span class="text-danger ml-1">*</span>
                            </label>
                            <div class="col-12 col-md-9 col-lg-5">
                                <input type="text" name="code" id="code" value="{{ isset($service->code ) ? $service->code : old('code') }}" class="form-control">
                                @if ($errors->has('code'))<span class="validate-error">{{ $errors->first('code') }}</span>@endif
                            </div>
                        </div>
                        {{-- Giá từ bao nhiêu $ --}}
                        <div class="form-group row">
                            <label class="col-12 col-md-3 col-lg-3 col-form-label">
                                {{ trans('fotober.sadmin.service.from_price') }}
                                <span class="text-danger ml-1">*</span>
                            </label>
                            <div class="col-12 col-md-9 col-lg-5">
                                <input type="text" name="from_price" id="from_price" value="{{ isset($service->from_price ) ? $service->from_price : old('from_price') }}" class="form-control">
                                @if ($errors->has('from_price'))<span class="validate-error">{{ $errors->first('from_price') }}</span>@endif
                            </div>
                        </div>
                        {{-- Nhóm dịch vụ --}}
                        <div class="form-group row">
                            <label class="col-12 col-md-3 col-lg-3 col-form-label">
                                {{ trans('fotober.sadmin.service.group_name') }}
                                <span class="text-danger ml-1">*</span>
                            </label>
                            <div class="col-12 col-md-9 col-lg-5">
                                <select class="form-control" name="group_code" id="group_code">
                                    {{-- <option value="">{{ trans('fotober.common._select_') }}</option> --}}
                                    @foreach($group_code as $key => $group)
                                        <option value="{{ $key }}" {{ ($key == $service->group_code) ? 'selected' : '' }}>{{ $group }}</option>
                                    @endforeach
                                </select>
                                @if ($errors->has('group_code'))<span class="validate-error">{{ $errors->first('group_code') }}</span>@endif
                            </div>
                        </div>
                        {{-- Loại dịch vụ --}}
                        <div class="form-group row">
                            <label class="col-12 col-md-3 col-lg-3 col-form-label">
                                {{ trans('fotober.sadmin.service.type') }}
                                <span class="text-danger ml-1">*</span>
                            </label>
                            <div class="col-12 col-md-9 col-lg-5">
                                <select class="form-control" name="type" id="type">
                                    {{-- <option value="">{{ trans('fotober.common._select_') }}</option> --}}
                                    @foreach($types as $key => $type)
                                        <option value="{{ $key }}" {{ ($key == $service->type) ? 'selected' : '' }}>{{ $type }}</option>
                                    @endforeach
                                </select>
                                @if ($errors->has('type'))<span class="validate-error">{{ $errors->first('type') }}</span>@endif
                            </div>
                        </div>
                        {{-- Ảnh đại diện --}}
                        <div class="form-group row">
                            <label class="col-12 col-md-3 col-lg-3 col-form-label">
                                {{ trans('fotober.sadmin.service.image') }}
                            </label>
                            <div class="col-12 col-md-9 col-lg-5">
                                <div class="input-group">
                                    <input type="text" name="image" id="image" class="form-control" readonly value="{{ isset($service->image ) ? $service->image : old('image') }}">
                                    <span class="input-group-append">
                                        <button class="btn btn-info" type="button" onclick="browseServer();"><i class="fa fa-image"></i></button>
                                    </span>
                                </div>
                                @if ($errors->has('image'))<span class="validate-error">{{ $errors->first('image') }}</span>@endif
                                <div class="d-block mt-1">
                                    <img id="image_viewer" src="{{ (isset($service->image )) ? asset($service->image) : '' }}" style="max-height: 150px">
                                </div>
                            </div>
                        </div>
                        {{-- Ảnh trước khi sửa --}}
                        <div class="form-group row">
                            <label class="col-12 col-md-3 col-lg-3 col-form-label">
                                {{ trans('fotober.sadmin.service.before_image') }}
                            </label>
                            <div class="col-12 col-md-9 col-lg-5">
                                <div class="input-group">
                                    <input type="text" name="before_photo" id="before_photo" class="form-control" readonly value="{{ isset($service->before_photo ) ? $service->before_photo : old('before_photo') }}">
                                    <span class="input-group-append">
                                        <button class="btn btn-info" type="button" onclick="browseServerBeforePhoto();"><i class="fa fa-image"></i></button>
                                    </span>
                                </div>
                                @if ($errors->has('before_photo'))<span class="validate-error">{{ $errors->first('before_photo') }}</span>@endif
                                <div class="d-block mt-1">
                                    <img id="before_photo_viewer" src="{{ (isset($service->before_photo )) ? asset($service->before_photo) : '' }}" style="max-height: 150px">
                                </div>
                            </div>
                        </div>
                        {{-- Ảnh sau khi sửa --}}
                        <div class="form-group row">
                            <label class="col-12 col-md-3 col-lg-3 col-form-label">
                                {{ trans('fotober.sadmin.service.after_image') }}
                            </label>
                            <div class="col-12 col-md-9 col-lg-5">
                                <div class="input-group">
                                    <input type="text" name="after_photo" id="after_photo" class="form-control" readonly value="{{ isset($service->after_photo ) ? $service->after_photo : old('after_photo') }}">
                                    <span class="input-group-append">
                                        <button class="btn btn-info" type="button" onclick="browseServerAfterPhoto();"><i class="fa fa-image"></i></button>
                                    </span>
                                </div>
                                @if ($errors->has('after_photo'))<span class="validate-error">{{ $errors->first('after_photo') }}</span>@endif
                                <div class="d-block mt-1">
                                    <img id="after_photo_viewer" src="{{ (isset($service->after_photo )) ? asset($service->after_photo) : '' }}" style="max-height: 150px">
                                </div>
                            </div>
                        </div>
                        {{-- Đường dẫn video --}}
                        <div class="form-group row">
                            <label class="col-12 col-md-3 col-lg-3 col-form-label">
                                {{ trans('fotober.sadmin.service.video_link') }}
                                <br>
                                (Ex: https://player.vimeo.com/video/581952747)
                            </label>
                            <div class="col-12 col-md-9 col-lg-5">
                                <div class="input-group">
                                    <input type="text" name="video_link" id="video_link" class="form-control" value="{{ isset($service->video_link ) ? $service->video_link : old('video_link') }}">
                                    <span class="input-group-append">
                                        <button class="btn btn-info" type="button" onclick="browseServerVideoLink();"><i class="fa fa-image"></i></button>
                                    </span>
                                </div>
                                @if ($errors->has('video_link'))<span class="validate-error">{{ $errors->first('video_link') }}</span>@endif
                            </div>
                        </div>
                        {{-- Link read more --}}
                        <div class="form-group row">
                            <label class="col-12 col-md-3 col-lg-3 col-form-label">
                                {{ trans('fotober.sadmin.service.read_more') }}
                                <span class="text-danger ml-1">*</span>
                            </label>
                            <div class="col-12 col-md-9 col-lg-5">
                                <input type="text" name="read_more" id="read_more" value="{{ isset($service->read_more ) ? $service->read_more : old('read_more') }}" class="form-control">
                                @if ($errors->has('read_more'))<span class="validate-error">{{ $errors->first('read_more') }}</span>@endif
                            </div>
                        </div>
                        {{-- Mô tả nếu có --}}
                        <div class="form-group row">
                            <label class="col-12 col-md-3 col-lg-3 col-form-label">
                                {{ trans('fotober.sadmin.service.description') }}
                                <span class="text-danger ml-1">*</span>
                            </label>
                            <div class="col-12 col-md-9 col-lg-5">
                                <textarea name="description" id="description" rows="5" class="form-control" style="resize: none">{{ isset($service->description ) ? $service->description : old('description') }}</textarea>
                                @if ($errors->has('description'))<span class="validate-error">{{ $errors->first('description') }}</span>@endif
                            </div>
                        </div>
                        {{-- Số thứ tự hiển thị --}}
                        <div class="form-group row">
                            <label class="col-12 col-md-3 col-lg-3 col-form-label">
                                {{ trans('fotober.sadmin.service.sort') }}
                                <span class="text-danger ml-1">*</span>
                            </label>
                            <div class="col-12 col-md-9 col-lg-5">
                                <input type="number" name="sort" id="sort" value="{{ isset($service->sort ) ? $service->sort : old('sort') }}" class="form-control" min="1" max="1000" step="1">
                                @if ($errors->has('sort'))<span class="validate-error">{{ $errors->first('sort') }}</span>@endif
                            </div>
                        </div>
                        {{-- Submit --}}
                        <div class="form-group mb-0">
                            <button type="submit" class="btn btn-primary">{{ trans('fotober.common.btn_edit') }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('asset-bottom')
    <script type="text/javascript">
        $(document).ready(function () {});

        function browseServer() {
            var finder = new CKFinder();
            finder.selectActionFunction = function (fileUrl){
                $('#image').val(fileUrl.replace(window.location.origin + '/', ''));
                $('#image_viewer').attr('src', fileUrl);
            };
            finder.popup();
        }

        function browseServerBeforePhoto() {
            var finder = new CKFinder();
            finder.selectActionFunction = function (fileUrl){
                $('#before_photo').val(fileUrl.replace(window.location.origin + '/', ''));
                $('#before_photo_viewer').attr('src', fileUrl);
            };
            finder.popup();
        }

        function browseServerAfterPhoto() {
            var finder = new CKFinder();
            finder.selectActionFunction = function (fileUrl){
                $('#after_photo').val(fileUrl.replace(window.location.origin + '/', ''));
                $('#after_photo_viewer').attr('src', fileUrl);
            };
            finder.popup();
        }

        function browseServerVideoLink() {
            var finder = new CKFinder();
            finder.selectActionFunction = function (fileUrl){
                $('#video_link').val(fileUrl.replace(window.location.origin + '/', ''));
            };
            finder.popup();
        }

    </script>
@endsection
