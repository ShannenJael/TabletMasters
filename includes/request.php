<?php
/** Query parameters used as text must not accept PHP's array form syntax. */
function tmQueryString(string $name, string $default = ''): string {
    return is_string($_GET[$name] ?? null) ? $_GET[$name] : $default;
}
