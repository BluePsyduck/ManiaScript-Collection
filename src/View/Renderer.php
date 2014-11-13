<?php

namespace BluePsyduck\ManiaScriptCollection\View;

use BluePsyduck\ManiaScriptCollection\View\Helper\AbstractHelper;
use BluePsyduck\ManiaScriptCollection\View\Helper\Factory;

/**
 * The class able to render a view.
 *
 * @author Marcel <marcel@mania-community.de>
 * @license http://opensource.org/licenses/GPL-2.0 GPL v2
 */
class Renderer {
    /**
     * The directory of the template files.
     */
    const DIRECTORY = 'view';

    /**
     * The view helper factory.
     * @var \BluePsyduck\ManiaScriptCollection\View\Helper\Factory
     */
    protected $helperFactory;

    /**
     * The view to render.
     * @var \BluePsyduck\ManiaScriptCollection\View\View
     */
    protected $view;

    /**
     * The rendered content of the view.
     * @var string
     */
    protected $renderedContent = '';

    /**
     * Initializes the renderer.
     */
    public function __construct() {
        $this->helperFactory = new Factory();
        $this->view = new View();
    }

    /**
     * Sets the view to render.
     * @param \BluePsyduck\ManiaScriptCollection\View\View $view The view.
     * @return $this Implementing fluent interface.
     */
    public function setView(View $view) {
        $this->view = $view;
        return $this;
    }

    /**
     * Renders the view.
     * @return $this Implementing fluent interface.
     */
    public function render() {
        $this->renderedContent = '';
        if ($this->view instanceof View) {
            $template = self::DIRECTORY . DIRECTORY_SEPARATOR . $this->view->getTemplate();
            if (file_exists($template)) {
                ob_start();
                include($template);
                $this->renderedContent = ob_get_contents();
                ob_end_clean();
            }
        }
        return $this;
    }

    /**
     * Returns the rendered content of the view.
     * @return string The rendered content.
     */
    public function getRenderedContent() {
        return $this->renderedContent;
    }

    /**
     * Returns a variable from the view.
     * @param string $name The name of the variable.
     * @return mixed The value.
     */
    public function __get($name) {
        $result = null;
        if ($this->view instanceof View) {
            $result = $this->view->getVariable($name);
        }
        return $result;
    }

    /**
     * Sets a variable to the view.
     * @param string $name The name of the variable.
     * @param mixed $value The value.
     * @return $this Implementing fluent interface.
     */
    public function __set($name, $value) {
        if ($this->view instanceof View) {
            $this->view->setVariable($name, $value);
        }
        return $this;
    }

    /**
     * Invokes a view helper.
     * @param string $name The name of the view helper.
     * @param array $arguments The arguments to pass to the view helper.
     * @return mixed The result of the view helper.
     */
    public function __call($name, $arguments) {
        $helper = $this->helperFactory->get($name);
        $result = null;
        if ($helper instanceof AbstractHelper) {
            $helper->setView($this->view);
            $result = call_user_func_array($helper, $arguments);
        }
        return $result;
    }
} 