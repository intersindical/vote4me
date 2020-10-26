function decrypt($key, $iv, $ciphertext) {
    $method = "AES-256-CBC";

    return openssl_decrypt($ciphertext, $method, $key, OPENSSL_RAW_DATA, $iv);
}


$key = hex2bin("0110bbc60a4d374b1f09ea33404e52bf");
$iv = hex2bin("789be74659c51791d9d1bf6163c5edbc");

$method = "AES-256-
