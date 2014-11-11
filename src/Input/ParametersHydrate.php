<?php

namespace BluePsyduck\ManiaScriptCollection\Input;

/**
 * Class for hydrating the parameters from the input to the parameters class.
 *
 * @author Marcel <marcel@mania-community.de>
 * @license http://opensource.org/licenses/GPL-2.0 GPL v2
 */
class ParametersHydrate {
    /**
     * Hydrates the parameters.
     * @param \BluePsyduck\ManiaScriptCollection\Input\Parameters $parameters The parameters instance.
     * @param array $data The data.
     * @return \BluePsyduck\ManiaScriptCollection\Input\Parameters The hydrated parameters.
     */
    public function hydrate(Parameters $parameters, array $data) {
        reset($data);
        $current = current($data);
        if (empty($current)) {
            $scripts = key($data);
            if (!empty($scripts) && is_string($scripts)) {
                $parameters->setScripts(array_filter(explode(',', $scripts)));
            }
        }
        if (isset($data['compress'])) {
            $parameters->setCompress($data['compress'] != 0);
        }
        if (isset($data['log'])) {
            $parameters->setLogLevel($data['log']);
        }
        return $parameters;
    }
}