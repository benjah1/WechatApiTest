<?php

define('TOKEN', 'abc');
logger(var_export($_POST, true));
traceHttp();

$wechat = new WechatApiTest();
$wechat->valid();

class WechatApiTest {
    public function valid() {
        $echoStr = $_GET['echostr'];
        if ($this->checkSignature()) {
            $raw = $GLOBALS['HTTP_RAW_POST_DATA'];
            logger("raw: {$raw}");
            if (!empty($raw)) {
                $this->process($raw);
            } else {
                logger("echoStr: {$echoStr};");
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
        $tmpArr = array($timestamp, $nonce, $token);
        // sort($tmpArr);
        $mySignature = sha1(implode($tmpArr));
        logger("source: ".implode($tmpArr, '.'));
        logger("mS:     {$mySignature}");
        logger("S:      {$signature}");
        if ($mySignature === $signature) {
            return true;
        } else {
            return false;
        }
    }

    private function process($raw) {
        libxml_disable_entity_loader(true);
        $postObj = simplexml_load_string($raw, 'SimpleXMLElement', LIBXML_NOCDATA);
        logger(var_export($postObj, true));
	if ($postObj === false) {
		logger(var_export(libxml_get_errors(), true));
    	}
        $fromUsername = (string)$postObj->FromUserName;  //这一句便是得到谁发给公众号，我们之后处理回复信息就需要这个；
        $toUsername = (string)$postObj->ToUserName;  //这一句得到用户发送给谁，这里这个指的就是我们这个微信公众号了；
        $keyword = trim((string)$postObj->Content);  //这一句就是用来判断用户发送过来的信息了，这里有一个函数trim，这个函数是用来去除Content消息左右两边的空格的，这样就不会影响我们对消息的判断；我们有了keyword，就可以针对用户发送过来的消息做相应的响应操作了，即判断keyword的内容，响应响应的操作；
        $time = time();  //这个是时间函数，得到时间信息；
        logger("postObj: {(string)$postObj->Content}");
        logger("keyword: {$keyword}");
        $textTpl = '<xml>
  <ToUserName><![CDATA[%s]]></ToUserName>
  <FromUserName><![CDATA[%s]]></FromUserName>
  <CreateTime>%s</CreateTime>
  <MsgType><![CDATA[%s]]></MsgType>
  <Content><![CDATA[%s]]></Content>
  <FuncFlag>0</FuncFlag>
</xml>';

        if (!empty($keyword) || 1==1) {
            $msgType = "text";
            $contentStr = "Welcome to wechat world! Did you just say '{$keyword}'";
            $resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType, $contentStr);
            logger($resultStr);
            echo $resultStr;
        } else {
            echo 'Input Something...';
        }
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
