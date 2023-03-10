@extends(\App\Helpers\Constants::VIEW_LAYOUT_PATH . '.master-layout')
@section('title')
    {{ trans('fotober.order.create_title') }}
@endsection
@section('content')
    <!-- ============================================================== -->
    <!-- Bread crumb and right sidebar toggle -->
    <!-- ============================================================== -->
    <div class="row page-titles">
        <div class="col-md-5 align-self-center">
            <h4 class="text-themecolor">{{ trans('fotober.order.create_title') }}</h4>
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
                    <form id="form-create" class="form-horizontal" role="form" method="post" action="{{ route('customer_order_store') }}" enctype="multipart/form-data">
                        {{-- CSRF --}}
                        <div class="form-group m-0">
                            {{ csrf_field() }}
                        </div>
                        <div class="form-group row">
                            <label class="col-12 col-md-3 col-lg-2 col-form-label">{{ trans('fotober.order.form_name') }}<span class="text-danger ml-1">*</span></label>
                            <div class="col-12 col-md-9 col-lg-4">
                                <input type="text" name="name" id="name" value="{{ old('name') }}" class="form-control" placeholder="Living room">
                                @if ($errors->has('name'))<span class="validate-error">{{ $errors->first('name') }}</span>@endif
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-12 col-md-3 col-lg-2 col-form-label">{{ trans('fotober.order.form_service_type') }}<span class="text-danger ml-1">*</span></label>
                            <div class="col-12 col-md-9 col-lg-4">
                                <select class="form-control custom-select" name="service_id" id="service_id">
                                    <option value="">{{ trans('fotober.common._select_') }}</option>
                                    @foreach($services as $service_key => $service)
                                        @if ($url_service_id > 0)
                                            <option value="{{ $service->id }}" {{ ($url_service_id == $service->id) ? 'selected' : '' }}>{{ $service->name }}</option>
                                        @else
                                            <option value="{{ $service->id }}" {{ ((old('service_id') == $service->id) ? 'selected' : '') }}>{{ $service->name }}</option>
                                        @endif
                                    @endforeach
                                </select>
                                @if ($errors->has('service_id'))<span class="validate-error">{{ $errors->first('service_id') }}</span>@endif
                            </div>
                        </div>

                        {{-- <div class="form-group row">
                            <label class="col-12 col-md-3 col-lg-2 col-form-label">{{ trans('fotober.order.form_link_upload') }}</label>
                            <div class="col-12 col-md-9 col-lg-4 d-flex">
                                <div class="d-flex align-items-center custom-control custom-radio mr-4">
                                    <input name="options" id="o_link" type="radio" class="ace" value="LINK"
                                        @if (old('options') != 'UPLOAD')
                                        checked
                                    @endif
                                    >
                                    <span class="lbl">&nbsp;{{ trans('fotober.order.form_send_alink') }}</span>
                                </div>
                                <div class="d-flex align-items-center custom-control custom-radio">
                                    <input name="options" id="o_upload"  type="radio" class="ace" value="UPLOAD" @if (old('options') == 'UPLOAD')
                                        checked
                                        onclick="checkOption(old('options'))"
                                    @endif>
                                    <span class="lbl">&nbsp;{{ trans('fotober.order.form_upload_file') }}</span>
                                </div>
                            </div>
                        </div> --}}

                        <div class="form-group row">
                            <label class="col-12 col-md-3 col-lg-2 col-form-label"></label>
                            <div class="col-12 col-md-9 col-lg-4">
                                <div class="d-block">
                                    <div class="input-group">
                                        <div class="upload-file w-100 form-group mb-0">
                                            <div class="upload__box">
                                                <div class="upload__img-wrap row"></div>
                                                <div class="upload__btn-box">
                                                  <label class="upload__btn" title="Max file size {{ \App\Helpers\Constants::UPLOAD_MAX_SIZE }} KB">
                                                    <p class="btn btn-info mb-0"><i class="ti-export mr-1"></i> Upload photo</p>
                                                    <input type="file" id="upload_file" class="form-control upload__inputfile" name="upload_file[]" multiple data-max_length="10">
                                                  </label>
                                                </div>
                                                @if ($errors->has('upload_file.0'))<span class="validate-error">{{ $errors->first('upload_file.0') }}</span>@endif
                                            </div>
                                        </div>
                                        <div class="upload__img-wrap"></div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- N???u ch???n g???i link --}}
                        <div id="option-link">
                            <div class="form-group row">
                                <label class="col-12 col-md-3 col-lg-2 col-form-label p-0 d-flex align-items-center">
                                    <span class="ml-auto">Or</span>
                                </label>

                                <div class="col-12 col-md-9 col-lg-4">
                                    <div class="input-group">
                                        <input type="text" name="link[]" id="link" class="form-control" placeholder="Paste link here">
                                        @if ($errors->has('link'))<span class="validate-error">{{ $errors->first('link') }}</span>@endif
                                        <div class="input-group-append">
                                            <button class="btn btn-info" type="button" onclick="addToLink()"><i class="ti-plus"></i></button>
                                        </div>
                                    </div>
                                    @if ($errors->has('options'))<span class="validate-error">{{ $errors->first('options') }}</span>@endif
                                </div>
                            </div>
                        </div>

                        {{-- T??y ch???n upload file --}}
                        {{-- <div id="option-upload"> --}}
                        {{-- <div class="form-group row">
                            <label class="col-12 col-md-3 col-lg-2 col-form-label" for="upload_file">
                                {{ trans('fotober.order.form_upload_file') }}&nbsp;(<span class="form-required"></span>)
                            </label>
                            <div class="col-12 col-md-9 col-lg-4">
                                <input type="file" id="upload_file" class="form-control" name="upload_file[]" multiple>
                                @if ($errors->has('upload_file'))<span class="validate-error">{{ $errors->first('upload_file') }}</span>@endif
                            </div>
                        </div> --}}

                        <div class="form-group row">
                            <label class="col-12 col-md-3 col-lg-2 col-form-label">{{ trans('fotober.order.form_turn_around') }}<span class="text-danger ml-1">*</span></label>
                            <div class="col-12 col-md-9 col-lg-4">
                                <select class="form-control custom-select" name="turn_arround_time">
                                    @foreach($turn_arround_times as $time_key => $time_value)
                                        <option value="{{ $time_key }}" {{ (old('turn_arround_time') == $time_key) ? 'selected' : (($time_key == 24) ? 'selected' : '') }}>{{ $time_value }}</option>
                                    @endforeach
                                </select>
                                @if ($errors->has('turn_arround_time'))<span class="validate-error">{{ $errors->first('turn_arround_time') }}</span>@endif
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-12 col-md-3 col-lg-2 col-form-label">{{ trans('fotober.order.instructions') }}</label>
                            <div class="col-12 col-md-9 col-lg-4">
                                <textarea name="notes" id="notes" rows="5" class="form-control" placeholder="Please Provide Intructions" style="resize: none">{{ old('notes') }}</textarea>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-12 col-md-3 col-lg-2 col-form-label"></label>
                            <div class="col-12 col-md-9 col-lg-4">
                                <div class="text-right">
                                    <button onclick="inerStatus()" class="btn btn-info ml-auto mr-1">{{ trans('fotober.common.btn_create_submit') }}</button>
                                    <button type="submit" class="btn btn-secondary ml-auto mr-1"> {{ trans('fotober.common.btn_draft') }} </button>
                                </div>
                            </div>
                        </div>

                        <input type="hidden" id="status" name="status" value="0">
                        <input name="options" type="hidden" class="ace" value="">

                        {{-- Modal upload file --}}
                        {{-- <div class="modal fade" tabindex="-1" role="dialog" aria-labelledby="gridSystemModalLabel" id="modal-upload-file">
                            <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h4 class="modal-title">Upload File</h4>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="form-group row">
                                            <label class="col-12 col-md-4 col-lg-3 col-form-label" for="upload_file">
                                                {{ trans('fotober.order.form_upload_file') }}&nbsp;(<span class="form-required"></span>)
                                            </label>
                                            <div class="col-12 col-md-6 col-lg-6">
                                                <input type="file" id="upload_file" class="form-control" name="upload_file[]" multiple style="height: 42px;">
                                                @if ($errors->has('upload_file'))<span class="validate-error">{{ $errors->first('upload_file') }}</span>@endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div> --}}

                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('asset-bottom')

    <script type="text/javascript">
        let options = $("#form-create input[name='options']").val();

        $(document).ready(function () {

            ImgUpload();

            /**
            * [Click] Check file upload when click button
            */
            $("#upload_file").on('change', function () {
                var file = $(this).val();

                if(file){
                    $('#btn-modal-upload-file .text').html('Uploaded');
                    if( (options == 'LINK') || (options == 'ALL') ){
                        options = 'ALL';
                    } else {
                        options = 'UPLOAD';
                    }
                }
            });

            $('#deadline').datetimepicker({
                format: 'DD/MM/YYYY HH:mm:ss',
                minDate: moment().add(2, 'hours').format('DD/MM/YYYY HH:mm:ss')
            }).next().on(ace.click_event, function(){
                $(this).prev().focus();
            });
        });

        function inerStatus(){
            var link = $("#form-create input[name='link[]']").val();
            $("#form-create input[name='status']").val(1);
            if(link){
                if(options == 'ALL' || options == 'UPLOAD'){
                    options = 'ALL';
                } else {
                    options = 'LINK';
                }
            }
            $("#form-create input[name='options']").val(options);

            $('#form-create').submit();
        }
        var total_link = 1;
        function addToLink () {
            var value = document.getElementsByName('link[]');
            var i;
            value_old = [];
            for (i = 0; i < value.length; i++) {
                value_old.push(value[i].value);
            }
            var title = '';
            var item =    '<div class="form-group row" id="item-link-'+total_link+'">'
                    +'<label class="col-12 col-md-3 col-lg-2 col-form-label" for="url">'+ title+'</label><div class="col-12 col-md-9 col-lg-4"><div class="input-group"><input type="text" name="link[]" id="link-'+total_link+'" class="form-control" placeholder="Paste link here"><div class="input-group-append"><button class="btn btn-danger" type="button" onclick="removeToLink('+total_link+')"><i class="ti-minus"></i></button></div></div></div></div>';
            document.getElementById('option-link').innerHTML += item;
            total_link++;
            var x;
            for (x = 0; x < value_old.length; x++) {
                value[x].value = value_old[x];
            }
        }
        function removeToLink(number){
            var item = document.getElementById('item-link-'+number);
            item.remove();
        }


        function ImgUpload() {
            var imgWrap = "";
            var imgArray = [];

            $('#upload_file').each(function () {
                $(this).on('change', function (e) {
                imgWrap = $(this).closest('.upload__box').find('.upload__img-wrap');
                var maxLength = $(this).attr('data-max_length');

                var files = e.target.files;
                var filesArr = Array.prototype.slice.call(files);
                var iterator = 0;
                filesArr.forEach(function (f, index) {

                    if (!f.type.match('image.*')) {
                        return;
                    }

                    if (imgArray.length > maxLength) {
                        return false;
                    } else {
                        var len = 0;
                        for (var i = 0; i < imgArray.length; i++) {
                            if (imgArray[i] !== undefined) {
                                len++;
                            }
                        }
                        if (len > maxLength) {
                            return false;
                        } else {
                            imgArray.push(f);

                            var reader = new FileReader();
                            reader.onload = function (e) {
                                var html = "<div class='upload__img-box col-4 col-md-4 col-lg-4'><div style='margin-bottom: 10px; background-image: url(" + e.target.result + ")' data-number='" + $(".upload__img-close").length + "' data-file='" + f.name + "' class='img-bg'><div class='upload__img-close'></div></div></div>";
                                imgWrap.append(html);
                                iterator++;
                            }
                            reader.readAsDataURL(f);
                        }
                    }
                });
                });
            });

            $('body').on('click', ".upload__img-close", function (e) {
                var file = $(this).parent().data("file");
                for (var i = 0; i < imgArray.length; i++) {
                    if (imgArray[i].name === file) {
                        imgArray.splice(i, 1);
                        break;
                    }
                }
                $(this).parent().parent().remove();
            });
        }
    </script>
@endsection
