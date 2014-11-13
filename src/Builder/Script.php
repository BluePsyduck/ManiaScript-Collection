<?php

namespace BluePsyduck\ManiaScriptCollection\Builder;

use BluePsyduck\ManiaScriptCollection\Parameters\Parameters;
use BluePsyduck\ManiaScriptCollection\Logger\Logger;
use ManiaScript\Builder as ManiaScriptBuilder;
use ManiaScript\Builder\Code as ManiaScriptCode;

/**
 * Class building the final script to be printed.
 *
 * @author Marcel <marcel@mania-community.de>
 * @license http://opensource.org/licenses/GPL-2.0 GPL v2
 */
class Script implements BuilderInterface {
    /**
     * The input parameters.
     * @var \BluePsyduck\ManiaScriptCollection\Parameters\Parameters
     */
    protected $parameters;

    /**
     * The codes to print.
     * @var \BluePsyduck\ManiaScriptCollection\Script\Code[]
     */
    protected $codes;

    /**
     * The ManiaScript builder.
     * @var \ManiaScript\Builder
     */
    protected $builder;

    /**
     * Initializes the instance.
     */
    public function __construct() {
        $this->builder = new ManiaScriptBuilder();
    }

    /**
     * Sets the input parameters.
     * @param \BluePsyduck\ManiaScriptCollection\Parameters\Parameters $parameters The parameters instance.
     * @return $this Implementing fluent interface.
     */
    public function setParameters(Parameters $parameters) {
        $this->parameters = $parameters;
        return $this;
    }

    /**
     * Sets the codes to print.
     * @param \BluePsyduck\ManiaScriptCollection\Script\Code[] $codes The codes.
     * @return $this Implementing fluent interface.
     */
    public function setCodes($codes) {
        $this->codes = $codes;
        return $this;
    }

    /**
     * Builds the final code.
     * @return $this Implementing fluent interface.
     */
    public function build() {
        $this->buildCodes()
             ->buildLog();
        return $this;
    }

    /**
     * Build the script codes.
     * @return $this Implementing fluent interface.
     */
    protected function buildCodes() {
        foreach ($this->codes as $code) {
            $globalCode = new ManiaScriptCode();
            if ($this->parameters->getCompress()) {
                $globalCode->setCode($code->getCompressedCode());
            } else {
                $globalCode->setCode($code->getPatchedCode());
            }
            $this->builder->addGlobalCode($globalCode);

            foreach ($code->getDirectives() as $directive) {
                $this->builder->addDirective($directive);
            }
        }
        return $this;
    }

    /**
     * Builds the log.
     * @return $this Implementing fluent interface.
     */
    protected function buildLog() {
        $items = Logger::getInstance()->getFilteredItems($this->parameters->getLogLevel());
        if (!empty($items)) {
            $log = '/**' . PHP_EOL
                . ' * ManiaScript Collection Log: ' . PHP_EOL;
            foreach ($items as $item) {
                $log .= ' * [' . strtoupper($item->getLevel()) . '] ';
                if ($item->getTool()) {
                    $log .= '[' . $item->getTool() . '] ';
                }
                $log .= $item->getMessage() . PHP_EOL;
            }
            $log .= ' */' . PHP_EOL;

            $globalCode = new ManiaScriptCode();
            $globalCode->setCode($log)
                       ->setPriority(0);
            $this->builder->addGlobalCode($globalCode);
        }
        return $this;
    }

    /**
     * Returns the headers to be set.
     * @return array The header lines.
     */
    public function getHeaders() {
        return array('Content-Type: text/xml;charset=utf8');
    }

    /**
     * Returns the rendered content to be send to the client.
     * @return string The rendered content.
     */
    public function getRenderedContent() {
        $this->builder->getOptions()->setCompress(false)
                      ->setIncludeScriptTag(true)
                      ->setRenderContextDirective(false)
                      ->setRenderMainFunction(false);
        $this->builder->build();
        return $this->builder->getCode();
    }
}