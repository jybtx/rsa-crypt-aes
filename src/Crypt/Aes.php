<?php

namespace Jybtx\RsaCryptAes\Crypt;

class Aes
{
    private $hex_iv = '';
    private $method = '';

    function __construct ()
    {
        $aes          = config('crypt.aes_encrypt_key');
        $this->method = $aes['method'];
        $this->hex_iv = $this->getDecodeBase64( $aes['hex_iv'] );
    }
	
    /**
     * encrypt_openssl 加密
     * @author jybtx
     * @date   2019-09-23
     * @param  [type]     $str        [description]
     * @param  [type]     $encryptKey [description]
     * @return [type]                 [description]
     */
    function getEncryptOpenssl($str,$encryptKey)
    {
        return base64_encode(openssl_encrypt($str, $this->method,$encryptKey,true,$this->hex_iv));
    }

    /**
     * decrypt_openssl 解密
     * @author jybtx
     * @date   2019-09-23
     * @param  [type]     $str        [description]
     * @param  [type]     $encryptKey [description]
     * @return [type]                 [description]
     */
    function getDecryptOpenssl($str,$encryptKey)
    {
        return openssl_decrypt(base64_decode($str), $this->method, $encryptKey, true, $this->hex_iv);
    }
    /**
     * [getDecodeBase64 description]
     * @author jybtx
     * @date   2019-10-10
     * @param  [type]     $key [description]
     * @return [type]          [description]
     */
    function getDecodeBase64($key)
    {
        return base64_decode(str_replace('base64:','',$key),true);
    }
}