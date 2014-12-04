<?php
/**
 * KAR API for is-dev applications.
 *
 * @copyright  2014 Skylar Kelty <S.Kelty@kent.ac.uk>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace unikent\KAR;

/**
 * A publiction, as KAR sees them.
 */
class Publication
{
    /**
     * API.
     * 
     * @internal
     * @param object
     */
    private $_api;

    /**
     * Data.
     * 
     * @internal
     * @param array
     */
    private $_data;

    /**
     * Authors.
     * 
     * @internal
     * @param array
     */
    private $_authors;

    /**
     * Editors.
     * 
     * @internal
     * @param array
     */
    private $_editors;

    /**
     * Reviewers.
     * 
     * @internal
     * @param array
     */
    private $_reviewers;

    /**
     * Funders.
     * 
     * @internal
     * @param array
     */
    private $_funders;

    /**
     * Constructor.
     *
     * @internal
     * @param object $api The API we are related to.
     */
    private function __construct($api) {
        $this->_api = $api;
        $this->_data = array();
        $this->_authors = array();
        $this->_editors = array();
        $this->_reviewers = array();
        $this->_funders = array();
    }

    /**
     * Create a publication from a JSON object.
     *
     * @internal
     * @param object $api The API we are related to.
     * @param object $data The data.
     */
    public static function create_from_api($api, $data) {
        $obj = new static($api);

        foreach ((array)$data as $k => $v) {
            if (in_array($k, array('authors', 'editors', 'reviewers', 'funders'))) {
                continue;
            }

            $obj->_data[$k] = $v;
        }

        foreach ($data->authors as $author) {
            $obj->_authors[] = Person::create_from_api($api, $author);
        }

        foreach ($data->editors as $editor) {
            $obj->_editors[] = Person::create_from_api($api, $editor);
        }

        foreach ($data->reviewers as $reviewer) {
            $obj->_reviewers[] = Person::create_from_api($api, $reviewer);
        }


        foreach ($data->funders as $funder) {
            $obj->_funders[] = $funder->name;
        }

        return $obj;
    }

    /**
     * Get all authors.
     */
    public function get_authors() {
        return $this->_authors;
    }

    /**
     * Get all editors.
     */
    public function get_editors() {
        return $this->_editors;
    }

    /**
     * Get all reviewers.
     */
    public function get_reviewers() {
        return $this->_reviewers;
    }

    /**
     * Get all funders.
     */
    public function get_funders() {
        return $this->_funders;
    }

    /**
     * Returns the publication ID.
     */
    public function get_id() {
        return $this->_data['eprintid'];
    }

    /**
     * Returns the publication title.
     */
    public function get_title() {
        return $this->_data['title'];
    }

    /**
     * Returns the publication abstract.
     */
    public function get_abstract() {
        return $this->_data['abstract'];
    }

    /**
     * Returns the publication publisher.
     */
    public function get_publisher() {
        return $this->_data['publisher'];
    }

    /**
     * Returns the publication issn.
     */
    public function get_isbn() {
        return $this->_data['isbn'];
    }

    /**
     * Returns the publication issn.
     */
    public function get_issn() {
        return $this->_data['issn'];
    }

    /**
     * Returns the publication year.
     */
    public function get_year() {
        return $this->_data['date_year'];
    }

    /**
     * Returns the publication type.
     */
    public function get_type() {
        return $this->_data['type'];
    }

    /**
     * Returns the publication's document's URL.
     */
    public function get_file_url() {
        $fileinfo = $this->_data['fileinfo'];
        if (strpos($fileinfo, ';') === false) {
            return "";
        }

        $parts = explode(';', $fileinfo);
        $filename = array_pop($fileinfo);

        return $this->_api->get_url() . "/" . $filename;
    }

    /**
     * Returns the publication's document's Type.
     */
    public function get_file_type() {
        $url = $this->get_file_url();
        if (strpos($url, '.') === false) {
            return "";
        }

        return substr($url, strpos($url, '.') + 1);
    }

    /**
     * Returns the publication URL.
     */
    public function get_url() {
        return $this->_api->get_url() . "/" . $this->get_id();
    }

    /**
     * To String.
     */
    public function __toString() {
        $str = $this->get_title() . "\n";
        $str .= "By " . implode(', ', $this->_authors) . "\n";
        $str .= "See " . $this->get_url() . "\n";

        return $str;
    }
}