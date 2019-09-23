<?php

return [

	
	/**
	 * openssl_pkey_new(): 
	 * private key length is too short; 
	 * it needs to be at least 384 bits, not 256
	 * The minimum length of public and private keys is 384 bits.
	 * The length of the key can be 384,512,1024,2048.
	 */
	'private_key_bits'  => 1024,  // Key length
		
	/**
	* The length of RSA random string is at least 16 bits and the longest 64 bits
	*/
	'random_aes_key'    => 32,
		
	'aes_encrypt_key'   => [
		
		'hex_iv' => 'dBAyMA75vkUZbQWo', // IV can only be 16 bytes
		
		'method' => 'AES-256-CBC', //Encryption mode
	],
];