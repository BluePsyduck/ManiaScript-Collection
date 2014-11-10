<?php

namespace BluePsyduck\ManiaScriptCollection\Input;

/**
 * A container for the input parameters.
 *
 * @author Marcel <marcel@mania-community.de>
 * @license http://opensource.org/licenses/GPL-2.0 GPL v2
 */
class Parameters {
    /**
     * The scripts to load.
     * @var array
     */
    protected $scripts = array();

    /**
     * Sets the scripts to load.
     * @param array $scripts The scripts.
     * @return $this Implementing fluent interface.
     */
    public function setScripts($scripts) {
        $this->scripts = $scripts;
        return $this;
    }

    /**
     * Returns the scripts to load.
     * @return array The scripts.
     */
    public function getScripts() {
        return $this->scripts;
    }
}