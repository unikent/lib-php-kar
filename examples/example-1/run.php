<?php
/**
 * KAR API for is-dev applications.
 *
 * @copyright  2014 Skylar Kelty <S.Kelty@kent.ac.uk>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once(dirname(__FILE__) . "/../../src/API.php");
require_once(dirname(__FILE__) . "/../../src/Person.php");
require_once(dirname(__FILE__) . "/../../src/Publication.php");

if (!isset($argv[1])) {
    die("Usage: php run.php <author email>\n");
}

$api = new \unikent\KAR\API('https://kar-test.kent.ac.uk');

$documents = $api->search_author($argv[1]);

foreach ($documents as $document) {
    echo "---------------------------------\n";
    echo $document;
}