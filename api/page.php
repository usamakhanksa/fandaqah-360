<?php
/* The start of every generated page (tools/build-php.py): the headers a PHP host should send with it. The page itself is the .html
   page, copied, with its forms pointed at api/lead.php. */
if (!headers_sent()) {
    header('Content-Type: text/html; charset=utf-8');
    header('X-Content-Type-Options: nosniff');
    header('Referrer-Policy: strict-origin-when-cross-origin');
    header('X-Frame-Options: SAMEORIGIN');
    header('Permissions-Policy: camera=(), microphone=(), geolocation=()');
}
