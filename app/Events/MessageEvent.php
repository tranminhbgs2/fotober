<?php

namespace App\Events;

use App\Helpers\Constants;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class MessageEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $message_sale;
    public $message_cus;
    public $type;
    public $order_id;
    public $type_mess;
    public $file_name;
    public $created_at;
    public $sender_name;
    public $sender_avatar;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($params)
    {
        $this->message_sale = ($params['mess_sale']) ? $params['mess_sale'] : '';
        $this->message_cus = ($params['mess_cus']) ? $params['mess_cus'] : '';
        $this->type = ($params['type']) ? $params['type'] : '';
        $this->order_id = ($params['order_id']) ? $params['order_id'] : '';
        $this->type_mess = ($params['type_mess']) ? $params['type_mess'] : Constants::MESSAGE_TYPE_TEXT;
        $this->file_name = ($params['file_name']) ? $params['file_name'] : '';
        $this->created_at = ($params['created_at']) ? $params['created_at'] : '';
        $this->sender_name = ($params['sender_name']) ? $params['sender_name'] : '';
        $this->sender_avatar = ($params['sender_avatar']) ? $params['sender_avatar'] : '';
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new Channel('chat');
    }

    public function broadcastAs()
    {
        return 'message';
    }
}
