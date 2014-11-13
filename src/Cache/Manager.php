<?php

namespace BluePsyduck\ManiaScriptCollection\Cache;

use BluePsyduck\ManiaScriptCollection\Logger\Logger;
use BluePsyduck\ManiaScriptCollection\Script\Code;
use BluePsyduck\ManiaScriptCollection\Script\Script;

/**
 * The cache manager.
 *
 * @author Marcel <marcel@mania-community.de>
 * @license http://opensource.org/licenses/GPL-2.0 GPL v2
 */
class Manager {
    /**
     * The directory where the files will be saved.
     */
    const DIRECTORY = 'cache';

    /**
     * The timeout of the cache entries.
     */
    const TIMEOUT = 60;

    /**
     * Fetches the code from the cache, if available.
     * @param \BluePsyduck\ManiaScriptCollection\Script\Script $script The script.
     * @return \BluePsyduck\ManiaScriptCollection\Script\Code|null The code from the cache.
     */
    public function fetch(Script $script) {
        $result = null;
        $entry = $this->load($script);
        if (!is_null($entry) && $entry->getTime()->getTimestamp() + self::TIMEOUT >= time()) {
            $result = $this->createCodeFromEntry($entry, $script);
            Logger::getInstance()->logInfo(
                'Hit of "' . $script->getName() . '" (Cached at ' . $entry->getTime()->format('Y-m-d H:i:s') . ')',
                'Cache'
            );
        }
        return $result;
    }

    /**
     * Fetches the code from the cache, even if outdated.
     * @param \BluePsyduck\ManiaScriptCollection\Script\Script $script The script.
     * @return \BluePsyduck\ManiaScriptCollection\Script\Code|null The code from the cache.
     */
    public function fetchOutdated(Script $script) {
        $result = $this->load($script);
        if (!is_null($result)) {
            Logger::getInstance()->logInfo(
                'Outdated hit of "' . $script->getName() . '" (Cached at ' . $result->getTime()->format('Y-m-d H:i:s')
                    . ')',
                'Cache'
            );
            $result = $this->createCodeFromEntry($result, $script);
        }
        return $result;
    }

    /**
     * Loads the entry from the cache, if available.
     * @param \BluePsyduck\ManiaScriptCollection\Script\Script $script The script.
     * @return \BluePsyduck\ManiaScriptCollection\Cache\Item|null The entry from the cache.
     */
    protected function load(Script $script) {
        $result = null;
        $fileName = $this->getFileName($script);
        if (file_exists($fileName)) {
            $content = unserialize(file_get_contents($fileName));
            if ($content instanceof Item) {
                $result = $content;
            }
        }
        return $result;
    }

    /**
     * Persists the specified code in the cache.
     * @param \BluePsyduck\ManiaScriptCollection\Script\Code $code The code instance.
     * @return $this Implementing fluent interface.
     */
    public function persist(Code $code) {
        $entry = $this->createEntryFromCode($code);
        $fileName = $this->getFileName($code->getSettings());
        file_put_contents($fileName, serialize($entry));
        return $this;
    }

    /**
     * Returns the cache file name of the specified script.
     * @param \BluePsyduck\ManiaScriptCollection\Script\Script $script The script settings.
     * @return string The cache file name.
     */
    protected function getFileName(Script $script) {
        return self::DIRECTORY . DIRECTORY_SEPARATOR . md5($script->getName());
    }

    /**
     * Creates a code instance from the cache entry.
     * @param \BluePsyduck\ManiaScriptCollection\Cache\Item $entry The cache entry instance.
     * @param \BluePsyduck\ManiaScriptCollection\Script\Script $settings The script settings.
     * @return \BluePsyduck\ManiaScriptCollection\Script\Code The code instance.
     */
    protected function createCodeFromEntry(Item $entry, Script $settings) {
        $code = new Code();
        $code->setSettings($settings)
             ->setRawCode($entry->getRawCode())
             ->setPatchedCode($entry->getPatchedCode())
             ->setCompressedCode($entry->getCompressedCode())
             ->setDirectives($entry->getDirectives());
         return $code;
    }

    /**
     * Creates an entry from the specified code.
     * @param \BluePsyduck\ManiaScriptCollection\Script\Code $code The code instance.
     * @return \BluePsyduck\ManiaScriptCollection\Cache\Item The entry instance.
     */
    protected function createEntryFromCode(Code $code) {
        $entry = new Item();
        $entry->setRawCode($code->getRawCode())
              ->setPatchedCode($code->getPatchedCode())
              ->setCompressedCode($code->getCompressedCode())
              ->setDirectives($code->getDirectives());
        return $entry;
    }
}