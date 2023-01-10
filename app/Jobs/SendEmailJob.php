<?php

namespace App\Jobs;

use App\Helpers\Constants;
use App\Models\Customer;
use App\Services\Email\SendMail;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

use PHPMailer\PHPMailer;

class SendEmailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $scope;
    protected $email;
    protected $order;

    protected $subject;
    protected $message;
    protected $customer_id;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($scope, $data)
    {
        $this->scope = $scope;
        $this->email = isset($data['email']) ? $data['email'] : null;
        $this->fullname = isset($data['fullname']) ? $data['fullname'] : $this->email;
        $this->customer_id = isset($data['customer_id']) ? $data['customer_id'] : null;
        $this->order = isset($data['order']) ? $data['order'] : null;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(SendMail $sendMail)
    {
        // Lấy email KH từ id truyền sang nếu có
        if ($this->customer_id > 0) {
            $customer = Customer::where('id', $this->customer_id)->first();
            if ($customer) {
                if($this->email == null){
                    $this->email = $customer->email;
                }
                $this->fullname = isset($customer->fullname) ? $customer->fullname : $this->email;
            }
        }
        if($this->email){

            // Lấy tiêu đề theo từng sự kiện
            switch ($this->scope) {
                case Constants::EMAIL_ORDER_CREATE:
                    $this->subject = 'Order ID ' . (isset($this->order->code) ? $this->order->code : '()') . ' has been submitted successfully';
                    break;
                case Constants::EMAIL_ORDER_DELIVERY:
                    $this->subject = (isset($this->order->name) ? $this->order->name : 'Fotober') . ' Status Update';
                    break;
                case Constants::EMAIL_ORDER_COMPLETED:
                    $this->subject = (isset($this->order->name) ? $this->order->name : 'Fotober') . ' Status Update';
                    break;
                case Constants::EMAIL_ORDER_AWAIT_PAYMENT:
                    $this->subject = 'Fotober đã gửi thông tin yêu cầu thanh toán qua Paypal. Bạn vui lòng, kiểm tra tài khoản Paypal';
                    break;
                case Constants::EMAIL_CHAT_MESSAGE:
                    $this->subject = 'Bạn có tin nhắn cần được trả lời';
                    break;
                case Constants::ORDER_UPDATE_REVISION:
                    $this->subject = 'Order ID ' . (isset($this->order->code) ? $this->order->code : '()') . ' has been updated.';
                    break;
                case Constants::EMAIL_ORDER_ASSIGN_SALE:
                    $this->subject = 'Order ID ' . (isset($this->order->code) ? $this->order->code : '()') . ' has been assigned for you.';
                    break;
                case Constants::EMAIL_ORDER_REQUEST_OUTPUT:
                    $this->subject = 'Order ID ' . (isset($this->order->code) ? $this->order->code : '()') . ' has been requested to edit the output.';
                    break;
                case Constants::EMAIL_ORDER_ACCEPT_OUTPUT:
                    $this->subject = 'Order ID ' . (isset($this->order->code) ? $this->order->code : '()') . ' has been accepted output.';
                    break;
                default:
            }

            // Lấy nội dung theo từng sự kiện
            $this->message = getEmailBody($this->scope, [
                'email' => $this->email,
                'fullname' => $this->fullname,
                'order' => $this->order,
                'subject' => $this->subject,
            ]);

            if ($this->subject && $this->email && $this->message) {
                $sendMail->sendMail($this->subject, $this->email, $this->message);
                if($this->scope == Constants::EMAIL_ORDER_CREATE){
                    $sendMail->sendMail($this->subject, 'support@fotober.com', $this->message);
                }
            }

        }
    }
}
