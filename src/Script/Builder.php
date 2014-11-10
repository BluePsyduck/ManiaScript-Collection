<?php

namespace BluePsyduck\ManiaScriptCollection\Script;

use ManiaScript\Builder as ManiaScriptBuilder;
use ManiaScript\Builder\Code as ManiaScriptCode;

/**
 * Class building the final script to be printed.
 *
 * @author Marcel <marcel@mania-community.de>
 * @license http://opensource.org/licenses/GPL-2.0 GPL v2
 */
class Builder {
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
        foreach ($this->codes as $code) {
            $globalCode = new ManiaScriptCode();
            $globalCode->setCode($code->getPatchedCode());
            $this->builder->addGlobalCode($globalCode);

            foreach ($code->getDirectives() as $directive) {
                $this->builder->addDirective($directive);
            }
        }
        return $this;
    }

    /**
     * Returns the final code.
     * @return string The final code.
     */
    public function getFinalCode() {
        $this->builder->getOptions()->setCompress(false)
                                    ->setIncludeScriptTag(true);
        $this->builder->build();
        return $this->builder->getCode();
    }
}