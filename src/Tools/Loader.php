<?php

namespace BluePsyduck\ManiaScriptCollection\Tools;

use BluePsyduck\ManiaScriptCollection\Cache\Manager;
use BluePsyduck\ManiaScriptCollection\Logger\Logger;
use BluePsyduck\ManiaScriptCollection\Script\Code;
use Exception;
use ManiaScript\Compressor;

/**
 * Class actually loading the script codes either from cache or from the external source.
 *
 * @author Marcel <marcel@mania-community.de>
 * @license http://opensource.org/licenses/GPL-2.0 GPL v2
 */
class Loader {
    /**
     * The user agent to use on the requests.
     */
    const USER_AGENT = 'ManiaScript Collection Loader';

    /**
     * The cache manager.
     * @var \BluePsyduck\ManiaScriptCollection\Cache\Manager
     */
    protected $cacheManager;

    /**
     * The patcher.
     * @var \BluePsyduck\ManiaScriptCollection\Tools\Patcher
     */
    protected $patcher;

    /**
     * The ManiaScript compressor.
     * @var \ManiaScript\Compressor
     */
    protected $compressor;

    /**
     * The scripts to load.
     * @var \BluePsyduck\ManiaScriptCollection\Script\Script[]
     */
    protected $scriptsToLoad = array();

    /**
     * The loaded script codes.
     * @var \BluePsyduck\ManiaScriptCollection\Script\Code[]
     */
    protected $scriptCodes = array();

    /**
     * Initializes the instance.
     */
    public function __construct() {
        $this->cacheManager = new Manager();
        $this->patcher = new Patcher();
        $this->compressor = new Compressor();
    }

    /**
     * Sets the scripts to load.
     * @param \BluePsyduck\ManiaScriptCollection\Script\Script[] $scriptsToLoad The script settings.
     * @return $this Implementing fluent interface.
     */
    public function setScriptsToLoad($scriptsToLoad) {
        $this->scriptsToLoad = $scriptsToLoad;
        return $this;
    }

    /**
     * Loads the scripts.
     * \BluePsyduck\ManiaScriptCollection\
     */
    public function load() {
        $this->scriptCodes = array();
        foreach ($this->scriptsToLoad as $script) {
            $code = $this->cacheManager->fetch($script);
            if (is_null($code)) {
                $code = new Code();
                $code->setSettings($script);
                $this->request($code);

                if ($code->getRawCode()) {
                    $this->patch($code)
                         ->compress($code);

                    $this->cacheManager->persist($code);
                } else {
                    $code = $this->cacheManager->fetchOutdated($script);
                    if (is_null($code)) {
                        Logger::getInstance()->logCritical(
                            'Unable to load "' . $script->getName() . '", script must be skipped!',
                            'Loader'
                        );
                    } else {
                        Logger::getInstance()->logWarning(
                            'Using outdated version of "' . $script->getName() . '"',
                            'Loader'
                        );
                    }
                }
            }
            if (!is_null($code)) {
                $this->scriptCodes[] = $code;
            }
        }
        return $this;
    }

    /**
     * Returns the loaded script codes.
     * @return \BluePsyduck\ManiaScriptCollection\Script\Code[] The script codes.
     */
    public function getScriptCodes() {
        return $this->scriptCodes;
    }

    /**
     * Requests the code from its external source.
     * @param \BluePsyduck\ManiaScriptCollection\Script\Code $code The code instance.
     * @return $this Implementing fluent interface.
     */
    protected function request(Code $code) {
        Logger::getInstance()->logInfo(
            'Request "' . $code->getSettings()->getName() . '"',
            'Loader'
        );

        $handle = curl_init();
        try {
            curl_setopt_array($handle, array(
                CURLOPT_URL => $code->getSettings()->getSource(),
                CURLOPT_USERAGENT => self::USER_AGENT,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_FAILONERROR => true
            ));

            $response = curl_exec($handle);
            if (curl_errno($handle) > 0) {
                throw new Exception(curl_error($handle), curl_errno($handle));
            }
            if ($response) {
                $code->setRawCode($response);
            }
        } catch (Exception $e) {
            if ($handle) {
                curl_close($handle);
            }
            Logger::getInstance()->logWarning(
                'Request of "' . $code->getSettings()->getName() . '" failed: ' . $e->getMessage(),
                'Loader'
            );
        }
        return $this;
    }

    /**
     * Patches the code.
     * @param \BluePsyduck\ManiaScriptCollection\Script\Code $code The code instance.
     * @return $this Implementing fluent interface.
     */
    protected function patch(Code $code) {
        $this->patcher->setCode($code->getRawCode())
                      ->patch();
        $code->setPatchedCode($this->patcher->getPatchedCode())
             ->setDirectives($this->patcher->getDirectives());
        return $this;
    }

    /**
     * Compresses the code.
     * @param \BluePsyduck\ManiaScriptCollection\Script\Code $code The code instance.
     * @return $this Implementing fluent interface.
     */
    protected function compress(Code $code) {
        $comment = '/* ManiaScript Collection: ' . $code->getSettings()->getName() . ' */ ';

        $this->compressor->setCode($code->getPatchedCode())
                         ->compress();
        $code->setCompressedCode($comment . $this->compressor->getCompressedCode());
        return $this;
    }
}