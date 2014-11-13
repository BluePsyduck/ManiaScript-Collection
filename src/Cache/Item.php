<?php

namespace BluePsyduck\ManiaScriptCollection\Cache;

use DateTime;

/**
 * A container for the items saved in the cache.
 *
 * @author Marcel <marcel@mania-community.de>
 * @license http://opensource.org/licenses/GPL-2.0 GPL v2
 */
class Item {
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
     * The directives.
     * @var \ManiaScript\Builder\Directive\AbstractDirective[]
     */
    protected $directives = array();

    /**
     * The timestamp when the item has been saved to the cache.
     * @var \DateTime
     */
    protected $time;

    /**
     * Initializes the cache item.
     */
    public function __construct() {
        $this->time = new DateTime();
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

    /**
     * Sets the directives.
     * @param \ManiaScript\Builder\Directive\AbstractDirective[] $directives The directives.
     * @return $this Implementing fluent interface.
     */
    public function setDirectives(array $directives) {
        $this->directives = $directives;
        return $this;
    }

    /**
     * Returns the directives.
     * @return \ManiaScript\Builder\Directive\AbstractDirective[] The directives.
     */
    public function getDirectives() {
        return $this->directives;
    }

    /**
     * Sets the timestamp when the item has been saved to the cache.
     * @param \DateTime $time The timestamp.
     * @return $this Implementing fluent interface.
     */
    public function setTime(DateTime $time) {
        $this->time = $time;
        return $this;
    }

    /**
     * Returns the timestamp when the item has been saved to the cache.
     * @return \DateTime The timestamp.
     */
    public function getTime() {
        return $this->time;
    }
}