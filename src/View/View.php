<?php

namespace BluePsyduck\ManiaScriptCollection\View;

/**
 * A class representing a view to be rendered.
 *
 * @author Marcel <marcel@mania-community.de>
 * @license http://opensource.org/licenses/GPL-2.0 GPL v2
 */
class View {
    /**
     * The template of the view.
     * @var string
     */
    protected $template = '';

    /**
     * The variables set to the view.
     * @var array
     */
    protected $variables = array();

    /**
     * Sets the template of the view.
     * @param string $template The template.
     * @return $this Implementing fluent interface.
     */
    public function setTemplate($template) {
        $this->template = $template;
        return $this;
    }

    /**
     * Returns the template of the view.
     * @return string The template.
     */
    public function getTemplate() {
        return $this->template;
    }

    /**
     * Sets a variable to the view.
     * @param string $name The name of the variable.
     * @param mixed $value The value.
     * @return $this Implementing fluent interface.
     */
    public function setVariable($name, $value) {
        $this->variables[$name] = $value;
        return $this;
    }

    /**
     * Returns a variable set to the view.
     * @param string $name The name of the variable.
     * @return mixed The value.
     */
    public function getVariable($name) {
        $result = null;
        if (isset($this->variables[$name])) {
            $result = $this->variables[$name];
        }
        return $result;
    }

    /**
     * Sets multiple variables to the view.
     * @param array $variables The variables.
     * @return $this Implementing fluent interface.
     */
    public function setVariables(array $variables) {
        $this->variables = array_merge($this->variables, $variables);
        return $this;
    }

    /**
     * Returns all variables set to the view.
     * @return array The variables.
     */
    public function getVariables() {
        return $this->variables;
    }
} 