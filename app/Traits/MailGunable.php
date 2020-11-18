<?php
	
namespace App\Traits;

use App\Models\Language;
use App\Models\LogEmail;
use App\Models\Setting;
use Illuminate\Support\Facades\View;
use Mailgun\Mailgun;

trait MailGunable
{
	/**
	 * Don't forget to set env('MAIL_DRIVER') to mail
	 */
	public function sendByMailGunAPI()
	{
		$settings = Setting::find(1);
		
		$options = [
			'mailgun_domain' => empty($settings->mailgun_domain) ? config('services.mailgun.domain') : $settings->mailgun_domain,
			'mailgun_endpoint' => empty($settings->mailgun_endpoint) ? config('services.mailgun.endpoint') : $settings->mailgun_endpoint,
			'mailgun_secret' => empty($settings->mailgun_secret) ? config('services.mailgun.secret') : $settings->mailgun_secret,
			'from_name' => empty($settings->from_name) ? config('mail.from.name') : $settings->from_name,
			'from_email' => empty($settings->from_email) ? config('mail.from.address') : $settings->from_email,
		];
		
		$mg = Mailgun::create($options['mailgun_secret'], sprintf('https://%s/v3/%s/messages', $options['mailgun_endpoint'], $options['mailgun_domain']));
		
		foreach($this->to as $receiver){
			$params = [
				'from' => sprintf("%s <%s>", $options['from_name'], $options['from_email']),
				'to' => $receiver['address'],
				'subject' => $this->subject
			];
			
			$params['html'] = View::make($this->view, ['data' => $this->data])->render();
			
			// Send email
			$return = $mg->messages()->send($options['mailgun_domain'], $params);
			
			$language = Language::whereShort(app()->getLocale())->first();
			
			LogEmail::create([
				'language_id' =>  $language ? $language->id : 1,
				'type' => null,
				'from' => $options['from_email'],
				'to' => $receiver['address'],
				'subject' => $this->subject,
				'message' => $params['html'],
				'mailgun_id' => $return->getId(), // OR trim($return->getId(), '\<\>')
				'mailgun_status' => $return->getMessage()
			]);
		}
	}
}