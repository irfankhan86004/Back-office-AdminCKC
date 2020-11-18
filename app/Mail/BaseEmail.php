<?php

namespace App\Mail;

use App\Models\Setting;
use App\Traits\MailGunable;
use App\Models\Language;
use App\Models\LogEmail;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\View;

class BaseEmail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels, MailGunable;

	public $data;
    public $view;
    public $subject;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($data, $subject, $view)
    {
        $this->data = $data;
        $this->view = $view;
        $this->subject = $subject;
    }

	/**
	 * Build, send and log the message.
	 */
    public function build()
    {
	    $settings = Setting::find(1);
	
	    if ($settings->mailgun_use) {
	        $this->sendByMailGunAPI();
	    } else {
		    $options = [
			    'from_name' => empty($settings->from_name) ? config('mail.from.name') : $settings->from_name,
			    'from_email' => empty($settings->from_email) ? config('mail.from.address') : $settings->from_email,
		    ];
		
		    $this->from(['address' => $options['from_email'], 'name' => $options['from_name']])
			    ->subject($this->subject)
			    ->view($this->view)
			    ->with(['data' => $this->data]);

		    foreach($this->to as $receiver){

			    $language = Language::whereShort(app()->getLocale())->first();
		    	$html = View::make($this->view, ['data' => $this->data])->render();

			    LogEmail::create([
				    'language_id' =>  $language ? $language->id : 1,
				    'type' => null,
				    'from' => $options['from_email'],
				    'to' => $receiver['address'],
				    'subject' => $this->subject,
				    'message' => $html,
				    'mailgun_id' => null,
				    'mailgun_status' => null
			    ]);
		    }
	    }
    }
}
