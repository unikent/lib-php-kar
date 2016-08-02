<?php
/**
 * KAR API for is-dev applications.
 *
 * @copyright  2014 Skylar Kelty <S.Kelty@kent.ac.uk>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class APITest extends PHPUnit_Framework_TestCase
{
    /**
     * Test we can pull by ID.
     */
    public function testPullID()
    {
        $api = new \unikent\KAR\API();

        $documents = $api->search_by_id(4);

        $this->assertEquals(1, count($documents));

        $document = reset($documents);

        $this->assertEquals(4, $document->get_id());
    }
}
