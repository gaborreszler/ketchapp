<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class DatabaseBackupDumped extends Mailable
{
    use Queueable, SerializesModels;

    protected $filename;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($filename)
    {
        $this->filename = $filename;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
    	$app_name = config('app.name');

        return $this->from(config('mail.from.address'), $app_name)
					->subject($app_name . ' backup')
					->view('emails.backup')
					->with(['filename' => $this->filename])
					->attachFromStorage($this->filename);
    }
}
