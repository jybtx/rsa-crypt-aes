<?php

namespace Jybtx\RsaCryptAes\Crypt;

use Illuminate\Support\Str;

class Aes
{
    private $hex_iv = '';
    private $method = '';

    public function __construct ()
    {
        $aes          = config('crypt.aes_encrypt_key');
        $this->method = $aes['method'];
        $this->hex_iv = $this->parseKey( $aes['hex_iv'] );
    }

    /**
     * encrypt_openssl 加密
     * @author jybtx
     * @date   2019-09-23
     * @param  [type]     $str        [description]
     * @param  [type]     $encryptKey [description]
     * @return [type]                 [description]
     */
    public function getEncryptOpenssl($str,$encryptKey)
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
    public function getDecryptOpenssl($str,$encryptKey)
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
    public function parseKey($key)
    {
        if (Str::startsWith($key, $prefix = 'base64:')) {
            $key = base64_decode(Str::after($key, $prefix));
        }

        return $key;
    }
}