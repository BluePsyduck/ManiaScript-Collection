<?php

namespace BluePsyduck\ManiaScriptCollection\View\Helper;

/**
 * View helper for getting the script url to link back to oneself.
 *
 * @author Marcel <marcel@mania-community.de>
 * @license http://opensource.org/licenses/GPL-2.0 GPL v2
 */
class ScriptUrl extends AbstractHelper {
    /**
     * Retrieves and returns the script url.
     * @return string The script url.
     */
    public function __invoke() {
        $host = $_SERVER['SERVER_NAME'];
        $script = $_SERVER['REQUEST_URI'];
        $port = intval($_SERVER['SERVER_PORT']);

        if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') {
            $protocol = 'https';
            if ($port == 443) {
                $port = 0;
            }
        } else {
            $protocol = 'http';
            if ($port == 80) {
                $port = 0;
            }
        }

        return $protocol . '://' . $host . ($port > 0 ? ':' . $port : '') . $script;
    }
} 