<?php

namespace BluePsyduck\ManiaScriptCollection\View\Helper;

/**
 * View helper to implode a list to a comma separated string.
 *
 * @author Marcel <marcel@mania-community.de>
 * @license http://opensource.org/licenses/GPL-2.0 GPL v2
 */
class ImplodeList extends AbstractHelper {
    /**
     * Implodes the list to a comma separated string.
     * @param array $list The list to implode.
     * @return string The imploded list.
     */
    public function __invoke($list) {
        $result = 'none';
        if (is_array($list) && !empty($list)) {
            $result = implode(', ', $list);
        }
        return $result;
    }
} 