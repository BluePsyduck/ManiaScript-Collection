<?php

namespace BluePsyduck\ManiaScriptCollection\Script;

/**
 * A container for the actual code of a script.
 *
 * @author Marcel <marcel@mania-community.de>
 * @license http://opensource.org/licenses/GPL-2.0 GPL v2
 */
class Code {
    /**
     * The script settings of the code.
     * @var \BluePsyduck\ManiaScriptCollection\Script\Settings
     */
    protected $settings;

    /**
     * The raw code as received from the external source.
     * @var string
     */
    protected $rawCode = '';

    /**
     * The patched code, where the directives has been removed.
     * @var string
     */
    protected $patchedCode = '';

    /**
     * The compressed code.
     * @var string
     */
    protected $compressedCode = '';

    /**
     * Sets the script settings of the code.
     * @param \BluePsyduck\ManiaScriptCollection\Script\Settings $settings The script settings.
     * @return $this Implementing fluent interface.
     */
    public function setSettings($settings) {
        $this->settings = $settings;
        return $this;
    }

    /**
     * Returns the script settings of the code.
     * @return \BluePsyduck\ManiaScriptCollection\Script\Settings The script settings.
     */
    public function getSettings() {
        return $this->settings;
    }

    /**
     * Sets the raw code as received from the external source.
     * @param string $rawCode The raw code.
     * @return $this Implementing fluent interface.
     */
    public function setRawCode($rawCode) {
        $this->rawCode = $rawCode;
        return $this;
    }

    /**
     * Returns the raw code as received from the external source.
     * @return string The raw code.
     */
    public function getRawCode() {
        return $this->rawCode;
    }

    /**
     * Sets the patched code, where the directives has been removed.
     * @param string $patchedCode The patched code.
     * @return $this Implementing fluent interface.
     */
    public function setPatchedCode($patchedCode) {
        $this->patchedCode = $patchedCode;
        return $this;
    }

    /**
     * Returns the patched code, where the directives has been removed.
     * @return string The patched code.
     */
    public function getPatchedCode() {
        return $this->patchedCode;
    }

    /**
     * Sets the compressed code.
     * @param string $compressedCode The compressed code.
     * @return $this Implementing fluent interface.
     */
    public function setCompressedCode($compressedCode) {
        $this->compressedCode = $compressedCode;
        return $this;
    }

    /**
     * Returns the compressed code.
     * @return string The compressed code.
     */
    public function getCompressedCode() {
        return $this->compressedCode;
    }
}