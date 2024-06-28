<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;

use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class SendMessageToClientEvent implements ShouldBroadcast {
  use Dispatchable;
  use InteractsWithSockets;
  use SerializesModels;
  
  public string $message;
  
  public function __construct() {
    $this->message = 'Hello friend';
  }
  
  public function broadcastAs() {
    return 'SendMessageToClientEvent';
  }
  
  public function broadcastOn() { // use PrivateChannel to create channels only for auth users
    return new Channel('channel-name'); // enter channel name
  }
}