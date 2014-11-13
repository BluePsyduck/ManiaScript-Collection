<?php

namespace BluePsyduck\ManiaScriptCollection\Builder;

/**
 * Interface of the builders to create the final output for the client.
 *
 * @author Marcel <marcel@mania-community.de>
 * @license http://opensource.org/licenses/GPL-2.0 GPL v2
 */
interface BuilderInterface {
    /**
     * Builds the content for the client.
     * @return $this Implementing fluent interface.
     */
    public function build();

    /**
     * Returns the headers to be set.
     * @return array The header lines.
     */
    public function getHeaders();

    /**
     * Returns the rendered content to be send to the client.
     * @return string The rendered content.
     */
    public function getRenderedContent();
} 