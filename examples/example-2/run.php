<?php
/**
 * KAR API for is-dev applications.
 *
 * @copyright  2014 Skylar Kelty <S.Kelty@kent.ac.uk>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once(dirname(__FILE__).'/../vendor/autoload.php');

$api = new \unikent\KAR\API();

$documents = $api->search_by_email($_GET['author']);

foreach ($documents as $document) {
    echo "--------------------------------- <br/>";

    $formats = array('apa', 'harvard-university-of-kent', 'ieee-with-url', 'chicago-author-date');
    foreach ($formats as $format) {
    	echo '<strong>' . $format . '</strong><br/>' . $document->as_citation($format);
    }
}
