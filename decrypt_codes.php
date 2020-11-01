<?php 

function encrypt($plaintext, $key) {
    $ivlen = openssl_cipher_iv_length("AES-256-CBC");
    $iv = openssl_random_pseudo_bytes($ivlen);

    $ciphertext_raw = openssl_encrypt(
        $plaintext, "AES-256-CBC", $key, $options=OPENSSL_RAW_DATA, $iv);
    
    $hmac = hash_hmac('sha256', $ciphertext_raw, $key, $as_binary=true);

    return base64_encode($iv.$hmac.$ciphertext_raw);
}

function decrypt($ciphertext, $key) {
    $c = base64_decode($ciphertext);
    $ivlen = openssl_cipher_iv_length("AES-256-CBC");
    $iv = substr($c, 0, $ivlen);
    $hmac = substr($c, $ivlen, $sha2len=32);
    $ciphertext_raw = substr($c, $ivlen+$sha2len);
    
    $original_plaintext = openssl_decrypt(
        $ciphertext_raw, "AES-256-CBC", $key, $options=OPENSSL_RAW_DATA, $iv);

    $calcmac = hash_hmac('sha256', $ciphertext_raw, $key, $as_binary=true);
    if (hash_equals($hmac, $calcmac)) {
        return $original_plaintext;
    } else {
        return false;
    }    
}

function gen_codes() {
$num_codis = 50;
$mida_codi_bytes = 16;

for ($i=0; $i<$num_codis; $i++) {
    $bytes = openssl_random_pseudo_bytes($mida_codi_bytes);
    $hex   = bin2hex($bytes);
    //var_dump($hex);
    echo $hex.", ";
}

}

$key = openssl_random_pseudo_bytes(16);
$plaintext = "This is a test!";
$ciphertext = encrypt($plaintext, $key);

echo "Key: ".bin2hex($key)."\n";
echo "Text: ".$plaintext."\n";
echo "Ciphertext: ".$ciphertext."\n";

// decrypt later....

$dectext = decrypt($ciphertext, $key);

echo "Decrypted text: ".$dectext."\n";

?>
