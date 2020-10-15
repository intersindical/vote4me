<?php

$mida = 16;

for ($i=0; $i<100; $i++) {
    $bytes = openssl_random_pseudo_bytes($mida);
    $hex   = bin2hex($bytes);
    var_dump($hex);
}
?>
