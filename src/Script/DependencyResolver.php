<?php

namespace BluePsyduck\ManiaScriptCollection\Script;

use BluePsyduck\ManiaScriptCollection\Log\Logger;

/**
 * Class able to resolve the dependencies of the scripts.
 *
 * @author Marcel <marcel@mania-community.de>
 * @license http://opensource.org/licenses/GPL-2.0 GPL v2
 *
 * @todo Detect dependency loops.
 * @todo Detect conflicts
 */
class DependencyResolver {
    /**
     * The settings factory.
     * @var \BluePsyduck\ManiaScriptCollection\Script\SettingsFactory
     */
    protected $settingsFactory;

    /**
     * The required scripts.
     * @var string[]
     */
    protected $requiredScripts = array();

    /**
     * The scripts to load.
     * @var \BluePsyduck\ManiaScriptCollection\Script\Settings[]
     */
    protected $scriptsToLoad = array();

    /**
     * Initializes the instance.
     */
    public function __construct() {
        $this->settingsFactory = new SettingsFactory();
    }

    /**
     * Sets the required scripts.
     * @param \BluePsyduck\ManiaScriptCollection\Script\Settings[] $requiredScripts The script settings.
     * @return $this Implementing fluent interface.
     */
    public function setRequiredScripts(array $requiredScripts) {
        $this->requiredScripts = $requiredScripts;
        return $this;
    }

    /**
     * Resolves the dependencies.
     * @return $this Implementing fluent interface.
     */
    public function resolve() {
        $this->scriptsToLoad = array();
        while (!empty($this->requiredScripts)) {
            $scriptName = $this->requiredScripts[0];
            $settings = $this->settingsFactory->get($scriptName);
            if (is_null($settings)) {
                Logger::getInstance()->log(
                    '[DependencyResolver] Unable to resolve "' . $scriptName . '": Script not known.',
                    Logger::LEVEL_CRITICAL
                );
                array_shift($this->requiredScripts);
            } else {
                $this->resolveDependencies($settings);
            }
        }
        return $this;
    }

    /**
     * Resolves the dependencies of the specified script.
     * @param \BluePsyduck\ManiaScriptCollection\Script\Settings $script The script settings.
     * @return $this Implementing fluent interface.
     */
    protected function resolveDependencies(Settings $script) {
        $hasUnloadedDependencies = false;
        foreach ($script->getDependencies() as $dependency) {
            if (!isset($this->scriptsToLoad[$dependency])) {
                array_unshift($this->requiredScripts, $dependency);
                $hasUnloadedDependencies = true;
                Logger::getInstance()->log(
                    '[DependencyResolver] Add dependency "' . $dependency . '" of "' . $script->getName() . '".',
                    Logger::LEVEL_INFO
                );
            }
        }
        if (!$hasUnloadedDependencies) {
            array_shift($this->requiredScripts);
            $this->scriptsToLoad[$script->getName()] = $script;
        }
        return $this;
    }

    /**
     * Returns the scripts to load.
     * @return \BluePsyduck\ManiaScriptCollection\Script\Settings[] The script settings.
     */
    public function getScriptsToLoad() {
        return $this->scriptsToLoad;
    }
}