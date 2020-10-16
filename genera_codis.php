<?php

$num_codis = 50;
$mida_codi_bytes = 16;

for ($i=0; $i<100; $i++) {
    $bytes = openssl_random_pseudo_bytes($mida_codi_bytes);
    $hex   = bin2hex($bytes);
    //var_dump($hex);
    echo $hex."\n";
}
?>
