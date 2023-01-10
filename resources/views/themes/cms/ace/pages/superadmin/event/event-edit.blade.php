@extends(\App\Helpers\Constants::VIEW_LAYOUT_PATH . '.master-layout')
@section('title')
    {{ trans('fotober.sadmin.service.edit') }}
@endsection
@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="row">
                <div class="col-lg-12">
                    <div class="widget-box">
                        <div class="widget-header">
                            <h4 class="widget-title">{{ trans('fotober.sadmin.service.edit') }}</h4>
                        </div>
                        <div class="widget-body">
                            <div class="widget-main">
                                <form class="form-horizontal" role="form" method="post" action="{{ route('superadmin_update_service') }}" enctype="multipart/form-data">
                                    {{-- CSRF --}}
                                    <div class="form-group">
                                        {{ csrf_field() }}
                                        <input type="hidden" name="id" id="id" value="{{ $service->id }}" class="form-control">
                                    </div>
                                    {{-- Tên DV --}}
                                    <div class="form-group">
                                        <label class="col-sm-3 control-label no-padding-right" for="name">
                                            {{ trans('fotober.sadmin.service.name') }}&nbsp;(<span class="form-required"></span>)
                                        </label>
                                        <div class="col-sm-5">
                                            <input type="text" name="name" id="name" value="{{ isset($service->name ) ? $service->name : old('name') }}" class="form-control">
                                            @if ($errors->has('name'))<span class="validate-error">{{ $errors->first('name') }}</span>@endif
                                        </div>
                                    </div>
                                    {{-- Mã DV --}}
                                    <div class="form-group">
                                        <label class="col-sm-3 control-label no-padding-right" for="code">
                                            {{ trans('fotober.sadmin.service.code') }}&nbsp;(<span class="form-required"></span>)
                                        </label>
                                        <div class="col-sm-5">
                                            <input type="text" name="code" id="code" value="{{ isset($service->code ) ? $service->code : old('code') }}" class="form-control">
                                            @if ($errors->has('code'))<span class="validate-error">{{ $errors->first('code') }}</span>@endif
                                        </div>
                                    </div>
                                    {{-- Ảnh đại diện --}}
                                    <div class="form-group">
                                        <label class="col-sm-3 control-label no-padding-right" for="image">
                                            {{ trans('fotober.sadmin.service.image') }}&nbsp;(<span class="form-required"></span>)
                                        </label>
                                        <div class="col-sm-5">
                                            <div class="row">
                                                <div class="col-sm-12">
                                                    <input type="text" name="image" id="image" class="form-control" readonly
                                                           value="{{ isset($service->image ) ? $service->image : old('image') }}">
                                                    @if ($errors->has('image'))<span class="validate-error">{{ $errors->first('image') }}</span>@endif
                                                </div>
                                            </div>
                                            <div class="row" style="margin-top: 5px">
                                                <div class="col-sm-12">
                                                    <img id="image_viewer" src="{{ (isset($service->image )) ? asset($service->image) : '' }}" style="max-height: 150px">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-1">
                                            <i class="fa fa-fw fa-image" onclick="browseServer();" style="font-size: 34px; cursor: pointer" title="Chọn ảnh"></i>
                                        </div>
                                    </div>
                                    {{-- Mô tả nếu có --}}
                                    <div class="form-group">
                                        <label class="col-sm-3 control-label no-padding-right" for="address">{{ trans('fotober.sadmin.service.description') }}</label>
                                        <div class="col-sm-5">
                                            <textarea name="description" id="description" rows="5" class="form-control" style="resize: none">{{ isset($service->description ) ? $service->description : old('description') }}</textarea>
                                        </div>
                                    </div>
                                    {{-- Submit --}}
                                    <div class="form-group">
                                        <label class="col-sm-3 control-label no-padding-right" for="notes"></label>
                                        <div class="col-sm-5">
                                            <button type="submit" class="btn btn-sm btn-primary">{{ trans('fotober.common.btn_edit') }}</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
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

    </script>
@endsection
