<?php

namespace Jybtx\RsaCryptAes;

use Carbon\Carbon;
use Jybtx\RsaCryptAes\Crypt\Aes;
use Jybtx\RsaCryptAes\Crypt\Rsa;
use Illuminate\Support\Facades\Cache;

class EncryptionAndDecryption
{
    /**
     * 生成一对公私秘钥
     * @author jybtx
     * @date   2019-09-22
     * @return [type]     [description]
     */
	public function getThePublicKey()
    {
        // 1、生成一对公私秘钥
        $rsa       = new Rsa();
        $key       = $rsa->getPublicAndPrivateKeys();
        // 2、md5公钥作为key用redis存储私钥
        $pubKey    = $key['public_key'];
        $pubKey    = str_replace("-----BEGIN PUBLIC KEY-----","",$pubKey);
        $pubKey    = str_replace("-----END PUBLIC KEY-----","",$pubKey);
        $pubKey    = str_replace("\n","",$pubKey);
        $pubKeyMd5 = md5($pubKey);
        // 3、公钥md5加密并redis存储
        Cache::put('private_key_'.$pubKeyMd5, base64_encode($key['private_key']), Carbon::now()->addMinutes(1));
        return $pubKey;
    }
    /**
     * 解密随机字符串
     * @author jybtx
     * @date   2019-09-18
     * @param  [type]     $obj          [加密后的字符串]
     * @param  [type]     $md5PublicKey [md5后的字符串]
     * @return [type]                   [解密后的字符串]
     */
    public function decryptRandomString($obj,$md5PublicKey)
    {
        $rsa = new Rsa();
        if ( Cache::has( 'private_key_'. $md5PublicKey ) ) {
            $privkey = base64_decode( Cache::get('private_key_'. $md5PublicKey) );
        } else {
            return FALSE;
        }
        $decrypt = $rsa->getRSADecryptionString($obj,$privkey);
        Cache::forget('private_key_'.$md5PublicKey);
        return $decrypt;
    }
    /**
     * aes解密加密数据
     * @author jybtx
     * @date   2019-09-18
     * @param  [type]     $data   [加密后的数据]
     * @param  [type]     $random [解密后的随机字符串]
     * @return [type]             [解密后的数据]
     */
    public function decryptString($data,$random)
    {
        $aes = new Aes();
        return $aes->getDecryptOpenssl($data,$random);
    }
    /**
     * 解密加密后的数据
     * @author jybtx
     * @date   2019-09-18
     * @param  [type]     $random    [随机字符串]
     * @param  [type]     $pubKeyMd5 [md5后的公钥]
     * @param  [type]     $data      [加密后的数据]
     * @return [type]                [解密后的数据]
     */
    public function decryptEncryptedData($random,$pubKeyMd5,$data)
    {
        // 1、先解码随机字符串
        $decryptRandom = $this->decryptRandomString($random,$pubKeyMd5);
        if( $decryptRandom == FALSE ) return FALSE;
        // 2、解码加密字符串
        $decryptString = $this->decryptString($data,$decryptRandom);
        return json_decode($decryptString,true);
    }
}