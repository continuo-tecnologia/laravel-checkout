<?php

namespace MatheusFS\Laravel\Checkout\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class CartUpdated implements ShouldBroadcast{

    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $user_id;
    public $data;

    public function __construct($user_id, $cart){

        $this->user_id = $user_id;
        $this->data = $cart;
    }

    public function broadcastOn(){

        return new PrivateChannel("user:$this->user_id");
    }
}
