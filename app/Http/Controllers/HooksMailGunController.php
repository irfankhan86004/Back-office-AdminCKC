<?php
	
namespace App\Http\Controllers;

use App\Models\LogEmail;
use Illuminate\Http\Request;

class HooksMailGunController
{
	public function index(Request $request){
		$inputs = $request->all();
		
		if(isset($inputs['event-data']['message']['headers']['message-id'])){
			$messageID = $inputs['event-data']['message']['headers']['message-id'];
			
			$log = LogEmail::where('mailgun_id', 'LIKE', '%'.$messageID.'%')->first();
			
			if($log && isset($inputs['event-data']['event'])){
				$log->mailgun_status = $inputs['event-data']['event'];
				$log->save();
				
				$message = 'Status catched';
			} else {
				$message = 'Event or Log not found';
			}
		} else {
			$message = 'Message not found';
		}
		
		return json_encode(['message' => $message]);
	}
}