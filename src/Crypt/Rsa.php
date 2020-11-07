<?php

namespace Jybtx\RsaCryptAes\Crypt;

class Rsa
{
	
	/**
     * RSA签名
     * @param $data 待签名数据
     * @param $private_key 商户私钥字符串
     * return 签名结果
     */
    public function getRsaSign($data, $private_key) {
        //以下为了初始化私钥，保证在您填写私钥时不管是带格式还是不带格式都可以通过验证。
        $private_key = str_replace("-----BEGIN RSA PRIVATE KEY-----","",$private_key);
    	$private_key = str_replace("-----END RSA PRIVATE KEY-----","",$private_key);
    	$private_key = str_replace("\n","",$private_key);
    	$private_key="-----BEGIN RSA PRIVATE KEY-----".PHP_EOL .wordwrap($private_key, 64, "\n", true). PHP_EOL."-----END RSA PRIVATE KEY-----";
        $res = openssl_get_privatekey($private_key);
        if ($res) {
            openssl_sign($data, $sign,$res);
        } else {
            echo "您的私钥格式不正确!"."<br/>"."The format of your private_key is incorrect!";
            exit();
        }
        openssl_free_key($res);
    	//base64编码
        $sign = base64_encode($sign);
        return $sign;
    }

    /**
     * RSA验签
     * @param $data 待签名数据
     * @param $alipay_public_key 支付宝的公钥字符串
     * @param $sign 要校对的的签名结果
     * return 验证结果
     */
    public function getRsaVerify($data, $alipay_public_key, $sign)  {
        //以下为了初始化私钥，保证在您填写私钥时不管是带格式还是不带格式都可以通过验证。
    	$alipay_public_key = str_replace("-----BEGIN PUBLIC KEY-----","",$alipay_public_key);
    	$alipay_public_key = str_replace("-----END PUBLIC KEY-----","",$alipay_public_key);
    	$alipay_public_key = str_replace("\n","",$alipay_public_key);

        $alipay_public_key = '-----BEGIN PUBLIC KEY-----'.PHP_EOL.wordwrap($alipay_public_key, 64, "\n", true) .PHP_EOL.'-----END PUBLIC KEY-----';
        $res=openssl_get_publickey($alipay_public_key);
        if($res)
        {
            $result = (bool)openssl_verify($data, base64_decode($sign), $res);
        } else {
            echo "您的公钥格式不正确!"."<br/>"."The format of your public_key is incorrect!";
            exit();
        }
        openssl_free_key($res);
        return $result;
    }

    /**
     * 获取rsa解密字符串
     * @author jybtx
     * @date   2019-09-22
     * @param  [type]     $content [description]
     * @param  [type]     $priKey  [description]
     * @return [type]              [description]
     */
    public function getRSADecryptionString($content,$priKey) {
        //转换为openssl密钥，必须是没有经过pkcs8转换的私钥
        $res = openssl_pkey_get_private($priKey);

        //声明明文字符串变量
        $result  = '';

        //私钥解密
        openssl_private_decrypt(base64_decode($content), $result, $priKey);

        //释放资源
        openssl_free_key($res);

        //返回明文
        return $result;
    }

    /**
     * 获取rsa加密字符串
     * @author jybtx
     * @date   2019-09-22
     * @param  [type]     $content [description]
     * @param  [type]     $pubKey  [description]
     * @return [type]              [description]
     */
    public function getRSAEncryptedString($content,$pubKey) {

        //转换为openssl公钥，必须是没有经过pkcs8转换的公钥
        $res = openssl_pkey_get_public($pubKey);

        //声明明文字符串变量
        $result  = '';

        //公钥加密
        openssl_public_encrypt($content, $result, $pubKey);

        //编码
        $result = base64_encode($result);

        //释放资源
        openssl_free_key($res);

        //返回密文
        return $result;
    }

    /**
     * 获取随机字符串
     * @author jybtx
     * @date   2019-09-22
     * @return [type]     [description]
     */
    public function getRandomAesKey(){
        mt_srand((double)microtime()*10000);//optional for php 4.2.0 and up.
        $charid = strtoupper( base64_encode( bin2hex( md5( uniqid(rand(), true) ) ) ) );
        return substr($charid, 0, config('crypt.random_aes_key') );        
    }

    /**
     * 生成公私钥
     * @author jybtx
     * @date   2019-09-22
     * @return [type]     [description]
     */
    public function getPublicAndPrivateKeys() {
        //创建私钥和公钥
        $res = openssl_pkey_new(["private_key_bits" => config('crypt.private_key_bits')]);

        //将私钥从$res提取到$privKey
        openssl_pkey_export($res, $privKey);

        //将公钥从$res提取到$pubKey
        $pubKey = openssl_pkey_get_details($res);
        return array(
            'private_key' => $privKey,
            'public_key'  => $pubKey["key"]
        );
    }
}