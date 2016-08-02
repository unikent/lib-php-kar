<?php
/**
 * KAR API for is-dev applications.
 *
 * @copyright  2014 Skylar Kelty <S.Kelty@kent.ac.uk>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
namespace unikent\KAR;

/**
 * A static cache, for when the app doesnt give us anything.
 */
class StaticCache
{
    /**
     * Store Data.
     *
     * @internal
     */
    private $_data;

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->_data = array();
    }

    /**
     * Get.
     */
    public function get($name)
    {
        return isset($this->_data[$name]) ? $this->_data[$name] : null;
    }

    /**
     * Set.
     */
    public function set($name, $value)
    {
        $this->_data[$name] = $value;
    }

    /**
     * Isset.
     */
    public function __isset($name)
    {
        return isset($this->_data[$name]);
    }
}
