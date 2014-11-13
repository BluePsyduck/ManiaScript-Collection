<?php

namespace BluePsyduck\ManiaScriptCollection\Logger;

/**
 * A logger class.
 *
 * @author Marcel <marcel@mania-community.de>
 * @license http://opensource.org/licenses/GPL-2.0 GPL v2
 */
class Logger {
    /**
     * The singleton instance.
     * @var \BluePsyduck\ManiaScriptCollection\Logger\Logger
     */
    private static $instance;

    /**
     * Returns the singleton instance of the logger.
     * @return \BluePsyduck\ManiaScriptCollection\Logger\Logger The logger instance.
     */
    public static function getInstance() {
        if (is_null(self::$instance)) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * The item is critical and prevents the project from delivering the result as intended.
     */
    const LEVEL_CRITICAL = 'critical';

    /**
     * The item is a warning, that not everything was fine, but the result will most likely work as intended.
     */
    const LEVEL_WARNING = 'warning';

    /**
     * The item is for information only.
     */
    const LEVEL_INFO = 'info';

    /**
     * The items to be logged.
     * @var \BluePsyduck\ManiaScriptCollection\Logger\Item[]
     */
    protected $items = array();

    /**
     * The order of the levels.
     * @var array
     */
    protected $levelOrder = array(
        self::LEVEL_CRITICAL,
        self::LEVEL_WARNING,
        self::LEVEL_INFO
    );

    /**
     * Logs a critical message.
     * @param string $message The message.
     * @param string $tool The tool which triggered the message.
     * @return $this Implementing fluent interface.
     */
    public function logCritical($message, $tool = '') {
        $this->log(self::LEVEL_CRITICAL, $message, $tool);
        return $this;
    }

    /**
     * Logs a warning.
     * @param string $message The message.
     * @param string $tool The tool which triggered the message.
     * @return $this Implementing fluent interface.
     */
    public function logWarning($message, $tool = '') {
        $this->log(self::LEVEL_WARNING, $message, $tool);
        return $this;
    }

    /**
     * Logs an information.
     * @param string $message The message.
     * @param string $tool The tool which triggered the message.
     * @return $this Implementing fluent interface.
     */
    public function logInfo($message, $tool = '') {
        $this->log(self::LEVEL_INFO, $message, $tool);
        return $this;
    }

    /**
     * Logs a message.
     * @param string $level The level of the message.
     * @param string $message The message.
     * @param string $tool The tool which triggered the message.
     * @return $this Implementing fluent interface.
     */
    protected function log($level, $message, $tool = '') {
        $item = new Item();
        $item->setLevel($level)
             ->setMessage($message)
             ->setTool($tool);
        $this->items[] = $item;
        return $this;
    }

    /**
     * Returns all log items matching the specified level.
     * @param string $level The log level.
     * @return \BluePsyduck\ManiaScriptCollection\Logger\Item[] The log as ManiaScript.
     */
    public function getFilteredItems($level = self::LEVEL_INFO) {
        $currentLevel = intval(array_search($level, $this->levelOrder));
        $result = array();
        foreach ($this->items as $item) {
            $itemLevel = intval(array_search($item->getLevel(), $this->levelOrder));
            if ($currentLevel >= $itemLevel) {
                $result[] = $item;
            }
        }
        return $result;
    }
}