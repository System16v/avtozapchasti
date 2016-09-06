<?php

class Meil{
	static $subject = 'Вы зарегистрировались на нашем сайте';   	// тема письма
	static $from = 'lesson25@list.ru';   	   // от кого письмо
	static $to = 'System88_@mail.ru';    	  // кому
	static $text = 'Шаблонное письмо';   // текст письма
	static $headers = '';				// заголовки

	
	static function send(){
		// Настройка кодировки письма и остальных заголовков
		self::$subject = '=?utf-8?b?'. base64_encode(self::$subject).'?=';
		self::$headers = "Content-type: text/html; charset=\"utf-8\"\r\n";
		
		self::$headers .= "From: ".self::$from."\r\n";
		self::$headers .= "MIME-Version: 1.0\r\n";
		self::$headers .= "Date: ".date('D, d M Y h:i:s O') ."\r\n";
		self::$headers .= "Precedence: bulk\r\n"; // Указывает что отправляется не одно письмо (рассылка)
												// например нужно указывать при ответе на регистрацию
		return mail(self::$to,self::$subject,self::$text,self::$headers);
	}
	
	static function testSend(){
		if(mail(self::$to,'English words','English words'))
			echo 'Письмо отправилось';
		else
			echo 'Письмо не отправилось';
		exit();
	}
}


?>