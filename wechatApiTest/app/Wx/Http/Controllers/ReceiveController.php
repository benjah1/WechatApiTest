<?php

namespace App\Wx\Http\Controllers;

use App\Http\Controllers\Controller;
use Monolog\Logger;
use Illuminate\Log\Writer;
use Illuminate\Http\Request;

class ReceiveController extends Controller {
	
	public function sysmessage(Request $request) {
		$logger = new Writer(new Logger("output"));		
		$logger->useFiles('php://stdout');
		$raw = $GLOBALS['HTTP_RAW_POST_DATA'];
		$logger->info(var_export($raw, true));
		echo 'success';
	}

}
