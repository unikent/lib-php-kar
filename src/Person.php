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
     * @internal
     * @param object $api The API we are related to.
     * @param string $firstname Firstname.
     * @param string $lastname Lastname.
     * @param string $email Email.
     */
    public function __construct($api, $firstname = '', $lastname = '', $email = '') {
        $this->_api = $api;
        $this->set_firstname($firstname);
        $this->set_lastname($lastname);
        $this->set_email($email);
    }

    /**
     * Set the person's firstname.
     * 
     * @internal
     * @param string $firstname A firstname.
     */
    public function set_firstname($firstname) {
        $this->_firstname = $firstname;
    }

    /**
     * Set the person's lastname.
     * 
     * @internal
     * @param string $firstname A lastname.
     */
    public function set_lastname($lastname) {
        $this->_lastname = $lastname;
    }

    /**
     * Set the person's email.
     * 
     * @internal
     * @param string $firstname A email.
     */
    public function set_email($email) {
        $this->_email = $email;
        $this->_publications = null;
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
     * Returns publications.
     */
    public function get_publications() {
        // We cant do anything without an email address :(
        if (empty($this->_email)) {
            return array();
        }

        // Do we have a list?
        if ($this->_publications === null) {
            $this->_publications = $this->_api->search_author($this->_email);
        }

        return $this->_publications;
    }
}
