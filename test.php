<?php

define('TOKEN', 'abc');
logger(var_export($_POST, true));
logger(var_export($GLOBALS['HTTP_RAW_POST_DATA'], true));
traceHttp();


$wechat = new WechatApiTest();
$wechat->valid();


class WechatApiTest {
    public function valid() {
        $echoStr = $_GET['echostr'];
	logger("echoStr: {$echoStr};");
        if ($this->checkSignature()) {
            $raw = $GLOBALS['HTTP_RAW_POST_DATA'];
            if (!empty($raw)) {
                $this->process($raw);
            } else {
                echo $echoStr;
            }
            exit;
        }
    }

    private function checkSignature() {
        $signature = $_GET['signature'];
        $timestamp = $_GET['timestamp'];
        $nonce = $_GET['nonce'];
        $token = TOKEN;
        $tmpArr = array($token, $timestamp, $nonce);
        sort($tmpArr);
	$mySignature = sha1(implode($tmpArr));
	logger("S: {$signature}");
	logger("mS: {$mySignature}");
        if ($mySignature === $signature) {
            return true;
        } else {
            return false;
        }
    }

    private function process($raw) {

    }
}

function traceHttp() {
    logger("REMOTE_ADDR:{$_SERVER['REMOTE_ADDR']}; QUERY_STRING:{$_SERVER['QUERY_STRING']}");
}

function logger($content) {
    $stdout = fopen('php://stdout', 'w');
    fwrite($stdout, $content);
    fwrite($stdout, "\r\n");
    fclose($stdout);
}
