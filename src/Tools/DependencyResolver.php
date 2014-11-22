<?php

namespace BluePsyduck\ManiaScriptCollection\Tools;

use BluePsyduck\ManiaScriptCollection\Logger\Logger;
use BluePsyduck\ManiaScriptCollection\Script\Factory;
use BluePsyduck\ManiaScriptCollection\Script\Script;

/**
 * Class able to resolve the dependencies of the scripts.
 *
 * @author Marcel <marcel@mania-community.de>
 * @license http://opensource.org/licenses/GPL-2.0 GPL v2
 *
 * @todo Detect dependency loops.
 */
class DependencyResolver {
    /**
     * The settings factory.
     * @var \BluePsyduck\ManiaScriptCollection\Script\Factory
     */
    protected $settingsFactory;

    /**
     * The required scripts.
     * @var string[]
     */
    protected $requiredScripts = array();

    /**
     * The scripts to load.
     * @var \BluePsyduck\ManiaScriptCollection\Script\Script[]
     */
    protected $scriptsToLoad = array();

    /**
     * Initializes the instance.
     */
    public function __construct() {
        $this->settingsFactory = new Factory();
    }

    /**
     * Sets the required scripts.
     * @param \BluePsyduck\ManiaScriptCollection\Script\Script[] $requiredScripts The script settings.
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
            $script = $this->settingsFactory->get($scriptName);
            if (is_null($script)) {
                Logger::getInstance()->logCritical(
                    'Unable to resolve "' . $scriptName . '": Script not known',
                    'DependencyResolver'
                );
                array_shift($this->requiredScripts);
            } else {
                $this->resolveDependencies($script);
            }
        }
        foreach ($this->scriptsToLoad as $script) {
            $this->checkConflicts($script);
        }
        return $this;
    }

    /**
     * Resolves the dependencies of the specified script.
     * @param \BluePsyduck\ManiaScriptCollection\Script\Script $script The script settings.
     * @return $this Implementing fluent interface.
     */
    protected function resolveDependencies(Script $script) {
        $hasUnloadedDependencies = false;
        foreach ($script->getDependencies() as $dependency) {
            if (!isset($this->scriptsToLoad[$dependency])) {
                array_unshift($this->requiredScripts, $dependency);
                $hasUnloadedDependencies = true;
                Logger::getInstance()->logInfo(
                    'Add new script "' . $dependency . '" as required by "' . $script->getName() . '"',
                    'DependencyResolver'
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
     * Checks whether the specified script has conflicts in the currently loaded scripts.
     * @param \BluePsyduck\ManiaScriptCollection\Script\Script $script
     * @return $this Implementing fluent interface.
     */
    protected function checkConflicts(Script $script) {
        foreach ($script->getConflicts() as $conflict) {
            if (isset($this->scriptsToLoad[$conflict])) {
                Logger::getInstance()->logCritical(
                    'Conflict: Scripts "' . $script->getName() . '" and "' . $conflict . '" are not compatible!',
                    'DependencyResolver'
                );
            }
        }
        return $this;
    }

    /**
     * Returns the scripts to load.
     * @return \BluePsyduck\ManiaScriptCollection\Script\Script[] The script settings.
     */
    public function getScriptsToLoad() {
        return $this->scriptsToLoad;
    }
}