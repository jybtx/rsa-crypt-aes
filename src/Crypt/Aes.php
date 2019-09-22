<?php

namespace Jybtx\RsaCryptAes\Crypt;

class Aes
{
	
	private $hex_iv = config('crypt.key_random'); # converted JAVA byte code in to HEX and placed it here
    //public $key = '12345678987654321'; #Same as in JAVA

    //encrypt_openssl新版加密
    function getEncryptOpenssl($str,$encryptKey)
    {
        $localIV = $this->hex_iv;
        return base64_encode(openssl_encrypt($str, 'AES-128-CBC',$encryptKey,true,$localIV));
    }
    //decrypt_openssl新版解密
    function getDecryptOpenssl($str,$encryptKey)
    {
        $localIV = $this->hex_iv;
        return openssl_decrypt(base64_decode($str), 'AES-128-CBC', $encryptKey, true, $localIV);
    }
}