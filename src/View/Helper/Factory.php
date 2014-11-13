<?php

namespace BluePsyduck\ManiaScriptCollection\View\Helper;

/**
 * A factory for the view helpers.
 *
 * @author Marcel <marcel@mania-community.de>
 * @license http://opensource.org/licenses/GPL-2.0 GPL v2
 */
class Factory {
    /**
     * The already created instances of view helpers.
     * @var
     */
    protected $instances = array();

    /**
     * Returns the view helper of the name.
     * @param string $name The name of the view helper.
     * @return \BluePsyduck\ManiaScriptCollection\View\Helper\AbstractHelper|null The view helper.
     */
    public function get($name) {
        if (!array_key_exists($name, $this->instances)) {
            $this->instances[$name] = $this->create($name);
        }
        return $this->instances[$name];
    }

    /**
     * Creates the view helper with the specified name.
     * @param string $name The name of the view helper.
     * @return \BluePsyduck\ManiaScriptCollection\View\Helper\AbstractHelper|null The view helper.
     */
    protected function create($name) {
        $result = null;
        $class = 'BluePsyduck\ManiaScriptCollection\View\Helper\\' . ucfirst($name);
        if (class_exists($class)
            && is_subclass_of($class, 'BluePsyduck\ManiaScriptCollection\View\Helper\AbstractHelper')
        ) {
            $result = new $class();
        }
        return $result;
    }
} 