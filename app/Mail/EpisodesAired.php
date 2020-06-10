<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class EpisodesAired extends Mailable
{
    use Queueable, SerializesModels;

    protected $user, $episodes, $interval, $interval_date, $interval_text;

	/**
	 * Create a new message instance.
	 *
	 * @param $user
	 * @param $episodes
	 * @param $interval
	 * @param $interval_date
	 * @param $interval_text
	 */
    public function __construct($user, $episodes, $interval, $interval_date, $interval_text)
    {
        $this->user = $user;
        $this->episodes = $episodes;
        $this->interval = $interval;
        $this->interval_date = $interval_date;
        $this->interval_text = $interval_text;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from(config('mail.from.address'), config('app.name'))
					->subject(sprintf('Reminder about your TV shows aired %s (%s)', $this->interval_text, $this->interval_date))
					->markdown('emails.reminder')
					->with(['user' => $this->user, 'episodes' => $this->episodes, 'interval' => $this->interval, 'interval_text' => $this->interval_text]);
    }
}
