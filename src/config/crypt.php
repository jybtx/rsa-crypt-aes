<?php

return [

	/**
	 * AES random string length is 18 bits
	 * converted JAVA byte code in to HEX and placed it here
	 */
	'key_random'    => 'wevSVLX7Y7jxyUTE9',
	
	/**
	 * openssl_pkey_new(): 
	 * private key length is too short; 
	 * it needs to be at least 384 bits, not 256
	 * The minimum length of public and private keys is 384 bits.
	 */
	'key_len'       => 384,  // Key length

	/**
	 * The length of RSA random string is at least 16 bits and the longest 64 bits
	 */
	'random_aes_key'    => 16,
];