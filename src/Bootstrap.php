<?php

namespace BluePsyduck\ManiaScriptCollection;

use BluePsyduck\ManiaScriptCollection\Input\Parameters;
use BluePsyduck\ManiaScriptCollection\Input\ParametersHydrate;
use BluePsyduck\ManiaScriptCollection\Script\Builder;
use BluePsyduck\ManiaScriptCollection\Script\DependencyResolver;
use BluePsyduck\ManiaScriptCollection\Script\Loader;

/**
 * The bootstrap class.
 *
 * @author Marcel <marcel@mania-community.de>
 * @license http://opensource.org/licenses/GPL-2.0 GPL v2
 */
class Bootstrap {
    /**
     * The main method of the project.
     */
    public function bootstrap() {
        $parameters = $this->createParameters();

        $dependencyResolver = $this->createDependencyResolver();
        $dependencyResolver->setRequiredScripts($parameters->getScripts())
                           ->resolve();

        $loader = $this->createLoader();
        $loader->setScriptsToLoad($dependencyResolver->getScriptsToLoad())
               ->load();

        $builder = $this->createBuilder();
        $builder->setParameters($parameters)
                ->setCodes($loader->getScriptCodes())
                ->build();

        header('Content-Type: text/xml;charset=utf8');
        echo $builder->getFinalCode();
    }

    /**
     * Creates and returns the input parameters.
     * @return \BluePsyduck\ManiaScriptCollection\Input\Parameters The parameters instance.
     */
    protected function createParameters() {
        $parametersHydrate = new ParametersHydrate();
        return $parametersHydrate->hydrate(new Parameters(), $_GET);
    }

    /**
     * Creates and returns the dependency resolver.
     * @return \BluePsyduck\ManiaScriptCollection\Script\DependencyResolver The dependency resolver instance.
     */
    protected function createDependencyResolver() {
        return new DependencyResolver();
    }

    /**
     * Creates and returns the loader.
     * @return Loader
     */
    protected function createLoader() {
        return new Loader();
    }

    /**
     * Creates and returns the builder.
     * @return Builder
     */
    protected function createBuilder() {
        return new Builder();
    }
}