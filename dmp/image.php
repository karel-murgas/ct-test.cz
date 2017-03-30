<?php
function gen_uuid() {
    return sprintf( '%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
        // 32 bits for "time_low"
        mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ),

        // 16 bits for "time_mid"
        mt_rand( 0, 0xffff ),

        // 16 bits for "time_hi_and_version",
        // four most significant bits holds version number 4
        mt_rand( 0, 0x0fff ) | 0x4000,

        // 16 bits, 8 bits for "clk_seq_hi_res",
        // 8 bits for "clk_seq_low",
        // two most significant bits holds zero and one for variant DCE1.1
        mt_rand( 0, 0x3fff ) | 0x8000,

        // 48 bits for "node"
        mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff )
    );
}
header("P3P: policyref=\"/w3c/p3p.xml\", CP=\"NOI DSP COR NID PSA OUR IND COM NAV STA\"");
header("Content-Type: image/gif");
$expire = time() + 60 * 60 * 24 * 365 * 5;
$domain = "." . implode(".", array_slice(explode(".", $_SERVER["HTTP_HOST"]), -2, 2, true));
$redir = $_SERVER["QUERY_STRING"] !== "" ? strpos("n=1", $_SERVER["QUERY_STRING"]) !== false : false;
if (!isset($_COOKIE["ct_tp_uuid"])) {
    $uuid = gen_uuid();
    if (!$redir) {
        setcookie("ct_tp_uuid", $uuid, $expire, "/", $domain);
        header("Location: " . $_SERVER["REQUEST_URI"] . "&n=1");
        die();
    }
} else {
    setcookie("ct_tp_uuid", $_COOKIE["ct_tp_uuid"], $expire, "/", $domain);
    echo "\x47\x49\x46\x38\x37\x61\x1\x0\x1\x0\x80\x0\x0\xfc\x6a\x6c\x0\x0\x0\x2c\x0\x0\x0\x0\x1\x0\x1\x0\x0\x2\x2\x44\x1\x0\x3b";
}
?>
