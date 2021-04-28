<?php

/* To use in other file - include 'file_encryptor.php'; */

function encrypt_file($filename, $key) 	{
	
    // get file as string
    $plaintext = file_get_contents($filename);

    // delete filename - pending


    // referred https://www.php.net/manual/en/function.openssl-encrypt.php
    $tag = "GCM";
    $cipher = "aes-128-gcm";
    if (in_array($cipher, openssl_get_cipher_methods()))    {
        $ivlen = openssl_cipher_iv_length($cipher);
        $iv = openssl_random_pseudo_bytes($ivlen);
        $ciphertext = openssl_encrypt($plaintext, $cipher, $key, $options=0, $iv, $tag);
    }

    // write ciphertext to file
    // new filename is $filename + "_enc"
    file_put_contents($filename."_enc", $ciphertext);
}

function decrypt_file($filename, $key) 	{
	
    // get file as string
    // encrypted filename is $filename + '_enc'
    $ciphertext = file_get_contents($filename."_enc");

    // delete filename - pending


    // referred Example #1 - https://www.php.net/manual/en/function.openssl-encrypt.php
    $cipher = "aes-128-gcm";
    $tag = "GCM";
    if (in_array($cipher, openssl_get_cipher_methods()))    {
        $ivlen = openssl_cipher_iv_length($cipher);
        $iv = openssl_random_pseudo_bytes($ivlen);
        $plaintext = openssl_decrypt($ciphertext, $cipher, $key, $options=0, $iv, $tag);
    }

    echo $plaintext;
    // write to file
    file_put_contents($filename, $plaintext);
}

$key = "770A8A65DA156D24EE2A093277530142";

//encrypt_file("testing_file.pdf", $key);
//decrypt_file("testing_file.pdf", $key);

decrypt_file("log.txt", $key);

?>