<?php

namespace App\Events;

use App\Models\Notification;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class NotificationEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $scope;
    public $message_vi;
    public $message_en;
    public $order;
    public $order_id;
    public $account_type;
    public $sender_id;
    public $receiver_id;
    public $total_no_seen_sale;
    public $total_no_seen_cus;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($params)
    {
        $this->scope = isset($params['scope']) ? $params['scope'] : 'ORDER';
        $this->message_vi = isset($params['message_vi']) ? $params['message_vi'] : null;
        $this->message_en = isset($params['message_en']) ? $params['message_en'] : null;
        $this->order = isset($params['order']) ? $params['order'] : null;
        $this->order_id = isset($params['order_id']) ? $params['order_id'] : null;
        $this->account_type = isset($params['account_type']) ? $params['account_type'] : null;
        $this->sender_id = isset($params['sender_id']) ? $params['sender_id'] : null;
        $this->receiver_id = isset($params['receiver_id']) ? $params['receiver_id'] : null;
        $this->total_no_seen_sale = isset($params['total_no_seen_sale']) ? $params['total_no_seen_sale'] : 0;
        $this->total_no_seen_cus = isset($params['total_no_seen_cus']) ? $params['total_no_seen_cus'] : 0;
        // Lưu log notification
        Notification::create([
            'scope' => $this->scope,
            'order_id' => $this->order_id,
            'order_data' => json_encode($this->order),
            'title_vi' => $this->message_vi,
            'title_en' => $this->message_en,
            'content' => null,
            'sender_id' => $this->sender_id,
            'receiver_id' => $this->receiver_id,
        ]);

    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        //return new PrivateChannel('private-notification-user-' . $this->receiver_id);
        return new Channel('notification');
    }

}
