<?php

namespace BluePsyduck\ManiaScriptCollection\Log;

/**
 * An item of the logger.
 *
 * @author Marcel <marcel@mania-community.de>
 * @license http://opensource.org/licenses/GPL-2.0 GPL v2
 */
class Item {
    /**
     * The level of the item.
     * @var string
     */
    protected $level = Logger::LEVEL_INFO;

    /**
     * The log message.
     * @var string
     */
    protected $message = '';

    /**
     * Sets the level of the item.
     * @param string $level The level.
     * @return $this Implementing fluent interface.
     */
    public function setLevel($level) {
        $this->level = $level;
        return $this;
    }

    /**
     * Returns the level of the item.
     * @return string The level.
     */
    public function getLevel() {
        return $this->level;
    }

    /**
     * Sets the log message.
     * @param string $message THe log message.
     * @return $this Implementing fluent interface.
     */
    public function setMessage($message) {
        $this->message = $message;
        return $this;
    }

    /**
     * Returns the log message.
     * @return string The log message.
     */
    public function getMessage() {
        return $this->message;
    }
}