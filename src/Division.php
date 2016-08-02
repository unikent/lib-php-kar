<?php
/**
 * KAR API for is-dev applications.
 *
 * @copyright  2014 Skylar Kelty <S.Kelty@kent.ac.uk>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
namespace unikent\KAR;

/**
 * A division, as KAR sees them.
 * This is also a doubly linked list.
 */
class Division
{
    /**
     * API.
     *
     * @internal
     *
     * @param object
     */
    private $_api;

    /**
     * Parent.
     *
     * @internal
     *
     * @param object
     */
    private $_parent;

    /**
     * Children.
     *
     * @internal
     *
     * @param object
     */
    private $_child;

    /**
     * ID.
     *
     * @internal
     *
     * @param string
     */
    private $_id;

    /**
     * Name.
     *
     * @internal
     *
     * @param string
     */
    private $_name;

    /**
     * Constructor.
     *
     * @internal
     *
     * @param object $api The API we are related to.
     */
    private function __construct($api)
    {
        $this->_api = $api;
    }

    /**
     * Create a division from a JSON object.
     *
     * @internal
     *
     * @param object $api  The API we are related to.
     * @param object $data The data.
     */
    public static function create_from_api($api, $data)
    {
        $obj = new static($api);
        $obj->_id = $data->id;
        $obj->_name = $data->name;
        $obj->_parent = null;
        $obj->_child = null;

        return $obj;
    }

    /**
     * Create a divisions path from a JSON object.
     *
     * @internal
     *
     * @param object $api  The API we are related to.
     * @param object $path The path.
     */
    private static function create_path($api, $path)
    {
        krsort($path);

        $curr = null;
        foreach ($path as $child) {
            $division = static::create_from_api($api, $child);
            if ($curr) {
                $curr->set_child($division);
            }
            $curr = $division;
        }

        return $curr;
    }

    /**
     * Create a divisions path from a JSON object.
     *
     * @internal
     *
     * @param object $api       The API we are related to.
     * @param object $divisions The data.
     */
    public static function create_paths_from_api($api, $divisions)
    {
        // Split it up into bits.
        $paths = array();
        foreach ($divisions as $division) {
            $id = $division->division;
            $pos = $division->subject;

            if (!isset($id)) {
                $paths[$id] = array();
            }

            $paths[$id][$pos] = $division;
        }

        $ret = array();
        foreach ($paths as $id => $path) {
            $path = static::create_path($api, $path);
            $ret[] = $path->get_last();
        }

        return $ret;
    }

    /**
     * Set my child.
     *
     * @internal
     */
    private function set_child($child)
    {
        $this->_child = $child;
        $child->_parent = $this;
    }

    /**
     * Return my ID.
     */
    public function get_id()
    {
        return $this->_id;
    }

    /**
     * Return my name.
     */
    public function get_name()
    {
        return $this->_name;
    }

    /**
     * Return my URL.
     */
    public function get_url()
    {
        $url = $this->_api->get_url() . '/view/divisions/';
        $id = $this->get_id();
        $url .= $this->_api->encode_string($id) . '.html';

        return $url;
    }

    /**
     * Return a textual representation of myself and my parents.
     */
    public function get_full_path()
    {
        $str = '';
        if (isset($this->_parent)) {
            $str = $this->_parent->get_full_path() . ' > ';
        }

        return $str . $this->get_name();
    }

    /**
     * Return the first element in this list.
     */
    public function get_first()
    {
        if (isset($this->_parent)) {
            return $this->_parent->get_first();
        }

        return $this;
    }

    /**
     * Return the previous element in the list.
     */
    public function get_parent()
    {
        return $this->_parent;
    }

    /**
     * Return the next element in the list.
     */
    public function get_child()
    {
        return $this->_child;
    }

    /**
     * Return the last element in this list.
     */
    public function get_last()
    {
        if (!isset($this->_child)) {
            return $this;
        }

        return $this->_child->get_last();
    }

    /**
     * To String.
     */
    public function __toString()
    {
        return $this->get_full_path();
    }
}
