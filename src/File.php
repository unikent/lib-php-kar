<?php
/**
 * KAR API for is-dev applications.
 *
 * @copyright  2015 Skylar Kelty <S.Kelty@kent.ac.uk>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace unikent\KAR;

/**
 * A file, as KAR sees them.
 */
class File
{
    /**
     * API.
     *
     * @internal
     * @param object
     */
    private $_api;

    /**
     * ID.
     *
     * @internal
     * @param string
     */
    private $_id;

    /**
     * eprintID.
     *
     * @internal
     * @param string
     */
    private $_eprintid;

    /**
     * Position.
     *
     * @internal
     * @param string
     */
    private $_pos;

    /**
     * Filename.
     *
     * @internal
     * @param string
     */
    private $_filename;

    /**
     * Mimetype.
     *
     * @internal
     * @param string
     */
    private $_mimetype;


    /**
     * Constructor.
     *
     * @internal
     * @param object $api The API we are related to.
     */
    private function __construct($api) {
        $this->_api = $api;
    }

    /**
     * Create a file from a JSON object.
     *
     * @internal
     * @param object $api The API we are related to.
     * @param object $data The data.
     */
    public static function create_from_api($api, $eprintid, $data) {
        $obj = new static($api);
        $obj->_id = $data->id;
        $obj->_eprintid = $eprintid;
        $obj->_pos = $data->pos;
        $obj->_filename = $data->filename;
        $obj->_mimetype = $data->mimetype;

        return $obj;
    }

    /**
     * Return my ID.
     */
    public function get_id() {
        return $this->_id;
    }

    /**
     * Return my position.
     */
    public function get_pos() {
        return $this->_pos;
    }

    /**
     * Return my filename.
     */
    public function get_filename() {
        return $this->_filename;
    }

    /**
     * Return my mimetype.
     */
    public function get_mimetype() {
        return $this->_mimetype;
    }

    /**
     * Return my URL.
     */
    public function get_url() {
        return $this->_api->get_file_url($this->_eprintid, $this->_pos, $this->_filename);
    }

    /**
     * toString.
     */
    public function __toString() {
        return $this->get_url();
    }
}