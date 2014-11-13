<?php

namespace BluePsyduck\ManiaScriptCollection\View\Helper;

/**
 * 
 *
 * @author Marcel <marcel@mania-community.de>
 * @license http://opensource.org/licenses/GPL-2.0 GPL v2
 */
class Escape extends AbstractHelper {
    public function __invoke($string) {
        return htmlspecialchars($string);
    }
} 