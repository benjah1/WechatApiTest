<?php

namespace App\Wx\Http\Controllers;

use App\Http\Controllers\Controller;
use Monolog\Logger;
use Illuminate\Log\Writer;
use Illuminate\Http\Request;

use App\Wx\Contracts\Crypt;

class ReceiveController extends Controller {
	
	public function sysmessage(Request $request, Crypt $crypt) {
		$logger = new Writer(new Logger("output"));		
		$logger->useFiles('php://stdout');
		$raw = $GLOBALS['HTTP_RAW_POST_DATA'];

		$logger->info('raw post:');
		$logger->info(var_export($raw, true));

		$data = $this->process($raw);
		$logger->info(var_export($data, true));
		// $errCode = $crypt->decryptMsg($raw->ComponentVerifyTicket, $raw->CreateTime, $nonce, $from_xml, $msg);
		echo 'success';
		return;
/*
		if ($errCode == 0) {
			$logger->info('after decrypt:');
			$logger->info(var_export($msg, true));
		} else {
			$logger->info('Err: '.$errCode);
		}
	*/
		echo 'success';
	}

	private function process($raw) {
		libxml_disable_entity_loader(true);
		$postObj = simplexml_load_string($raw, 'SimpleXMLElement', LIBXML_NOCDATA);
 
		return $postObj;
	}

	public function test(Crypt $crypt) {
	
		$timeStamp = "1409304348";
		$nonce = "xxxxxx";
		$text =
		"<xml><ToUserName><![CDATA[oia2Tj我是中文jewbmiOUlr6X-1crbLOvLw]]></ToUserName><FromUserName><![CDATA[gh_7f083739789a]]></FromUserName><CreateTime>1407743423</CreateTime><MsgType><![CDATA[video]]></MsgType><Video><MediaId><![CDATA[eYJ1MbwPRJtOvIEabaxHs7TX2D-HV71s79GUxqdUkjm6Gs2Ed1KF3ulAOA9H1xG0]]></MediaId><Title><![CDATA[testCallBackReplyVideo]]></Title><Description><![CDATA[testCallBackReplyVideo]]></Description></Video></xml>";

		$encryptMsg = '';
		$errCode = $crypt->encryptMsg($text, $timeStamp, $nonce, $encryptMsg);
		if ($errCode == 0) {
			print("加密后: " . $encryptMsg . "\n");
		} else {
			print($errCode . "\n");
		}

		$xml_tree = new \DOMDocument();
		$xml_tree->loadXML($encryptMsg);
		$array_e = $xml_tree->getElementsByTagName('Encrypt');
		$array_s = $xml_tree->getElementsByTagName('MsgSignature');
		$encrypt = $array_e->item(0)->nodeValue;
		$msg_sign = $array_s->item(0)->nodeValue;

		$format = "<xml><ToUserName><![CDATA[toUser]]></ToUserName><Encrypt><![CDATA[%s]]></Encrypt></xml>";
		$from_xml = sprintf($format, $encrypt);

		// 第三方收到公众号平台发送的消息
		$msg = '';
		$errCode = $crypt->decryptMsg($msg_sign, $timeStamp, $nonce, $from_xml, $msg);
		if ($errCode == 0) {
			print("解密后: " . $msg . "\n");
		} else {
			print($errCode . "\n");
		}
		
	}
}
