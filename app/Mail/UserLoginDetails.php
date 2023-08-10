<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class UserLoginDetails extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * The order instance.
     *
     * @var \App\Models\User
     */
    public $user;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($user)
    {
        $this->user = $user;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        if($this->user->new){
            return $this->view('email.welcome')
            ->subject('Welcome to '.env('APP_NAME'))
            ->with('user', $this->user);
        }
        if($this->user->reset){
            return $this->view('email.resetpass')
            ->subject(env('APP_NAME').' New Password')
            ->with('user', $this->user);
        }
    }
}
