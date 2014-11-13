<?php

namespace BluePsyduck\ManiaScriptCollection\Builder;

use BluePsyduck\ManiaScriptCollection\Script\Factory;
use BluePsyduck\ManiaScriptCollection\View\Renderer;
use BluePsyduck\ManiaScriptCollection\View\View;

/**
 * The builder for the frontend.
 *
 * @author Marcel <marcel@mania-community.de>
 * @license http://opensource.org/licenses/GPL-2.0 GPL v2
 */
class Frontend implements BuilderInterface {
    /**
     * The directory of the scripts.
     */
    const SCRIPT_DIRECTORY = 'script';

    /**
     * The script factory.
     * @var \BluePsyduck\ManiaScriptCollection\Script\Factory
     */
    protected $scriptFactory;

    /**
     * The view renderer.
     * @var \BluePsyduck\ManiaScriptCollection\View\Renderer
     */
    protected $renderer;

    /**
     * Initializes the builder.
     */
    public function __construct() {
        $this->scriptFactory = new Factory();
        $this->renderer = new Renderer();
    }

    /**
     * Builds the content for the client.
     * @return $this Implementing fluent interface.
     */
    public function build() {
        $authors = $this->getAuthors();
        $scripts = array();
        foreach ($authors as $author) {
            $scripts[$author] = $this->getScriptsOfAuthor($author);
        }
        $view = new View();
        $view->setTemplate('index.phtml')
             ->setVariable('scripts', $scripts);

        $this->renderer->setView($view)
                       ->render();
        return $this;
    }

    /**
     * Returns the headers to be set.
     * @return array The header lines.
     */
    public function getHeaders() {
        return array('Content-Type: text/html;charset=utf8');
    }

    /**
     * Returns the rendered content to be send to the client.
     * @return string The rendered content.
     */
    public function getRenderedContent() {
        return $this->renderer->getRenderedContent();
    }

    /**
     * Returns all authors known to the collection.
     * @return array The authors.
     */
    protected function getAuthors() {
        $authors = array();
        $handle = opendir(self::SCRIPT_DIRECTORY);
        while (false !== ($fileName = readdir($handle))) {
            if ($fileName !== '.' && $fileName !== '..'
                && is_dir(self::SCRIPT_DIRECTORY . DIRECTORY_SEPARATOR . $fileName)
            ) {
                $authors[] = $fileName;
            }
        }
        closedir($handle);
        sort($authors);
        return $authors;
    }

    /**
     * Returns the scripts of the specified author.
     * @param string $author The author.
     * @return array The scripts.
     */
    protected function getScriptsOfAuthor($author) {
        $scripts = array();
        $directory = self::SCRIPT_DIRECTORY . DIRECTORY_SEPARATOR . $author;
        $handle = opendir($directory);
        while (false !== ($fileName = readdir($handle))) {
            if ($fileName !== '.' && $fileName !== '..'
                && is_file($directory . DIRECTORY_SEPARATOR . $fileName)
                && substr($fileName, -5) === '.json'
            ) {
                $scriptName = substr($fileName, 0, -5);
                $scripts[$scriptName] = $this->scriptFactory->get($author . '/' . $scriptName);
            }
        }
        closedir($handle);
        ksort($scripts);
        return $scripts;
    }
}