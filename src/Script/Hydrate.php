<?php

namespace BluePsyduck\ManiaScriptCollection\Script;

/**
 * Class for hydrating the script settings from the JSON input to the script class.
 *
 * @author Marcel <marcel@mania-community.de>
 * @license http://opensource.org/licenses/GPL-2.0 GPL v2
 */
class Hydrate {
    /**
     * Hydrates the script settings.
     * @param \BluePsyduck\ManiaScriptCollection\Script\Script $script The script instance.
     * @param array $data The data.
     * @return \BluePsyduck\ManiaScriptCollection\Script\Script The hydrated script instance.
     */
    public function hydrate(Script $script, array $data) {
        if (isset($data['name'])) {
            $script->setName(trim($data['name']));
        }
        if (isset($data['author'])) {
            $script->setAuthor(trim($data['author']));
        }
        if (isset($data['source'])) {
            $script->setSource(trim($data['source']));
        }
        if (isset($data['namespace'])) {
            $script->setNamespace(trim($data['namespace']));
        }
        if (isset($data['dependencies']) && is_array($data['dependencies'])) {
            $script->setDependencies(array_map('strval', $data['dependencies']));
        }
        if (isset($data['conflicts']) && is_array($data['conflicts'])) {
            $script->setConflicts(array_map('strval', $data['conflicts']));
        }
        return $script;
    }
}