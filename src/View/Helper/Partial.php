<?php

namespace BluePsyduck\ManiaScriptCollection\View\Helper;

use BluePsyduck\ManiaScriptCollection\View\Renderer;
use BluePsyduck\ManiaScriptCollection\View\View;

/**
 * View Helper for rendering an additional template.
 *
 * @author Marcel <marcel@mania-community.de>
 * @license http://opensource.org/licenses/GPL-2.0 GPL v2
 */
class Partial extends AbstractHelper {
    /**
     * Includes another template into the current one.
     * @param string $template The template to include.
     * @param array $additionalVariables Additional values for the view.
     * @return string The rendered template.
     */
    public function __invoke($template, $additionalVariables = array()) {
        $view = new View();
        if ($this->view instanceof $view) {
            $view->setVariables($this->view->getVariables());
        }
        $view->setTemplate($template)
             ->setVariables($additionalVariables);

        $renderer = new Renderer();
        $renderer->setView($view)
                 ->render();
        return $renderer->getRenderedContent();
    }
} 