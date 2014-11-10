<?php

namespace BluePsyduck\ManiaScriptCollection\Script;

/**
 * A container class for the settings of a script.
 *
 * @author Marcel <marcel@mania-community.de>
 * @license http://opensource.org/licenses/GPL-2.0 GPL v2
 */
class Settings {
    /**
     * The name of the script.
     * @var string
     */
    protected $name = '';

    /**
     * The author of the script.
     * @var string
     */
    protected $author = '';

    /**
     * The source URL of the script, where it can be downloaded.
     * @var string
     */
    protected $source = '';

    /**
     * The namespace of the script, to detect potential conflicts.
     * @var string
     */
    protected $namespace = '';

    /**
     * The directives of the script.
     * @var \ManiaScript\Builder\Directive\AbstractDirective[]
     */
    protected $directives = array();

    /**
     * The dependencies of the script.
     * @var string[]
     */
    protected $dependencies = array();

    /**
     * The conflicts of the script.
     * @var string[]
     */
    protected $conflicts = array();

    /**
     * Sets the name of the script.
     * @param string $name The name.
     * @return $this Implementing fluent interface.
     */
    public function setName($name) {
        $this->name = $name;
        return $this;
    }

    /**
     * Returns the name of the script.
     * @return string The name.
     */
    public function getName() {
        return $this->name;
    }

    /**
     * Sets the author of the script.
     * @param string $author The author.
     * @return $this Implementing fluent interface.
     */
    public function setAuthor($author) {
        $this->author = $author;
        return $this;
    }

    /**
     * Returns the author of the script.
     * @return string The author.
     */
    public function getAuthor() {
        return $this->author;
    }

    /**
     * Sets the source URL of the script, where it can be downloaded.
     * @param string $source The source.
     * @return $this Implementing fluent interface.
     */
    public function setSource($source) {
        $this->source = $source;
        return $this;
    }

    /**
     * Returns the source URL of the script, where it can be downloaded.
     * @return string The source.
     */
    public function getSource() {
        return $this->source;
    }

    /**
     * Sets the namespace of the script, to detect potential conflicts.
     * @param string $namespace The namespace.
     * @return $this Implementing fluent interface.
     */
    public function setNamespace($namespace) {
        $this->namespace = $namespace;
        return $this;
    }

    /**
     * Returns the namespace of the script, to detect potential conflicts.
     * @return string The namespace.
     */
    public function getNamespace() {
        return $this->namespace;
    }

    /**
     * Sets the directives of the script.
     * @param \ManiaScript\Builder\Directive\AbstractDirective[] $directives The directives
     * @return $this Implementing fluent interface.
     */
    public function setDirectives(array $directives) {
        $this->directives = $directives;
        return $this;
    }

    /**
     * Returns the directives of the script.
     * @return \ManiaScript\Builder\Directive\AbstractDirective[] The directives.
     */
    public function getDirectives() {
        return $this->directives;
    }

    /**
     * Sets the dependencies of the script.
     * @param string[] $dependencies The dependencies.
     * @return $this Implementing fluent interface.
     */
    public function setDependencies($dependencies) {
        $this->dependencies = $dependencies;
        return $this;
    }

    /**
     * Returns the dependencies of the script.
     * @return string[] The dependencies.
     */
    public function getDependencies() {
        return $this->dependencies;
    }

    /**
     * Returns the conflicts of the script.
     * @return string[] The conflicts.
     */
    public function getConflicts() {
        return $this->conflicts;
    }

    /**
     * Sets the conflicts of the script.
     * @param string[] $conflicts The conflicts.
     * @return $this Implementing fluent interface.
     */
    public function setConflicts($conflicts) {
        $this->conflicts = $conflicts;
        return $this;
    }
}