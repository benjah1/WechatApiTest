<?php 

namespace App\Wx\Providers;

use Illuminate\Support\ServiceProvider;

class WxCryptProvider extends ServiceProvider {
	
	public function register() {
		$this->app->singleton('App\Wx\Contracts\Crypt', function() {
			include_once "WxCrypt/wxBizMsgCrypt.php";

			$token = env('WXTOKEN');
			$encodingAesKey = env('WXAESKEY');
			$appId = env('WXAPPID');
			return new \WXBizMsgCrypt($token, $encodingAesKey, $appId);
		});
	}
}
