<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SendMail extends Mailable
{
    // use Queueable, SerializesModels;
    public $title;
    public $description;
    public $content;
    public $footer;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($title, $description, $content, $footer)
    {
        //
        $this->title = $title;
        $this->description = $description;
        $this->content= $content;
        $this->footer = $footer;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject($this->title)
        ->view('mail_template');
    }
}
