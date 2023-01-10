/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

const { default: axios } = require('axios');
const { parseHTML } = require('jquery');

require('./bootstrap');

// window.Vue = require('vue');

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

// Vue.component('example-component', require('./components/ExampleComponent.vue'));

// const app = new Vue({
//     el: '#app',
// });

const mess_form = document.getElementById('message_from');
const type_send = document.getElementById('type-send');
const order_id = document.getElementById('order_id');
const mess_el = document.getElementById('content-mess-' + order_id.value);
const mess_input = document.getElementById('mess_input');
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
setTimeout(function() {

    objDiv.scrollTop = objDiv.scrollHeight;
    console.log(objDiv.scrollTop);
}, 2000);

//call customer
mess_form.addEventListener('submit', function(e) {
    e.preventDefault();
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

    $('#showLink').css("display", "none");
    link.value = '';
});

on_show_link.addEventListener('click', function(e) {

    $('#showLink').css("display", "block");
})

window.Echo.channel('chat')
    .listen('.message', (e) => {
        if (e.type == 'customer' && order_id.value === e.order_id) {
            var mess = '';
            switch (e.type_mess) {
                case 'TEXT':
                    mess = '<p class="text-break">' +
                        e.message_cus + '</p>';
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
                        mess = '<a href="' + e.message_cus + '" data-lightbox="chat-lightbox-' + e.order_id + '" data-title="Preview"><img class="img-chat sale" src="' +
                            e.message_cus + '" style="max-width: 50px; max-height: 50px"></a>';
                    } else {
                        mess = '<a href="' + e.message_cus + '" data-lightbox="chat-lightbox-' + e.order_id + '" data-title="Preview"><img class="img-chat" src="' +
                            e.message_cus + '" style="max-width: 50px; max-height: 50px"></a>';
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
                    mess = '<p><a href="' +
                        e.message_cus + '" target="_blank">' +
                        e.file_name + '</a></p>';
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
                    mess = '<p><a href="' +
                        e.message_cus + '" target="_blank">' +
                        e.message_cus + '</a></p>';
                    break;
            }
            if (type_send.value == 'customer') {
                mess_el.innerHTML += '<div class="media media-chat media-chat-reverse"><div class="media-body sale">' +
                    mess + '</div></div>';
            } else {
                content_sale.innerHTML += '<div class="media media-chat"> <img class="avatar" src="https://img.icons8.com/color/36/000000/administrator-male.png"><div class="media-body cus">' +
                    mess +
                    '</div></div>';
            }
        } else if (e.type == 'sale' && order_id.value === e.order_id) {
            var mess = '';
            switch (e.type_mess) {
                case 'TEXT':
                    mess = '<p class="text-break">' +
                        e.message_sale + '</p>';
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
                        mess = '<a href="' + e.message_cus + '" data-lightbox="chat-lightbox-' + e.order_id + '" data-title="Preview"><img class="img-chat sale" src="' +
                            e.message_sale + '" style="max-width: 50px; max-height: 50px"></a>';
                    } else {
                        mess = '<a href="' + e.message_cus + '" data-lightbox="chat-lightbox-' + e.order_id + '" data-title="Preview"><img class="img-chat" src="' +
                            e.message_sale + '" style="max-width: 50px; max-height: 50px"></a>';
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
                    mess = '<p><a href="' +
                        e.message_sale + '" target="_blank">' +
                        e.file_name + '</a></p>';
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
                    mess = '<p><a href="' +
                        e.message_sale + '" target="_blank">' +
                        e.message_sale + '</a></p>';
                    break;
            }
            if (type_send.value == 'customer') {
                mess_el.innerHTML += '<div class="media media-chat"> <img class="avatar" src="https://img.icons8.com/color/36/000000/administrator-male.png" alt="..."><div class="media-body cus">' + mess +
                    '</div></div>';
            } else {
                content_sale.innerHTML += '<div class="media media-chat media-chat-reverse"><div class="media-body sale">' +
                    mess + '</div></div>';
            }
        }
        setTimeout(function() {

            objDiv.scrollTop = objDiv.scrollHeight;
            console.log(objDiv.scrollTop);
        }, 1500);
    });
