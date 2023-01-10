require('./laravel-echo-pusher');

const mess_form = document.getElementById('message_from');
const type_send = document.getElementById('type-send');
const order_id = document.getElementById('order_id');
const mess_el = document.getElementById('content-mess-' + order_id.value);
const mess_input = document.getElementById('mess_input');
const message_send = document.getElementById('message_send');
const file_camera = document.getElementById('fileCamera');
const file_doc = document.getElementById('fileDoc');
const link = document.getElementById('link');
const submit_link = document.getElementById('submit_link');
const on_show_link = document.getElementById('on_show_link');
const media = document.getElementById('media');
const no_media = document.getElementById('no_media');
const no_file = document.getElementById('no_file');
const no_link = document.getElementById('no_link');

const content_sale = document.getElementById('content-mess-sale-' + order_id.value);


var objDiv = document.getElementById("chat-content");
var objDivOrder = document.getElementById("ajax_show_chat");

if(objDivOrder){

    console.log('objDivssOrder1')
} else{
    
    console.log('objDivOrder1')
}

if(objDiv){

    console.log('objDivssssOrder1')
} else{
    
    console.log('objDivOssrder1')
}
setTimeout(function() {

    objDiv.scrollTop = objDiv.scrollHeight;
    objDivOrder.scrollTop = objDivOrder.scrollHeight;
}, 1000);

//call customer
// console.log('messs', mess_input.value);
message_send.addEventListener('click', function(e) {
    e.preventDefault();
    // console.log(mess_input.value);
    var url = '';
    if (mess_input.value && mess_input.value != '' && mess_input.value != ' ') {
        if (type_send.value == 'customer') {
            url = '/customers/send-message';
        } else {
            url = '/sales/send-message';
        }
        const options = {
            method: 'post',
            url: url,
            data: {
                message: mess_input.value,
                order_id: order_id.value
            }
        }

        axios(options);
    }
    mess_input.value = '';
});

mess_form.addEventListener('click', function(e) {
    e.preventDefault();
    // console.log(mess_input.value);
    var url = '';
    if (mess_input.value) {
        if (type_send.value == 'customer') {
            url = '/customers/send-message';
        } else {
            url = '/sales/send-message';
        }
        const options = {
            method: 'post',
            url: url,
            data: {
                message: mess_input.value,
                order_id: order_id.value
            }
        }

        axios(options);
    }
    mess_input.value = '';
});

//call send image
file_camera.addEventListener('change', function() {

    var url = '';
    let data = new FormData();
    data.append('file', file_camera.files[0]);
    data.append('type', 'IMAGE');
    data.append('order_id', order_id.value);
    if (type_send.value == 'customer') {
        url = '/customers/send-message-file';
    } else {
        url = '/sales/send-message-file';
    }
    const options = {
        method: 'post',
        url: url,
        data: data
    }
    axios(options);
    $("#fileCamera").val('');
});

//send file
file_doc.addEventListener('change', function() {

    var url = '';
    let data = new FormData();
    data.append('file', file_doc.files[0]);
    data.append('type', 'FILE');
    data.append('order_id', order_id.value);
    if (type_send.value == 'customer') {
        url = '/customers/send-message-file';
    } else {
        url = '/sales/send-message-file';
    }
    const options = {
        method: 'post',
        url: url,
        data: data
    }
    axios(options);
    $("#fileDoc").val('');
});

//send link
submit_link.addEventListener('click', function(e) {
    var url = '';
    if (link.value) {
        if (type_send.value == 'customer') {
            url = '/customers/send-message';
        } else {
            url = '/sales/send-message';
        }
        const options = {
            method: 'post',
            url: url,
            data: {
                message: link.value,
                order_id: order_id.value,
                type: 'LINK'
            }
        }

        axios(options);
    }

    // $('#showLink').css("display", "none");
    $('#showLink').modal('hide');
    link.value = '';
});

