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
        if (isset($data['directives']) && is_array($data['directives'])) {
            $directives = array();
            foreach ($data['directives'] as $directiveData) {
                $directives[] = $this->mapDirective($directiveData);
            }
            $settings->setDirectives(array_filter($directives));
        }
        if (isset($data['dependencies']) && is_array($data['dependencies'])) {
            $settings->setDependencies(array_map('strval', $data['dependencies']));
        }
        if (isset($data['conflicts']) && is_array($data['conflicts'])) {
            $settings->setConflicts(array_map('strval', $data['conflicts']));
        }
        return $settings;
    }

    /**
     * Maps the directive data to an instance.
     * @param array $data The data.
     * @return \ManiaScript\Builder\Directive\AbstractDirective The directive instance.
     */
    protected function mapDirective(array $data) {
        $result = null;
        if (isset($data['type'])) {
            switch ($data['type']) {
                case 'include':
                case 'library':
                    if (isset($data['name'])) {
                        $result = new Library();
                        $result->setLibrary($data['name']);
                        if (isset($data['alias'])) {
                            $result->setAlias($data['alias']);
                        }
                    }
                    break;

                case 'const':
                case 'constant':
                    if (isset($data['name']) && isset($data['value'])) {
                        $result = new Constant();
                        $result->setName($data['name'])
                               ->setValue($data['value']);
                    }
                    break;

                case 'setting':
                    if (isset($data['name']) && isset($data['value'])) {
                        $result = new Setting();
                        $result->setName($data['name'])
                               ->setValue($data['value']);
                    }
                    break;
            }
        }
        return $result;
    }
}