<?php

namespace app\services\libraries;

class MessageType {

	const __default = self::Notification;
	
	const Error = 0;
	const Success = 1;
	const Notification = 2;
	
}