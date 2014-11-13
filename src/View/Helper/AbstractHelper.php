<?php

namespace BluePsyduck\ManiaScriptCollection\View\Helper;

use BluePsyduck\ManiaScriptCollection\View\View;

/**
 * Abstract base class of the view helpers.
 *
 * @author Marcel <marcel@mania-community.de>
 * @license http://opensource.org/licenses/GPL-2.0 GPL v2
 */
abstract class AbstractHelper {
    /**
     * The current view.
     * @var \BluePsyduck\ManiaScriptCollection\View\View
     */
    protected $view;

    /**
     * Sets the current view.
     * @param \BluePsyduck\ManiaScriptCollection\View\View $view
     * @return $this Implementing fluent interface.
     */
    public function setView(View $view) {
        $this->view = $view;
        return $this;
    }

    /**
     * Default implementation of a view helper to do nothing.
     * @return string An empty string.
     */
    public function __invoke() {
        return '';
    }
}