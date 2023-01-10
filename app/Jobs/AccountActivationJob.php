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

class AccountActivationJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $scope;
    protected $customer_id;
    protected $url;
    protected $subject;
    protected $email;
    protected $message;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($scope, $data)
    {
        $this->scope = $scope;
        $this->customer_id = isset($data['customer_id']) ? $data['customer_id'] : null;
        $this->url = isset($data['url']) ? $data['url'] : null;
        $this->subject = '[Fotober] Account Activation';
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
                $this->email = $customer->email;
                $this->fullname = $customer->fullname;
            }
        }

        // Lấy nội dung theo từng sự kiện
        $this->message = getEmailBody($this->scope, [
            'email' => $this->email,
            'fullname' => $this->fullname,
            'subject' => $this->subject,
            'url' => $this->url,
        ]);

        if ($this->subject && $this->email && $this->message) {
            $sendMail->sendMail($this->subject, $this->email, $this->message);
            //$sendMail->sendMail($this->subject, 'support@fotober.com', $this->message);
        }
    }
}
