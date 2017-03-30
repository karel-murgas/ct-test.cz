<?php
header("P3P: policyref=\"/w3c/p3p.xml\", CP=\"NOI DSP COR NID PSA OUR IND COM NAV STA\"");
header("Content-type: text/javascript");
$expire = time() + 60 * 60 * 24 * 365 * 5;
$isIP = (bool)ip2long($_SERVER['HTTP_HOST']);
//$domain = ($isIP ? $_SERVER['HTTP_HOST'] : "." . implode(".", array_slice(explode(".", $_SERVER["HTTP_HOST"]), -2, 2, true)));
$domain = ".417.cz";
$redir = $_SERVER["QUERY_STRING"] !== "" ? strpos("n=1", $_SERVER["QUERY_STRING"]) !== false : false;
if (!isset($_COOKIE["vid"])) {
    $visitorId = substr(sha1(openssl_random_pseudo_bytes(32)), 0, 16);
    if (!$redir) {
        setcookie("vid", $visitorId, $expire, "/", $domain);
        header('Location: ' . $_SERVER["REQUEST_URI"] . "?n=1");
        die();
    } else {
        echo "measure({";
        echo "\n    event: 'init',";
        echo "\n    vid: '" . $visitorId . "',";
        echo "\n    cookieAvailable: false,";
        echo "\n    isNew: true";
        echo "\n});";
    }
} else {
    setcookie("vid", $_COOKIE["vid"], $expire, "/", $domain);
    echo "measure({";
    echo "\n    event: 'init',";
    echo "\n    vid: '". $_COOKIE["vid"] . "',";
    echo "\n    cookieAvailable: true,";
    echo "\n    isNew: " . ($redir ? "true" : "false");
    echo "\n});";
}
?>
