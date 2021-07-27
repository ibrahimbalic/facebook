<?php


// https://github.com/phpseclib/phpseclib

use phpseclib\Crypt\RSA;
require 'vendor/autoload.php';

function gs($length = 10) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}

function gn($length = 10) {
    $characters = '0123456789';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}


function encrypt($password, $publicKey, $keyId)
{
		$time = time();
		$session_key = random_bytes(32);
		$iv = random_bytes(12);
		$tag= '';
		$rsa = new RSA();
		
		$rsa->loadKey($publicKey); 
		$rsa->setSignatureMode(RSA::SIGNATURE_PKCS1);
		$enc_session_key = $rsa->encrypt($session_key);
        $encrypted = openssl_encrypt( $password,'aes-256-gcm',$session_key,OPENSSL_RAW_DATA,$iv,$tag,intVal($time));
		
		return "#PWD_FB4A:4:".$time.":".base64_encode(("\x01" . pack('n', intval($keyId)) .$iv. pack('n',strlen($enc_session_key) ) . $enc_session_key . $tag . $encrypted));
}


function GUID() {
	if (function_exists('com_create_guid') === true) {
		return trim(com_create_guid(), '{}');
	}
	return sprintf('%04X%04X-%04X-%04X-%04X-%04X%04X%04X', mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(16384, 20479), mt_rand(32768, 49151), mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(0, 65535));
}

$e = "100068716380087";
$p = "****";
$publicKey = "-----BEGIN PUBLIC KEY-----
MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEArbd8VjAw2abyJ4eFWRtK
T7sI0UGmHRLtAtsp0tCI3yXxA5V4xEhLlc2SCXWpHxmjFuQ5vh37JyVQZLDxB5Vc
LPa1S8Lqsk0WfMnSEi5r5dpxThIF77JpJ9J9/L7oh1IGCFpsYQyQCXxOuXQd8XX4
YJV5aShUDtwLOMBgaqkGj5QHpNBGHMqewjwXUHMrh0FFFskEzQUkhzNEme7ogdvf
yvRwXvvJFZvp00XXnkTUMhmKa1UmhL0haqlXrGd1WfOs2WnAp6iJtIkKSCZSpPib
jIdS4VUhrCzUkjr+mvbRPO2Isz8JT80BL2pD2ob6Z5W9s/qcfquNqxNlvCP6u5qx
vwIDAQAB
-----END PUBLIC KEY-----";

$keyId     = 230; 

$enc_pass = urlencode(encrypt($p, $publicKey,$keyId));


$data = array("adid"=>gs(16),
"format"=>"json",
"device_id"=>GUID(),
"email"=>$e,
"password"=>$p,
"generate_analytics_claim"=>"1",
"community_id"=>"",
"cpl"=>"true",
"try_num"=>"1",
"family_device_id"=>GUID(),
"secure_family_device_id"=>GUID(),
"sim_serials"=>"%5B%2289014103211118510720%22%5D",
"credentials_type"=>"password",
"fb4a_shared_phone_cpl_experiment"=>"fb4a_shared_phone_nonce_cpl_at_risk_v3",
"fb4a_shared_phone_cpl_group"=>"enable_v3_at_risk",
"enroll_misauth"=>"false",
"generate_session_cookies"=>"1",
"error_detail_type"=>"button_with_disabled",
"source"=>"login",
"generate_machine_id"=>"1",
"jazoest"=>"22517",
"meta_inf_fbmeta"=>"",
"encrypted_msisdn"=>"",
"currently_logged_in_userid"=>"0",
"locale"=>"en_US",
"client_country_code"=>"US",
"fb_api_req_friendly_name"=>"authenticate",
"fb_api_caller_class"=>"Fb4aAuthHandler",
"api_key"=>"882a8490361da98702bf97a021ddc14d",
"access_token"=>"350685531728|62f8ce9f74b12f84c123cc23437a4a32");


ksort($data);


$sig = "";
foreach($data as $key => $value) { 
$sig .= $key."=".$value;
}
$sig .= '62f8ce9f74b12f84c123cc23437a4a32';
$data['sig'] = md5($sig);


$ch = curl_init("https://b-api.facebook.com/method/auth.login");
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS,  http_build_query($data));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_HTTPHEADER, array(
			"X-FB-Friendly-Name: authenticate",
			"X-FB-Connection-Quality: GOOD",
			"X-FB-SIM-HNI: 310260",
			"X-FB-Net-HNI: 310260",
			"X-Fb-Session-Id: nid=qW/DOVwni83/;pid=Main;tid=12;nc=0;fc=0;bc=0;cid=",
			"X-FB-Connection-Type: unknown",
			"X-Fb-Friendly-Name: authenticate",
			"User-Agent: Dalvik/2.1.0 (Linux; U; Android 7.1.1; SM-G930F Build/R16NW) [FBAN/FB4A;FBAV/328.1.0.28.119;FBPN/com.facebook.katana;FBLC/en_US;FBBV/306506931;FBCR/Bouygues Telecom;FBMF/samsung;FBBD/samsung;FBDV/SM-G930F;FBSV/7.1.1;FBCA/x86:armeabi;FBDM/{density=3.0,width=1080,height=1794};FB_FW/1;FBRV/0;]",
			"X-Fb-Connection-Token: ",
			"Content-Type: application/x-www-form-urlencoded",
			"X-Tigon-Is-Retry: False",
			"X-FB-HTTP-Engine: Liger"
		));

$result = json_decode(curl_exec($ch));

var_dump($result);

?>
