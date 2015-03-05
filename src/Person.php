<?php
/**
 * KAR API for is-dev applications.
 *
 * @copyright  2014 Skylar Kelty <S.Kelty@kent.ac.uk>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace unikent\KAR;

/**
 * A person, as KAR sees them.
 */
class Person
{
    /**
     * API.
     * 
     * @internal
     * @param object
     */
    private $_api;

    /**
     * First name.
     * 
     * @internal
     * @param string
     */
    private $_firstname;

    /**
     * Surname.
     * 
     * @internal
     * @param string
     */
    private $_lastname;

    /**
     * Email.
     * 
     * @internal
     * @param string
     */
    private $_email;

    /**
     * Publications (if grabbed).
     * 
     * @internal
     * @param array
     */
    private $_publications;

    /**
     * Constructor.
     *
     * @param object $api The API we are related to.
     * @param string $firstname The firstname.
     * @param string $lastname The lastname.
     * @param string $email The email.
     */
    public function __construct($api, $firstname, $lastname, $email) {
        $this->_api = $api;
        $this->_firstname = $firstname;
        $this->_lastname = $lastname;
        $this->_email = $email;
        $this->_publications = null;
    }

    /**
     * Create a person from a JSON object.
     *
     * @internal
     * @param object $api The API we are related to.
     * @param object $data The data.
     */
    public static function create_from_api($api, $data) {
        static $cache = array();

        if (!isset($cache[$data->email])) {
            $person = new static($api, $data->given_name, $data->family_name, $data->email);
            $cache[$data->email] = $person;
        }

        return $cache[$data->email];
    }

    /**
     * Returns the person's firstname.
     */
    public function get_firstname() {
        return $this->_firstname;
    }

    /**
     * Returns the person's lastname.
     */
    public function get_lastname() {
        return $this->_lastname;
    }

    /**
     * Returns the person's email.
     */
    public function get_email() {
        return $this->_email;
    }

    /**
     * Returns the URL for this person.
     */
    public function get_url() {
        return $this->_api->get_person_url($this->_email);
    }

    /**
     * Returns publications.
     */
    public function get_publications() {
        // We cant do anything without an email address :(
        if (empty($this->_email)) {
            return array();
        }

        // Do we have a list?
        if ($this->_publications === null) {
            $this->_publications = $this->_api->search_by_email($this->_email);
        }

        return $this->_publications;
    }

    /**
     * To String.
     */
    public function __toString() {
        return $this->get_firstname() . " " . $this->get_lastname();
    }
}
