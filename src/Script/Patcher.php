<?php

namespace BluePsyduck\ManiaScriptCollection\Script;

use ManiaScript\Builder\Directive\Constant;
use ManiaScript\Builder\Directive\Library;
use ManiaScript\Builder\Directive\Setting;

/**
 * Class able to patch the scripts so that no directives are left.
 *
 * @author Marcel <marcel@mania-community.de>
 * @license http://opensource.org/licenses/GPL-2.0 GPL v2
 */
class Patcher {
    /**
     * The code to patch.
     * @var string
     */
    protected $code = '';

    /**
     * The patched code.
     * @var string
     */
    protected $patchedCode = '';

    /**
     * The patched out directives.
     * @var \ManiaScript\Builder\Directive\AbstractDirective[]
     */
    protected $directives = array();

    /**
     * Sets the code to patch.
     * @param string $code The code.
     * @return $this Implementing fluent interface.
     */
    public function setCode($code) {
        $this->code = $code;
        return $this;
    }

    /**
     * Patches the code.
     * @return $this Implementing fluent interface.
     */
    public function patch() {
        $patterns = array(
            '@#(Include)\s+"([^"]+)"\s+as\s+(\S+)\s@',
            '@#(Const|Setting)\s+(\S+)\s+(\S+)\s@'
        );
        $this->patchedCode = preg_replace_callback($patterns, array($this, 'callbackReplace'), $this->code);
        return $this;
    }

    /**
     * Returns the patched code.
     * @return string The code.
     */
    public function getPatchedCode() {
        return $this->patchedCode;
    }

    /**
     * Returns the patched out directives.
     * @return \ManiaScript\Builder\Directive\AbstractDirective[] The directives.
     */
    public function getDirectives() {
        return $this->directives;
    }

    /**
     * Callback for patching the directives.
     * @param array $matches The matches.
     * @return string The replaced directive.
     */
    public function callbackReplace($matches) {
        switch (strtolower($matches[1])) {
            case 'const':
                $directive = new Constant();
                $directive->setName($matches[2])
                          ->setValue($matches[3]);
                $this->directives[] = $directive;
                break;

            case 'include':
                $directive = new Library();
                $directive->setLibrary($matches[2])
                          ->setAlias($matches[3]);
                $this->directives[] = $directive;
                break;

            case 'setting':
                $directive = new Setting();
                $directive->setName($matches[2])
                          ->setValue($matches[3]);
                $this->directives[] = $directive;
                break;
        }
        return '';
    }
}