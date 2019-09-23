# rsa-crypt-aes
A developing toolkit for RSA and AES encryption and decryption under test

## Installation

### Composer

Execute the following command to get the latest version of the package:

```terminal
composer require jybtx/rsa-crypt-aes
```

### Laravel

#### >= laravel5.5

ServiceProvider will be attached automatically

#### Other

In your `config/app.php` add `Jybtx\RsaCryptAes\Providers\CryptServiceProvider::class` to the end of the `providers` array:

```php
'providers' => [
    ...
    Jybtx\RsaCryptAes\Providers\CryptServiceProvider::class,
],
'aliases'  => [
    ...
    "RsaCryptAes": Jybtx\RsaCryptAes\Faceds\RsaCryptAesFaced::class,
]
```

Publish Configuration

```shell
php artisan vendor:publish --provider "Jybtx\RsaCryptAes\Providers\CryptServiceProvider"
```

## Usage

### get a public key
```php
use RsaCryptAes;
$public = RsaCryptAes::getThePublicKey();
```

### decrypt Random String
```php
$random = RsaCryptAes::decryptRandomString($obj,$md5PublicKey);
```

### decrypt Encrypted Data
```php
$data = RsaCryptAes::getDecryptEncryptedData($random,$pubKeyMd5,$data);
if ( $data == FALSE ) return respone()->json(['status'=>100,'message'=>'Public key invalidation, retrieve']);
```

### encrypt Data
```php
$restart = RsaCryptAes::getEncryptedDataAndRandomStrings($status,$msg,$data);
return [
	'status'       => $status,
    'msg'          => $msg,
    'data'         => $string,
    'random'       => $encrypt_aes_key,
    'md5public'    => md5($pubKey)
];
```

## Last
 Tips:The encryption and decryption of RSA and AES still need to be improved, but it does not affect the normal use

# License
MIT