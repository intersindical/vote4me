<?php 

function encrypt($plaintext, $key)
{
    $ivlen = openssl_cipher_iv_length("AES-256-CBC");
    $iv = openssl_random_pseudo_bytes($ivlen);

    $ciphertext_raw = openssl_encrypt(
        $plaintext, "AES-256-CBC", $key, $options=OPENSSL_RAW_DATA, $iv
    );
    
    $hash = hash_hmac('sha256', $ciphertext_raw, $key, $as_binary=true);

    return base64_encode($iv.$hash.$ciphertext_raw);
}

function decrypt($ciphertext, $key)
{
    $c = base64_decode($ciphertext);
    $ivlen = openssl_cipher_iv_length("AES-256-CBC");
    $iv = substr($c, 0, $ivlen);
    $hash = substr($c, $ivlen, $sha2len=32);
    $ciphertext_raw = substr($c, $ivlen + $sha2len);
    
    $original_plaintext = openssl_decrypt(
        $ciphertext_raw, "AES-256-CBC", $key, $options=OPENSSL_RAW_DATA, $iv
    );

    $calc_hash = hash_hmac('sha256', $ciphertext_raw, $key, $as_binary=true);
    if (hash_equals($hash, $calc_hash)) {
        return $original_plaintext;
    } else {
        return false;
    }    
}

function gen_codes($num_codis, $key)
{
    $mida_codi_bytes = 16;
    $codes = array();
    for ($i=0; $i<$num_codis; $i++) {
        $bytes = openssl_random_pseudo_bytes($mida_codi_bytes);
        $hex   = bin2hex($bytes);
        $hex_enc = encrypt($hex, $key);
        $codes[] = $hex_enc . "\n";
    }
    return $codes;
}

if ($argc < 4) {
    die("php gen_codes.php [num_codes] [out_file.enc] [out_file.dec]\n");
}

$key = openssl_random_pseudo_bytes(16);
$key_hex = bin2hex($key);

$num_codes = intval($argv[1]);
$out_file_enc = $argv[2];
$out_file_dec = $argv[3];

$codes = gen_codes($num_codes, $key);

file_put_contents($out_file_enc, $codes);
echo "Key: ".$key_hex."\n";

// decrypt -------------------------------

$codes_enc = file_get_contents($out_file_enc);
$codes_enc = str_split($codes_enc, 128+1);

$codes_dec = array();
foreach ($codes as $code) {
    $codes_dec[] = decrypt($code, $key) . "\n";
}

file_put_contents($out_file_dec, $codes_dec);

?>
