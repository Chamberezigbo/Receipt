<?php
/**
 * URL for a file under public/, with ?v=<last-modified time> so browsers fetch a
 * new copy as soon as the file changes (no stale images after a deploy).
 */
if (!function_exists('asset')) {
    function asset(string $path): string
    {
        $file = __DIR__ . '/../public/' . $path;
        $v = is_file($file) ? filemtime($file) : 0;
        return htmlspecialchars($path) . ($v ? '?v=' . $v : '');
    }
}
