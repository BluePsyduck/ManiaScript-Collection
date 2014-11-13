<?php

namespace BluePsyduck\ManiaScriptCollection;

use BluePsyduck\ManiaScriptCollection\Builder\BuilderInterface;
use BluePsyduck\ManiaScriptCollection\Builder\Frontend as FrontendBuilder;
use BluePsyduck\ManiaScriptCollection\Builder\Script as ScriptBuilder;
use BluePsyduck\ManiaScriptCollection\Parameters\Parameters;
use BluePsyduck\ManiaScriptCollection\Parameters\Hydrate;
use BluePsyduck\ManiaScriptCollection\Tools\DependencyResolver;
use BluePsyduck\ManiaScriptCollection\Tools\Loader;

/**
 * The bootstrap class.
 *
 * @author Marcel <marcel@mania-community.de>
 * @license http://opensource.org/licenses/GPL-2.0 GPL v2
 */
class Bootstrap {
    /**
     * The main method of the project.
     * @return $this Implementing fluent interface.
     */
    public function bootstrap() {
        $parameters = $this->createParameters();
        if ($this->showFrontend($parameters)) {
            $builder = new FrontendBuilder();
            $builder->build();
        } else {
            $dependencyResolver = new DependencyResolver();
            $dependencyResolver->setRequiredScripts($parameters->getScripts())
                               ->resolve();

            $loader = new Loader();
            $loader->setScriptsToLoad($dependencyResolver->getScriptsToLoad())
                   ->load();

            $builder = new ScriptBuilder();
            $builder->setParameters($parameters)
                    ->setCodes($loader->getScriptCodes())
                    ->build();
        }
        $this->printHeadersAndContent($builder);
        return $this;
    }

    /**
     * Creates and returns the input parameters.
     * @return \BluePsyduck\ManiaScriptCollection\Parameters\Parameters The parameters instance.
     */
    protected function createParameters() {
        $parametersHydrate = new Hydrate();
        return $parametersHydrate->hydrate(new Parameters(), $_GET);
    }

    /**
     * Checks whether we have to show the frontend.
     * @param \BluePsyduck\ManiaScriptCollection\Parameters\Parameters $parameters The parameters.
     * @return bool The result of the check.
     */
    protected function showFrontend(Parameters $parameters) {
        return count($parameters->getScripts()) === 0
            && strpos($_SERVER['HTTP_USER_AGENT'], 'ManiaPlanet') === false;
    }

    /**
     * Prints the headers and the content of the specified builder.
     * @param \BluePsyduck\ManiaScriptCollection\Builder\BuilderInterface $builder The builder to print.
     * @return $this Implementing fluent interface.
     */
    protected function printHeadersAndContent(BuilderInterface $builder) {
        foreach ($builder->getHeaders() as $header) {
            header($header);
        }
        echo $builder->getRenderedContent();
        return $this;
    }
}