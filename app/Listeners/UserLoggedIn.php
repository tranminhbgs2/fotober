<?php

namespace App\Listeners;

use App\Helpers\Constants;
use App\Models\Log\LogAuth;
use Carbon\Carbon;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class UserLoggedIn
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle($event)
    {
        LogAuth::create([
            'session_id' => null,
            'user_id' => null,
            'action_type' => Constants::ACTION_TYPE_LOGIN,
            'logged_in_at' => Carbon::now(),
            'account_input' => $event->user->username,
            'logged_out_at' => null,
            'user_agent' => null,
            'duration' => 0,
            'ip_address' => null,
            'result' => 'SUCCESS'
        ]);
    }
}
