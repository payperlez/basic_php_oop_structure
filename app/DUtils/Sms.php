<?php

	/**
	 * Sms class using MSM Pusher. Create account at http://msmpusher.com/
	 *Enter your _pubkey, _prikey and _sender_id
	 *
	 */
	class SmsAlert{

		public function message($_phone, $_message){
			$this->_pubkey = '';
			$this->_prikey = '';
			$this->_sender_id = ""; 	
			// this encodes the message                         
			$message= rawurlencode($_message);                   
			$numbers= rawurlencode($_phone); 

			$url= "http://api.msmpusher.com/api?api_private_key=".$this->_prikey."&api_public_key=".$this->_pubkey."&sender=".$this->_sender_id."&numbers=".$numbers."&message=".$message;

			$ch = curl_init();
			curl_setopt($ch, CURLOPT_AUTOREFERER, TRUE);
			curl_setopt($ch, CURLOPT_HEADER, 0);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);  
			curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 3);     
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
			$html_curl = curl_exec($ch);
			curl_close($ch);
			$data = json_decode($html_curl, TRUE);

			/*return result*/

			switch($data){                                
	            case "1000":
	            return $this->feedback =(['success', 'Message sent successfully']);
	           default:
	           return $this->feedback =(['error', 'Message was unable to send']);

		}
	}
}
?>