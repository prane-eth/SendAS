<?php

/* To use in other file - include 'file_encryptor.php'; */


// referred https://riptutorial.com/php/example/25499/symmetric-encryption-and-decryption-of-large-files-with-openssl
define('FILE_ENCRYPTION_BLOCKS', 10000);

function encryptFile($filename, $key)   {
    $source = $filename;
    $dest = $filename . ".enc";
    $key = substr(sha1($key, true), 0, 16);
    $iv = openssl_random_pseudo_bytes(16);

    $error = false;
    if ($fpOut = fopen($dest, 'w')) {
        // Put the initialzation vector to the beginning of the file
        fwrite($fpOut, $iv);
        if ($fpIn = fopen($source, 'rb')) {
            while (!feof($fpIn)) {
                $plaintext = fread($fpIn, 16 * FILE_ENCRYPTION_BLOCKS);
                $ciphertext = openssl_encrypt($plaintext, 'AES-128-CBC', $key, OPENSSL_RAW_DATA, $iv);
                // Use the first 16 bytes of the ciphertext as the next initialization vector
                $iv = substr($ciphertext, 0, 16);
                fwrite($fpOut, $ciphertext);
            }
            fclose($fpIn);
        } else {
            $error = true;
        }
        fclose($fpOut);
    } else {
        $error = true;
    }

    // delete file - pending

    return $error ? false : $dest;
}

function decryptFile($filename, $key)   {
    $source = $filename . ".enc";
    $dest = $filename;
    $key = substr(sha1($key, true), 0, 16);

    $error = false;
    if ($fpOut = fopen($dest, 'w')) {
        if ($fpIn = fopen($source, 'rb')) {
            // Get the initialzation vector from the beginning of the file
            $iv = fread($fpIn, 16);
            while (!feof($fpIn)) {
                $ciphertext = fread($fpIn, 16 * (FILE_ENCRYPTION_BLOCKS + 1)); // we have to read one block more for decrypting than for encrypting
                $plaintext = openssl_decrypt($ciphertext, 'AES-128-CBC', $key, OPENSSL_RAW_DATA, $iv);
                // Use the first 16 bytes of the ciphertext as the next initialization vector
                $iv = substr($ciphertext, 0, 16);
                fwrite($fpOut, $plaintext);
            }
            fclose($fpIn);
        } else {
            $error = true;
        }
        fclose($fpOut);
    } else {
        $error = true;
    }

    return $error ? false : $dest;
}


$key = "770A8A65DA156D24EE2A093277530142";

// to test, uncomment any of these
//encryptFile("testing_file.pdf", $key);
// decryptFile("testing_file.pdf", $key);

//encryptFile("log.txt", $key);
//decryptFile("log.txt", $key);



/* old code - not working
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
}*/

?>