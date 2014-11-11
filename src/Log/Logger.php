<?php

namespace BluePsyduck\ManiaScriptCollection\Log;

/**
 * A logger class.
 *
 * @author Marcel <marcel@mania-community.de>
 * @license http://opensource.org/licenses/GPL-2.0 GPL v2
 */
class Logger {
    /**
     * The singleton instance.
     * @var \BluePsyduck\ManiaScriptCollection\Log\Logger
     */
    private static $instance;

    /**
     * Returns the singleton instance of the logger.
     * @return \BluePsyduck\ManiaScriptCollection\Log\Logger The logger instance.
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
     * @var \BluePsyduck\ManiaScriptCollection\Log\Item[]
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
     * Logs a message.
     * @param string $message The message.
     * @param string $level The level of the message.
     * @return $this Implementing fluent interface.
     */
    public function log($message, $level = self::LEVEL_INFO) {
        $item = new Item();
        $item->setLevel($level)
             ->setMessage($message);
        $this->items[] = $item;
        return $this;
    }

    /**
     * Returns all log items matching the specified level.
     * @param string $level The log level.
     * @return \BluePsyduck\ManiaScriptCollection\Log\Item[] The log as ManiaScript.
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