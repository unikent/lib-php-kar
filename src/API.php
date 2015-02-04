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
    const TRAINING_URL = 'https://kar-training.kent.ac.uk';

    /**
     * URL of the Test KAR system.
     */
    const TEST_URL = 'https://kar-test.kent.ac.uk';

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

        $this->_url = $url;
    }

    /**
     * Returns the URL.
     */
    public function get_url() {
        return $this->_url;
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
     * Search KAR for a given x.
     *
     * @param string $url The URL to grab.
     * @param int $limit The maximum number of results to return.
     * @param int $offset The offset of results to return.
     */
    private function search_by_url($url, $limit = 1000, $offset = 0) {
        $query = "&limit=" . urlencode($limit) . "&offset=" . urlencode($offset);
        $json = $this->curl($this->_url . $url . $query);
        $objects = json_decode($json);

        if (!is_array($objects)) {
            return null;
        }

        foreach ($objects as $k => $v) {
            $object = Publication::create_from_api($this, $v);
            $objects[$k] = $object;
        }
        
        return $objects;
    }

    /**
     * Search KAR for a given eprintid.
     *
     * @param string $eprintid The eprint id.
     * @param int $limit The maximum number of results to return.
     * @param int $offset The offset of results to return.
     */
    public function search_by_id($eprintid, $limit = 1000, $offset = 0) {
        return $this->search_by_url("/cgi/api/search_by_id" . "?q=" . urlencode($eprintid), $limit, $offset);
    }

    /**
     * Search KAR for a given division.
     *
     * @param string $division The division id.
     * @param int $limit The maximum number of results to return.
     * @param int $offset The offset of results to return.
     */
    public function search_by_division($division, $limit = 1000, $offset = 0) {
        return $this->search_by_url("/cgi/api/search_by_division" . "?q=" . urlencode($division), $limit, $offset);
    }

    /**
     * Search KAR for a given author's email.
     *
     * @param string $email The author's email.
     * @param int $limit The maximum number of results to return.
     * @param int $offset The offset of results to return.
     */
    public function search_by_email($email, $limit = 1000, $offset = 0) {
        return $this->search_by_url("/cgi/api/search_by_email" . "?q=" . urlencode($email), $limit, $offset);
    }

    /**
     * Return all people associated with a publication.
     *
     * @internal
     * @param string $eprintid The eprint id.
     */
    public function get_people($eprintid) {
        $eprintid = urlencode($eprintid);
        $json = $this->curl($this->_url . "/cgi/api/get_people?eprintid=$eprintid");
        $objects = json_decode($json);

        if (!is_array($objects)) {
            return null;
        }

        foreach ($objects as $k => $v) {
            $object = Person::create_from_api($this, $v);
            $objects[$k] = $object;
        }
        
        return $objects;
    }

    /**
     * Return all people associated with a publication.
     * 
     * @internal
     * @param string $eprintid The eprint id.
     */
    public function get_divisions($eprintid) {
        $eprintid = urlencode($eprintid);
        $json = $this->curl($this->_url . "/cgi/api/get_divisions?eprintid=$eprintid");
        $objects = json_decode($json);

        if (!is_array($objects)) {
            return null;
        }

        return Division::create_paths_from_api($this, $objects);
    }

    /**
     * Encode a string in EPrints URL format.
     *
     * @internal
     * @param string $string The string to encode.
     */
    public function encode_string($string) {
        $string = urlencode($string);
        $string = str_replace('%', '=', $string);
        $string = str_replace('.', '=2E', $string);
        $string = str_replace('/', '=2F', $string);

        return $string;
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
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        if ($this->_timeout > 0) {
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT_MS, $this->_timeout);
            curl_setopt($ch, CURLOPT_TIMEOUT_MS, $this->_timeout);
        }

        $result = curl_exec($ch);

        if ($this->_cache !== null) {
            $this->_cache->set($url, $result);
        }

        return $result;
    }
}
