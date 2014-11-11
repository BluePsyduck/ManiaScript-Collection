<?php

namespace BluePsyduck\ManiaScriptCollection\Cache;

use BluePsyduck\ManiaScriptCollection\Log\Logger;
use BluePsyduck\ManiaScriptCollection\Script\Code;
use BluePsyduck\ManiaScriptCollection\Script\Settings;

/**
 * The cache manager.
 *
 * @author Marcel <marcel@mania-community.de>
 * @license http://opensource.org/licenses/GPL-2.0 GPL v2
 */
class CacheManager {
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
     * @param \BluePsyduck\ManiaScriptCollection\Script\Settings $script The script.
     * @return \BluePsyduck\ManiaScriptCollection\Script\Code|null The code from the cache.
     */
    public function fetch(Settings $script) {
        $result = null;
        $entry = $this->load($script);
        if (!is_null($entry) && $entry->getTime()->getTimestamp() + self::TIMEOUT >= time()) {
            $result = $this->createCodeFromEntry($entry, $script);
            Logger::getInstance()->log(
                '[Cache] Hit of "' . $script->getName() . '"'
                    . ' (Cached at ' . $entry->getTime()->format('Y-m-d H:i:s') . ')',
                Logger::LEVEL_INFO
            );
        }
        return $result;
    }

    /**
     * Fetches the code from the cache, even if outdated.
     * @param \BluePsyduck\ManiaScriptCollection\Script\Settings $script The script.
     * @return \BluePsyduck\ManiaScriptCollection\Script\Code|null The code from the cache.
     */
    public function fetchOutdated(Settings $script) {
        $result = $this->load($script);
        if (!is_null($result)) {
            Logger::getInstance()->log(
                '[Cache] Outdated hit of "' . $script->getName() . '"'
                    . ' (Cached at ' . $result->getTime()->format('Y-m-d H:i:s') . ')',
                Logger::LEVEL_INFO
            );
            $result = $this->createCodeFromEntry($result, $script);
        }
        return $result;
    }

    /**
     * Loads the entry from the cache, if available.
     * @param \BluePsyduck\ManiaScriptCollection\Script\Settings $script The script.
     * @return \BluePsyduck\ManiaScriptCollection\Cache\Entry|null The entry from the cache.
     */
    protected function load(Settings $script) {
        $result = null;
        $fileName = $this->getFileName($script);
        if (file_exists($fileName)) {
            $content = unserialize(file_get_contents($fileName));
            if ($content instanceof Entry) {
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
     * @param \BluePsyduck\ManiaScriptCollection\Script\Settings $script The script settings.
     * @return string The cache file name.
     */
    protected function getFileName(Settings $script) {
        return self::DIRECTORY . DIRECTORY_SEPARATOR . md5($script->getName());
    }

    /**
     * Creates a code instance from the cache entry.
     * @param \BluePsyduck\ManiaScriptCollection\Cache\Entry $entry The cache entry instance.
     * @param \BluePsyduck\ManiaScriptCollection\Script\Settings $settings The script settings.
     * @return \BluePsyduck\ManiaScriptCollection\Script\Code The code instance.
     */
    protected function createCodeFromEntry(Entry $entry, Settings $settings) {
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
     * @return \BluePsyduck\ManiaScriptCollection\Cache\Entry The entry instance.
     */
    protected function createEntryFromCode(Code $code) {
        $entry = new Entry();
        $entry->setRawCode($code->getRawCode())
              ->setPatchedCode($code->getPatchedCode())
              ->setCompressedCode($code->getCompressedCode())
              ->setDirectives($code->getDirectives());
        return $entry;
    }
}