<?php

namespace BluePsyduck\ManiaScriptCollection\Script;

use ManiaScript\Builder\Directive\Constant;
use ManiaScript\Builder\Directive\Library;
use ManiaScript\Builder\Directive\Setting;

/**
 * Class for hydrating the settings from the JSON input to the settings class.
 *
 * @author Marcel <marcel@mania-community.de>
 * @license http://opensource.org/licenses/GPL-2.0 GPL v2
 */
class SettingsHydrate {
    /**
     * Hydrates the settings.
     * @param \BluePsyduck\ManiaScriptCollection\Script\Settings $settings The settings instance.
     * @param array $data The data.
     * @return \BluePsyduck\ManiaScriptCollection\Script\Settings $settings The hydrated settings instance.
     */
    public function hydrate(Settings $settings, array $data) {
        if (isset($data['name'])) {
            $settings->setName(trim($data['name']));
        }
        if (isset($data['author'])) {
            $settings->setAuthor(trim($data['author']));
        }
        if (isset($data['source'])) {
            $settings->setSource(trim($data['source']));
        }
        if (isset($data['namespace'])) {
            $settings->setNamespace(trim($data['namespace']));
        }
        if (isset($data['dependencies']) && is_array($data['dependencies'])) {
            $settings->setDependencies(array_map('strval', $data['dependencies']));
        }
        if (isset($data['conflicts']) && is_array($data['conflicts'])) {
            $settings->setConflicts(array_map('strval', $data['conflicts']));
        }
        return $settings;
    }
}