on_show_link.addEventListener('click', function(e) {

    // $('#showLink').css("display", "block");
    $('#showLink').modal('show');
})

window.Echo.channel('chat')
    .listen('.message', (e) => {
        if (e.type == 'customer' && order_id.value === e.order_id) {
            var mess = '';
            switch (e.type_mess) {
                case 'TEXT':
                    mess = e.message_cus;
                    break;
                case 'IMAGE':
                        if (no_media) {
                            no_media.innerHTML = '';;
                        }
                        let mess_media = '<li><a href="' +
                            e.message_cus + '" title="Photo Title" target="_blank" data-rel="colorbox" class="cboxElement"><img class="img-media" alt="150x150" src="' +
                            e.message_cus + '"></a></li>';
                        $("#media").prepend(mess_media);
                        if (type_send.value == 'customer') {
                            mess = '<a href="' + e.message_cus + '" target="_blank" data-lightbox="chat-lightbox-' + e.order_id + '" data-title="Preview"><img class="img-chat sale img-fluid" src="' +
                                e.message_cus + '"></a>';
                        } else {
                            mess = '<a href="' + e.message_cus + '" target="_blank" data-lightbox="chat-lightbox-' + e.order_id + '" data-title="Preview"><img class="img-chat img-fluid" src="' +
                                e.message_cus + '"></a>';
                        }

                    break;
                case 'FILE':
                        if (no_file) {
                            no_file.innerHTML = '';;
                        }
                        let mess_file = '<div class="profile-activity clearfix"> <div class="row" > <div class ="col-md-10" ><a class="user" href="' +
                            e.message_cus + '" target="_blank">' +
                            e.file_name + ' </a><div class="col-md-2" >' +
                            e.created_at + '</div> </div> </div>';
                        $("#files").prepend(mess_file);
                        mess = '<a href="' +
                            e.message_cus + '" target="_blank">' +
                            e.file_name + '</a>';
                    break;
                case 'LINK':
                        if (no_link) {
                            no_link.innerHTML = '';;
                        }
                        let mess_link = '<div class="profile-activity clearfix"> <div class="row"> <div class="col-md-10" > <i class = "fa fa-link" > </i> <a class = "user" href="' +
                            e.message_cus + '" target="_blank" title="' +
                            e.message_cus + '" >' +
                            e.message_cus.substring(0, 24) + ' </a> </div> <div class="col-md-2" >' +
                            e.created_at + '</div> </div> </div> ';
                        $("#links").prepend(mess_link);
                        mess = '<a href="' +
                            e.message_cus + '" target="_blank">' +
                            e.message_cus + '</a>';
                    break;
            }
            if (type_send.value == 'customer') {
                // mess_el.innerHTML += '<li class="reverse"><div class="chat-content cus"><h5>'+ e.sender_name +'</h5><div class="box bg-light-inverse">' +
                //     mess + '</div><div class="chat-time">'+ e.created_at +'</div></div><div class="chat-img"><img class="avatar" src="'+ e.sender_avatar +'" alt="avatar"></div></li>';
                mess_el.innerHTML += '<li class="reverse"><div class="chat-content cus"><div class="box bg-light-inverse">' +
                    mess + '</div></div><div class="chat-img"><img class="avatar" src="'+ e.sender_avatar +'" alt="avatar"></div></li>';
            } else {
                // content_sale.innerHTML += '<li><div class="chat-img"><img src="'+ e.sender_avatar +'" alt="avatar"></div><div class="chat-content sale"><h5>'+ e.sender_name +'</h5><div class="box bg-light-info">' +
                //     mess + '</div><div class="chat-time">'+ e.created_at +'</div></div></li>';
                    content_sale.innerHTML += '<li><div class="chat-img"><img src="'+ e.sender_avatar +'" alt="avatar"></div><div class="chat-content sale"><div class="box bg-light-info">' +
                        mess + '</div></div></li>';
            }
        } else if (e.type == 'sale' && order_id.value === e.order_id) {
            var mess = '';
            switch (e.type_mess) {
                case 'TEXT':
                    mess = e.message_sale;
                    break;
                case 'IMAGE':
                    if (no_media) {
                        no_media.innerHTML = '';;
                    }
                    let mess_media = '<li><a href="' +
                        e.message_sale + '" title="Photo Title" target="_blank" data-rel="colorbox" class="cboxElement"><img class="img-media" alt="150x150" src="' +
                        e.message_sale + '"></a></li>';
                    $("#media").prepend(mess_media);
                    if (type_send.value == 'customer') {
                        mess = '<a href="' + e.message_cus + '" target="_blank" data-lightbox="chat-lightbox-' + e.order_id + '" data-title="Preview"><img class="img-chat sale img-fluid" src="' +
                            e.message_sale + '"></a>';
                    } else {
                        mess = '<a href="' + e.message_cus + '" target="_blank" data-lightbox="chat-lightbox-' + e.order_id + '" data-title="Preview"><img class="img-chat img-fluid" src="' +
                            e.message_sale + '"></a>';
                    }

                    break;
                case 'FILE':
                    if (no_file) {
                        no_file.innerHTML = '';;
                    }
                    let mess_file = '<div class="profile-activity clearfix"> <div class="row" > <div class ="col-md-10" ><a class="user" href="' +
                        e.message_sale + '" target="_blank">' +
                        e.file_name + ' </a><div class="col-md-2" >' +
                        e.created_at + '</div> </div> </div>';
                    $("#files").prepend(mess_file);
                    mess = '<a href="' +
                        e.message_sale + '" target="_blank">' +
                        e.file_name + '</a>';
                    break;
                case 'LINK':
                    if (no_link) {
                        no_link.innerHTML = '';
                    }
                    let mess_link = '<div class="profile-activity clearfix"> <div class="row"> <div class="col-md-10" > <i class = "fa fa-link" > </i> <a class = "user" href="' +
                        e.message_sale + '" target="_blank" title="' +
                        e.message_sale + '" >' +
                        e.message_sale.substring(0, 24) + ' </a> </div> <div class="col-md-2" >' +
                        e.created_at + '</div> </div> </div> ';
                    $("#links").prepend(mess_link);
                    mess = '<a href="' +
                        e.message_sale + '" target="_blank">' +
                        e.message_sale + '</a>';
                    break;
            }
            if (type_send.value == 'customer') {
                // mess_el.innerHTML += '<li><div class="chat-img"><img src="'+ e.sender_avatar +'" alt="avatar"></div><div class="chat-content cus"><h5>'+ e.sender_name +'</h5><div class="box bg-light-info">' +
                // mess + '</div><div class="chat-time">'+ e.created_at +'</div></div></li>';
                mess_el.innerHTML += '<li><div class="chat-img"><img src="'+ e.sender_avatar +'" alt="avatar"></div><div class="chat-content cus"><div class="box bg-light-info">' +
                mess + '</div></div></li>';
            } else {
                // content_sale.innerHTML += '<li class="reverse"><div class="chat-content sale"><h5>'+ e.sender_name +'</h5><div class="box bg-light-inverse">' +
                // mess + '</div><div class="chat-time">'+ e.created_at +'</div></div><div class="chat-img"><img class="avatar sale" src="'+ e.sender_avatar +'" alt="avatar"></div></li>';
                content_sale.innerHTML += '<li class="reverse"><div class="chat-content sale"><div class="box bg-light-inverse">' +
                mess + '</div></div><div class="chat-img"><img class="avatar sale" src="'+ e.sender_avatar +'" alt="avatar"></div></li>';
            }
        }
        setTimeout(function() {

            objDiv.scrollTop = objDiv.scrollHeight;
            objDivOrder.scrollTop = objDivOrder.scrollHeight + 10;
        }, 1000);
    });
