<?php
/**
 * KAR API for is-dev applications.
 *
 * @copyright  2014 Skylar Kelty <S.Kelty@kent.ac.uk>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once(dirname(__FILE__).'/../../vendor/autoload.php');
require_once(dirname(__FILE__) . "/../../src/API.php");
require_once(dirname(__FILE__) . "/../../src/Person.php");
require_once(dirname(__FILE__) . "/../../src/Publication.php");

$reference_styles_path = dirname(__FILE__).'/../../vendor/academicpuma/citeproc-php/tests/styles/';

$api = new \unikent\KAR\API('https://kar.kent.ac.uk', $reference_styles_path);

$documents = $api->search_author($_GET['author']);

foreach ($documents as $document) {
    echo "--------------------------------- <br/>";

    foreach(array('APA','Harvard','IEEE','CHICAGO') as $format){
    	echo '<strong>'.$format.'</strong><br/>'.$document->as_citation($format);
    }
}