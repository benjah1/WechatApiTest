<?php 

namespace App\Wx\Contracts;

interface Crypt {

	public function encryptMsg($replyMsg, $timeStamp, $nonce, &$encryptMsg);

	public function decryptMsg($msgSignature, $timestamp = null, $nonce, $postData, &$msg);
}
