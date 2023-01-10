<?php

namespace App\Console\Commands\Dev;

use App\Services\Email\SendMail;
use Illuminate\Console\Command;

class TestSendEmailCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:send-email';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test gửi email';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle(SendMail $sendMail)
    {
       $res = $sendMail->sendMail('[Fotober] Account Activation', 'trangiap010161@gmail.com', '<td>
       <table cellpadding="0" cellspacing="0" border="0" bgcolor="#FFFFFF" align="center" style="background:#ffffff;width:100%;max-width:600px">
           <tbody>
           <tr>
                <td bgcolor="#074B80" style="background-color: #1dabe3; font-size:20px;padding:20px 40px;color:#ffffff;border-bottom:5px solid #2b57a4">Welcome to Fotober</td>
            </tr>
            <tr>
                <td style="padding:22px 40px;border:1px solid #dddddd;border-top:none">
                <p>Dear <strong>ijktvm98@gmail.com</strong>, welcome to Fotober!</p>
                <p>Your Fotober login is: ijktvm98@gmail.com</p>
                <p>Your password as in sign-up.</p>
                <p>Please click this link to confirm your email at Fotober: </p>
                <br>
                <a href="https://bit.ly/3xTY7em">https://bit.ly/3xTY7em</a>
               
                <p>To submit your photos for retouching, please login at Fotober and click "Create Order"</p>
                <br>
                <p>All the best,</p>
                <p>Fotober</p>
                </td>
            </tr>
           </tbody>
       </table>
       <p style="text-align:center;color:#aaabbb;font-size:9pt">2021 © By FOTOBER</p>
       <br>
   </td>');
       return $res;
    } 
}
