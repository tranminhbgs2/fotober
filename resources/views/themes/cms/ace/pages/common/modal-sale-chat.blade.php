<div class="modal fade modal_order_info" tabindex="-1" role="dialog" aria-labelledby="vcenter" id="{{ $modal_id }}">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            {{-- <div class="modal-header">
                <h4 class="modal-title">{{ $modal_title }}</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div> --}}
            <div class="col-12 p-0">
                <div class="card m-b-0" style="background: transparent;">
                    <!-- .chat-row -->
                    <div class="chat-main-box">
                        <!-- .chat-left-panel -->
                        <div class="chat-left-aside" style="width: calc(100% - 1px);">
                            <div class="chat-main-header">
                                <div class="row">
                                    <h4 class="box-title mb-0 color-blue font-weight-bold col-11" style="line-height: 55px" id="name-service">Chat Message</h4>
                                    <button type="button" class="close show-mobile" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                </div>
                            </div>
                            
                            <div class="chat-rbox" id="{{ $ajax_div_id }}">
                            </div>
                            <div class="card-body border-top chat-control-abs chat-order box-submit" id="show-chat">
                                <div class="row">
                                <div class="col-md-12" style="padding: 0px 30px">
                                        <textarea class="publisher-input" style="width:100%" type="text" placeholder="Write something" name="message" id="mess_input"></textarea>
                                        <input type="hidden" name="type_send" id="type-send" value="sales">
                                        <input type="hidden" name="order_id" id="order_id" value="">
                                </div>
                                </div>
                                <div class="row" style="float: right;">
                                    <div class="col-md-12 text-right d-flex align-items-center justify-content-start" style="padding: 0px 30px; float: right;">
                                        <div class="list-btn-chat">
                                            {{-- <span class="file-group">
                                                <i class="ti-face-smile"></i>
                                            </span> --}}
                                            <span class="d-inline-block">
                                                <button type="button" data-toggle="modal" class="btn btn-light" data-target="#showLink" id="on_show_link">
                                                    {{-- <i class="ti-link"></i> --}}
                                                    <img src="{{ asset('images/link.png') }}" style="max-width: 20px;" alt="">
                                                </button>
                                            </span>
                                            <span class="d-inline-block">
                                                <form id='send_doc' enctype="multipart/form-data">
                                                    <input id="fileDoc" type="file" accept=".doc,.docx,.pdf" hidden />
                                                    <button id="buttonDoc" type="button" class="btn btn-light">
                                                        {{-- <i class="ti-id-badge"></i> --}}
                                                        <img src="{{ asset('images/folder.png') }}" style="max-width: 20px;" alt="">
                                                    </button>
                                                    <input type='submit' value='Submit' hidden/>
                                                </form>
                                            </span>
                                            <span class="d-inline-block">
                                                <form id='send_image' name="send_image" enctype="multipart/form-data">
                                                    <input id="fileCamera" name="fileCamera" type="file" accept="image/*" hidden />
                                                    <button id="buttonCamera" class="btn btn-light" type="button">
                                                        {{-- <i class="ti-image"></i> --}}
                                                        <img src="{{ asset('images/image.png') }}" style="max-width: 20px;" alt="">
                                                    </button>
                                                    <input type='submit' id="img_submit" hidden/>
                                                </form>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <form id="message_from" class="d-flex">
                                <div class="row" style="bottom: 15px; position: absolute; right: 25px;">
                                    <input type="submit" class="btn btn-info btn-md" id="message_send" data-abc="true" value="Submit">
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Modal -->
<div class="modal fade" id="showLink" tabindex="-1" role="dialog"        aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">{{ trans('fotober.order.link') }}</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="margin-top: -25px">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
                <div class="form-group">
                  <label for="exampleInputEmail1">{{ trans('fotober.order.link') }}</label>
                  <input type="text" class="form-control" id="link" aria-describedby="emailHelp" placeholder="{{ trans('fotober.order.enter_link') }}" required>
                </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ trans('fotober.common.close') }}</button>
          <button type="button" id="submit_link" class="btn btn-primary">{{ trans('fotober.common.send') }}</button>
        </div>
      </div>
    </div>
</div>
<script>
    function showChat(order_id, name_service, assigned_sale_id){
            $('#data-loading').show();
            $('#ajax_show_chat').html('');
            $('#name-service').html(name_service);
            console.log('show chat')
            document.getElementById('order_id').value = order_id;
            if(window.user.user_id != assigned_sale_id){
                document.getElementById("show-chat").style.display = "none";
            }
            // $('#order_id').val() = order_id;
            $.ajax({
                url: '{{ route('sale_order_chat_ajax') }}',
                type: 'POST',
                dataType: 'HTML',
                data: {
                    _token: '{{ csrf_token() }}',
                    order_id: order_id,
                    user_id: window.user.user_id,
                    account_type: window.user.account_type,
                },
                success: function(result) {
                    $('#data-loading').hide();
                    //
                    let total_no_seen_cus = document.getElementById('total_no_seen_cus_'+ order_id);
                    total_no_seen_cus.innerHTML = '';
                    $('#ajax_show_chat').html(result);
                    $('#chat_modal').modal('show');
                    setTimeout(function() {
                    let height = document.getElementsByClassName("chat-main-box")[0].offsetHeight - document.getElementsByClassName("box-submit")[0].offsetHeight - 5 - 255;
                    $(".chat-list-order").height(height);
                    var myDiv = document.getElementById("ajax_show_chat");
                    myDiv.scrollTop = myDiv.scrollHeight;
                    }, 1500);
                },
                error: function (jqXhr, textStatus, errorMessage) {
                    $('#data-loading').hide();
                }
            });
        }

        
        //chatbox
        document.getElementById('buttonCamera').addEventListener('click', function (){
            console.log('aazzza');
            document.getElementById('fileCamera').click();
        });
        document.getElementById('buttonDoc').addEventListener('click', function (){
            console.log('aaa');
            document.getElementById('fileDoc').click();
        });

        $(function() {

        "use strict";

        $('.chat-left-inner > .chatonline, .chat-rbox').perfectScrollbar();

        // // this is for the left-aside-fix in content area with scroll
        // var chtin = function() {
        //     var topOffset = 270;
        //     var height = ((window.innerHeight > 0) ? window.innerHeight : this.screen.height) - 1;
        //     height = height - topOffset;
        //     $(".chat-left-inner").css("height", (height) + "px");
        // };
        // $(window).ready(chtin);
        // $(window).on("resize", chtin);

        $(".open-panel").on("click", function() {
            $(".chat-left-aside").toggleClass("open-pnl");
            $(".open-panel i").toggleClass("ti-angle-left");
        });
        });
        $('.menu-chat').on('click', function(e) {
        $(this).toggleClass('active');
        $('.chat-right-aside').toggleClass("show"); //you can list several class names
        e.preventDefault();
        });
        $('#nav-menu-collapse').on('click', function(e) {
        $(this).toggleClass('active');
        $('#nav-content-collapse').toggleClass("show"); //you can list several class names
        e.preventDefault();
        });
</script>