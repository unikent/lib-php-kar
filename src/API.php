<?php
/**
 * KAR API for is-dev applications.
 *
 * @copyright  2014 Skylar Kelty <S.Kelty@kent.ac.uk>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace unikent\KAR;

/**
 * KAR API.
 * 
 * @example ../examples/example-1/run.php How to grab an author's documents from KAR.
 */
class API
{
    /**
     * URL of the Live KAR system.
     */
    const LIVE_URL = 'https://kar.kent.ac.uk';

    /**
     * URL of the Training KAR system.
     */
    const TRAINING_URL = 'https://kar.kent.ac.uk';

    /**
     * API Endpoint URL.
     * This uses version 2.
     */
    const API_ENDPOINT = '/cgi/api/search';

    /**
     * CURL Timeout.
     * 
     * @internal
     * @var int
     */
    private $_timeout;

    /**
     * URL of KAR.
     *
     * @internal
     * @var string
     */
    private $_url;

    /**
     * A Cache Layer.
     * 
     * @internal
     * @var mixed
     */
    private $_cache;

    /**
     * Constructor.
     *
     * @param string $url Which KAR installation do you want? (just say null)
     */
    public function __construct($url = null) {
        $this->set_url($url);
        $this->set_timeout(0);
    }

    /**
     * Set URL.
     * 
     * @param string $url Which KAR installation do you want? (just say null)
     */
    public function set_url($url = null) {
        if ($url === null) {
            $url = static::LIVE_URL;
        }

        $this->_url = $url . static::API_ENDPOINT;
    }

    /**
     * Set a custom CURL timeout
     *
     * @param int $timeout CURL Timeout in ms
     */
    public function set_timeout($timeout) {
        $this->_timeout = $timeout;
    }

    /**
     * Search KAR for a given author's emails.
     *
     * @param string $email The 
     */
    public function search_author($email) {
        $results = $this->curl($this->_url . "?q=" . urlencode($email));
        return json_decode($results);
    }

    /**
     * Set a cache object.
     * This API expects it can call "set($key, $value)" and "get($key)" and wont try to do anything else.
     *
     * @param object $cache An object with get and set methods.
     */
    public function set_cache_layer($cache) {
        if (!method_exists($cache, 'set') || !method_exists($cache, 'get')) {
            throw new \Exception("Invalid cache layer - must have set and get.");
        }

        $this->_cache = $cache;
    }

    /**
     * CURL shorthand.
     *
     * @internal
     * @param string $url The URL to curl.
     */
    protected function curl($url) {
        if ($this->_cache !== null) {
            $v = $this->_cache->get($url);
            if ($v) {
                return $v;
            }
        }

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL,            $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HEADER,         false);
        curl_setopt($ch, CURLOPT_HTTP_VERSION,   CURL_HTTP_VERSION_1_1);
        curl_setopt($ch, CURLOPT_HTTPHEADER,     array('Content-Type: text/plain'));

        if ($this->_timeout > 0) {
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $this->_timeout);
            curl_setopt($ch, CURLOPT_TIMEOUT, $this->_timeout);
        }

        $result = curl_exec($ch);

        if ($this->_cache !== null) {
            $this->_cache->set($url, $result);
        }

        return $result;
    }
}