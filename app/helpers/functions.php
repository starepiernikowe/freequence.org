<?php

/**
 * Redirect to a specified URL.
 *
 * @param string $url The URL to redirect to.
 */
function redirect($url) {
    header("Location: /" . $url);
    exit;
}