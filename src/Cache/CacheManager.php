<?php

namespace BluePsyduck\ManiaScriptCollection\Cache;

use BluePsyduck\ManiaScriptCollection\Script\Code;
use BluePsyduck\ManiaScriptCollection\Script\Settings;

/**
 * The cache manager.
 *
 * @author Marcel <marcel@mania-community.de>
 * @license http://opensource.org/licenses/GPL-2.0 GPL v2
 *
 * @todo Check timestamp of cache entry to trigger re-request
 */
class CacheManager {
    /**
     * The directory where the files will be saved.
     */
    const DIRECTORY = 'cache';

    /**
     * Loads the code from the cache.
     * @param Settings $script
     * @return Code|null
     */
    public function load(Settings $script) {
        $result = null;
        $fileName = $this->getFileName($script);
        if (file_exists($fileName)) {
            $content = unserialize(file_get_contents($fileName));
            if ($content instanceof Entry) {
                $result = $this->createCodeFromEntry($content, $script);
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
             ->setCompressedCode($entry->getCompressedCode());
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
              ->setCompressedCode($code->getCompressedCode());
        return $entry;
    }
}