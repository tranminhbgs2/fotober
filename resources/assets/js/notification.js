require('./laravel-echo-pusher');

window.Echo.channel('notification')
    .listen('NotificationEvent', (e) => {
        console.log(e)

        //Đếm số tin nhắn chưa đọc
        
        if(e.scope == "CHAT"){
            const order_id = e.order_id;
            
            if(e.total_no_seen_sale > 0){
                const total_no_seen_sale = document.getElementById('total_no_seen_sale_'+ order_id);
                total_no_seen_sale.innerHTML = '<sup class="ftb-badge-count"><span class="ftb-scroll-number-only"><span class="total_seen ftb-scroll-number-only-unit current" >'+e.total_no_seen_sale+'</span></span></sup>';
            } else{
                // total_no_seen_sale.innerHTML = '';
            }
            if(e.total_no_seen_cus > 0){
                const total_no_seen_cus = document.getElementById('total_no_seen_cus_'+ order_id);
                total_no_seen_cus.innerHTML = '<sup class="ftb-badge-count"><span class="ftb-scroll-number-only"><span class="total_seen ftb-scroll-number-only-unit current" >'+e.total_no_seen_cus+'</span></span></sup>';
            } else{
                // total_no_seen_cus.innerHTML = '';
            }
        }
        // Cập nhật thông báo
        updateNotification();

        // Xử lý show thông báo, chỉ show thông báo do người khác gửi
        if (parseInt(window.user.user_id) != parseInt(e.sender_id)) {
            // Nếu người nhận là người đang login thì show
            if (parseInt(window.user.user_id) == parseInt(e.receiver_id)) {
                showToast(e.message_vi);
            }

            if (e.account_type == 'CUSTOMER' && e.account_type == window.user.account_type) {
                /*if (parseInt(window.user.user_id) == parseInt(e.order.customer_id)) {
                    showToast(e.message_vi);
                }*/
                console.log('CUSTOMER');
            }

            if (e.account_type == 'SALE' && e.account_type == window.user.account_type) {
                /*if (window.user.is_admin || (parseInt(window.user.user_id) == parseInt(e.order.assigned_sale_id))) {
                    showToast(e.message_vi);
                }*/
                console.log('SALE');
            }

            if (e.account_type == 'ADMIN' && e.account_type == window.user.account_type) {
                //showToast(e.message_vi);
                console.log('ADMIN');
            }

            if (e.account_type == 'EDITOR' && e.account_type == window.user.account_type) {
                /*if (parseInt(window.user.user_id) == parseInt(e.order.assigned_editor_id)) {
                    showToast(e.message_vi);
                }*/
                console.log('EDITOR');
            }

            if (e.account_type == 'QAQC' && e.account_type == window.user.account_type) {
                /*if (parseInt(window.user.user_id) == parseInt(e.order.assigned_qaqc_id)) {
                    showToast(e.message_vi);
                }*/
                console.log('QAQC');
            }

            if (e.account_type == 'SUPER_ADMIN' && e.account_type == window.user.account_type) {}

            if (e.account_type == 'STAFF' && e.account_type == window.user.account_type) {}
        }
    });

function showToast(message) {
    if (message) {
        $.toast({
            heading: 'Fotober',
            text: message,
            position: 'bottom-right',
            stack: 5,
            hideAfter: 15000,
            bgColor: '#438eb9',
            loaderBg: '#a34335',
        });
        return true;
    }

    return false;
}
