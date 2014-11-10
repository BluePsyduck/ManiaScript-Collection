<?php

/**
 * The main script to request the library codes.
 *
 * @author Marcel <marcel@mania-community.de>
 * @license http://opensource.org/licenses/GPL-2.0 GPL v2
 */

chdir(__DIR__);
require_once(__DIR__ . '/vendor/autoload.php');

$bootstrap = new \BluePsyduck\ManiaScriptCollection\Bootstrap();
$bootstrap->bootstrap();