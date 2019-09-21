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
    public function rsaSign($data, $private_key) {
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
    public function rsaVerify($data, $alipay_public_key, $sign)  {
        //以下为了初始化私钥，保证在您填写私钥时不管是带格式还是不带格式都可以通过验证。
    	$alipay_public_key=str_replace("-----BEGIN PUBLIC KEY-----","",$alipay_public_key);
    	$alipay_public_key=str_replace("-----END PUBLIC KEY-----","",$alipay_public_key);
    	$alipay_public_key=str_replace("\n","",$alipay_public_key);

        $alipay_public_key='-----BEGIN PUBLIC KEY-----'.PHP_EOL.wordwrap($alipay_public_key, 64, "\n", true) .PHP_EOL.'-----END PUBLIC KEY-----';
        $res=openssl_get_publickey($alipay_public_key);
        if($res)
        {
            $result = (bool)openssl_verify($data, base64_decode($sign), $res);
        } else {
            echo "您的支付宝公钥格式不正确!"."<br/>"."The format of your alipay_public_key is incorrect!";
            exit();
        }
        openssl_free_key($res);
        return $result;
    }

    /**
     * rsa 解密
     * @param $content
     * @return string
     */
    public function rsa_decrypt($content,$priKey) {
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
     * rsa 加密
     * @param $content
     * @return string
     */
    public function rsa_encrypt($content,$pubKey) {

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
     * 16位 随机key
     * @return string
     */
    public function rand_key(){
        mt_srand((double)microtime()*10000);//optional for php 4.2.0 and up.
        $charid = strtoupper(md5(uniqid(rand(), true)));
        $uuid =substr($charid, 0, 16);
        return $uuid;//strtolower()
    }

    /**
     * 生成公私钥
     * @return [type] [description]
     */
    public function new_rsa_key() {
        //创建私钥和公钥
        $res = openssl_pkey_new(["private_key_bits" => config('crypt.key_len')]);

        //将私钥从$res提取到$privKey
        openssl_pkey_export($res, $privKey);

        //将公钥从$res提取到$pubKey
        $pubKey = openssl_pkey_get_details($res);
        $pubKey = $pubKey["key"];
        return array(
            'private_key' => $privKey,
            'public_key'  => $pubKey
        );
    }
}