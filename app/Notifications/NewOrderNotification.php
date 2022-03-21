<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewOrderNotification extends Notification
{
    use Queueable;
    protected $product;
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($product)
    {
        $this->product = $product; 
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['database'];
    }

    
    public function toArray($notifiable)
    {
        return [
            'order'=>$this->product,
            'title'=>'New Order',
            'message'=> 'New order has been created with order id ,'.$this->product->order_identifier,
        ];
    }
}
