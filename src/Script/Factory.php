<?php

namespace BluePsyduck\ManiaScriptCollection\Script;

/**
 * The factory class for the script settings.
 *
 * @author Marcel <marcel@mania-community.de>
 * @license http://opensource.org/licenses/GPL-2.0 GPL v2
 */
class Factory {
    /**
     * The directory where the script settings are saved.
     */
    const DIRECTORY = 'script';

    /**
     * The already created script settings instances.
     * @var array
     */
    protected $instances = array();

    /**
     * The hydrate instance of the settings.
     * @var \BluePsyduck\ManiaScriptCollection\Script\Hydrate
     */
    protected $settingsHydrate;

    /**
     * Initializes the factory.
     */
    public function __construct() {
        $this->settingsHydrate = new Hydrate();
    }

    /**
     * Returns the settings of the specified script.
     * @param string $name The name of the script.
     * @return \BluePsyduck\ManiaScriptCollection\Script\Script|null The settings.
     */
    public function get($name) {
        if (!array_key_exists($name, $this->instances)) {
            $this->instances[$name] = $this->create($name);
        }
        return $this->instances[$name];
    }

    /**
     * Creates the settings of the specified script.
     * @param string $name The name of the script.
     * @return \BluePsyduck\ManiaScriptCollection\Script\Script|null The settings instance.
     */
    protected function create($name) {
        $result = null;
        $fileName = $this->getFileName($name);
        if (file_exists($fileName)) {
            $data = json_decode(file_get_contents($fileName), true);
            if (is_array($data)) {
                $result = $this->settingsHydrate->hydrate(new Script(), $data);
            }
        }
        return $result;
    }

    /**
     * Provides the file name of the specified script.
     * @param string $name The name of the script.
     * @return string The file name.
     */
    protected function getFileName($name) {
        $result = '';
        if (preg_match('#([a-zA-Z\-_]+)/([a-zA-Z\-_]+)#', $name, $matches) > 0) {
            $group = strtolower($matches[1]);
            $script = strtolower($matches[2]);
            $result = self::DIRECTORY . DIRECTORY_SEPARATOR . $group . DIRECTORY_SEPARATOR . $script . '.json';
        }
        return $result;
    }
}