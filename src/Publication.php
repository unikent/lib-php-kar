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
     * People.
     * 
     * @internal
     * @param array
     */
    private $_people;

    /**
     * Divisions.
     * 
     * @internal
     * @param array
     */
    private $_divisions;

    /**
     * Constructor.
     *
     * @internal
     * @param object $api The API we are related to.
     */
    private function __construct($api) {
        $this->_api = $api;
        $this->_data = array();
        $this->_people = null;
        $this->_divisions = null;
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
        $obj->_data = (array)$data;
        return $obj;
    }

    /**
     * Get all people associated with this publication.
     */
    private function build_people_cache() {
        if ($this->_people !== null) {
            return;
        }

        $eprintid = $this->get_id();
        if (empty($eprintid)) {
            return;
        }

        $this->_people = array(
            'creator' => array(),
            'editor' => array(),
            'reviewer' => array(),
            'funder' => array()
        );

        // Grab people from the API.
        $types = $this->_api->get_people($eprintid);
        foreach ($types as $type => $people) {
            $this->_people[$type] = array();
            foreach ($people as $person) {
                $this->_people[$type][] = $person;
            }
        }
    }

    /**
     * Get all authors.
     */
    public function get_authors() {
        $this->build_people_cache();
        return $this->_people['creator'];
    }

    /**
     * Get all editors.
     */
    public function get_editors() {
        $this->build_people_cache();
        return $this->_people['editor'];
    }

    /**
     * Get all reviewers.
     */
    public function get_reviewers() {
        $this->build_people_cache();
        return $this->_people['reviewer'];
    }

    /**
     * Get all funders.
     */
    public function get_funders() {
        $this->build_people_cache();
        return $this->_people['funder'];
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
     * Returns the publication subtitle.
     */
    public function get_subtitle() {
        return $this->_data['subtitle'];
    }

    /**
     * Returns the publication abstract.
     */
    public function get_abstract() {
        return $this->_data['abstract'];
    }

    /**
     * Returns the publication.
     */
    public function get_publication() {
        return $this->_data['publication'];
    }

    /**
     * Returns the volume.
     */
    public function get_volume() {
        return $this->_data['volume'];
    }

    /**
     * Returns the number.
     */
    public function get_number() {
        return $this->_data['number'];
    }

    /**
     * Returns the page range.
     */
    public function get_page_range() {
        return $this->_data['pagerange'];
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
     * Returns the pretty type.
     */
    public function get_pretty_type() {
        static $map = array(
            'book_section' => 'Book section',
            'conference_item' => 'Conference or workshop item',
            'dataset' => 'Datasets / databases',
            'edbook' => 'Edited book',
            'exhibition' => 'Show / exhibition',
            'internet' => 'Internet publication',
            'monograph' => 'Monograph',
            'other' => 'Other',
            'patent' => 'Patent',
            'performance' => 'Performance',
            'research_report' => 'Research report (external)',
            'review' => 'Review',
            'scholarlyed' => 'Scholarly edition',
            'thesis' => 'Thesis',
            'video' => 'Visual media'
        );

        $type = $this->get_type();
        $type = str_replace('\\', '', $type);

        if (isset($map[$type])) {
            return $map[$type];
        }

        $type = str_replace('_', ' ', $type);
        return ucwords($type);
    }

    /**
     * Returns the publication location.
     */
    public function get_location() {
        return $this->_data['location'];
    }

    /**
     * Returns the id number.
     */
    public function get_id_number() {
        return $this->_data['id_number'];
    }

    /**
     * Returns the book title.
     */
    public function get_book_title() {
        return $this->_data['book_title'];
    }

    /**
     * Returns the series.
     */
    public function get_series() {
        return $this->_data['series'];
    }

    /**
     * Returns the place of publication.
     */
    public function get_place_of_pub() {
        return $this->_data['place_of_pub'];
    }

    /**
     * Returns the pages.
     */
    public function get_pages() {
        return $this->_data['pages'];
    }

    /**
     * Returns the event title.
     */
    public function get_event_title() {
        return $this->_data['event_title'];
    }

    /**
     * Returns the event dates.
     */
    public function get_event_dates() {
        return $this->_data['event_dates'];
    }

    /**
     * Returns the event location.
     */
    public function get_event_location() {
        return $this->_data['event_location'];
    }

    /**
     * Returns the monograph type.
     */
    public function get_monograph_type() {
        return $this->_data['monograph_type'];
    }

    /**
     * Returns the output media.
     */
    public function get_output_media() {
        return $this->_data['output_media'];
    }

    /**
     * Returns the size.
     */
    public function get_size() {
        return $this->_data['size'];
    }

    /**
     * Returns the performance type.
     */
    public function get_performance_type() {
        return $this->_data['performance_type'];
    }

    /**
     * Returns the num pieces.
     */
    public function get_num_pieces() {
        return $this->_data['num_pieces'];
    }

    /**
     * Returns the manufacturer.
     */
    public function get_manufacturer() {
        return $this->_data['manufacturer'];
    }

    /**
     * Returns the thesis type.
     */
    public function get_thesis_type() {
        return $this->_data['thesis_type'];
    }

    /**
     * Is it published?
     */
    public function get_ispublished() {
        return $this->_data['ispublished'];
    }

    /**
     * Returns the full text status.
     */
    public function get_full_text_status() {
        return $this->_data['full_text_status'];
    }

    /**
     * Returns the institution.
     */
    public function get_institution() {
        return $this->_data['institution'];
    }

    /**
     * Returns the patent applicant.
     */
    public function get_patent_applicant() {
        return $this->_data['patent_applicant'];
    }

    /**
     * Returns the reviewed item.
     */
    public function get_reviewed_item() {
        return $this->_data['reviewed_item'];
    }

    /**
     * Returns the official url.
     */
    public function get_official_url() {
        return $this->_data['official_url'];
    }

    /**
     * Is it published?
     */
    public function is_published() {
        return $this->get_ispublished() == 'pub';
    }

    /**
     * Get all divisions associated with this publication.
     */
    private function build_division_cache() {
        if ($this->_divisions !== null) {
            return;
        }

        $eprintid = $this->get_id();
        if (empty($eprintid)) {
            return;
        }

        // Grab divisions from the API.
        $this->_divisions = $this->_api->get_divisions($eprintid);
    }

    /**
     * Returns divisions this item belongs to.
     */
    public function get_divisions() {
        $this->build_division_cache();
        return $this->_divisions;
    }

    /**
     * Returns the publication's document's file name.
     */
    public function get_filename() {
        $fileinfo = $this->_data['fileinfo'];
        if (strpos($fileinfo, ';') === false) {
            return "";
        }

        $parts = explode(';', $fileinfo);
        $filename = array_pop($parts);

        return $filename;
    }

    /**
     * Returns the publication's document's URL.
     */
    public function get_file_url() {
        return $this->_api->get_url() . "/" . $this->get_filename();
    }

    /**
     * Returns the publication's document's Type.
     */
    public function get_file_type() {
        $filename = $this->get_filename();
        if (strpos($filename, '.') === false) {
            // Try and guess based on fileinfo.
            $fileinfo = $this->_data['fileinfo'];

            if (strpos($fileinfo, 'application_pdf') !== false) {
                return "pdf";
            }

            if (strpos($fileinfo, 'application_msword') !== false) {
                return "docx";
            }

            if (strpos($fileinfo, 'application_postscript') !== false) {
                return "ps";
            }

            if (strpos($fileinfo, 'ms-powerpoint') !== false) {
                return "pptx";
            }

            return "";
        }

        $filetype = substr($filename, strrpos($filename, '.') + 1);
        return strtolower($filetype);
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
        $authors = $this->get_authors();
        if (!empty($authors)) {
            $str .= "By " . implode(', ', $authors) . "\n";
        }
        $str .= "See " . $this->get_url();

        return $str;
    }

    /**
     * Return formatted citation in given reference style.
     *
     * @param string $csl The name of the csl file to use.
     */
    public function as_citation($csl = 'apa') {
        // Get parser for this citation format.
        $parser = $this->get_citeproc_parser($csl);

        // Return formatted citation.
        return $parser->render($this->get_for_citeproc());
    }

    /**
     * Format for CiteProc.
     * 
     * @internal
     */
    protected function get_for_citeproc() {
        // Format data in order to build
        $publication = new \stdClass();
        
        // Add basic params to pub object
        $publication->id = htmlentities($this->get_id(), ENT_QUOTES | ENT_HTML5 | ENT_SUBSTITUTE , 'UTF-8', false);
        $publication->type = htmlentities($this->get_citeproc_type(), ENT_QUOTES | ENT_HTML5 | ENT_SUBSTITUTE , 'UTF-8', false);

        $publication->DOI = htmlentities($this->get_id_number(), ENT_QUOTES | ENT_HTML5 | ENT_SUBSTITUTE , 'UTF-8', false);
        $publication->ISSN = htmlentities($this->get_issn(), ENT_QUOTES | ENT_HTML5 | ENT_SUBSTITUTE , 'UTF-8', false);
        $publication->ISBN = htmlentities($this->get_isbn(), ENT_QUOTES | ENT_HTML5 | ENT_SUBSTITUTE , 'UTF-8', false);

     
        $publication->abstract = htmlentities($this->get_abstract(), ENT_QUOTES | ENT_HTML5 | ENT_SUBSTITUTE , 'UTF-8', false);
        $publication->number = htmlentities($this->get_number(), ENT_QUOTES | ENT_HTML5 | ENT_SUBSTITUTE , 'UTF-8', false);
        $publication->page = htmlentities($this->get_page_range(), ENT_QUOTES | ENT_HTML5 | ENT_SUBSTITUTE , 'UTF-8', false);

        $publication->publisher = htmlentities($this->get_publisher(), ENT_QUOTES | ENT_HTML5 | ENT_SUBSTITUTE , 'UTF-8', false);
        $publication->{"publisher-place"} = htmlentities($this->get_place_of_pub(), ENT_QUOTES | ENT_HTML5 | ENT_SUBSTITUTE , 'UTF-8', false);

        $publication->title = htmlentities($this->get_title(), ENT_QUOTES | ENT_HTML5 | ENT_SUBSTITUTE , 'UTF-8', false);
        $publication->URL = htmlentities($this->get_official_url(), ENT_QUOTES | ENT_HTML5 | ENT_SUBSTITUTE , 'UTF-8', false);

        $publication->volume = htmlentities($this->get_volume(), ENT_QUOTES | ENT_HTML5 | ENT_SUBSTITUTE , 'UTF-8', false);
        $publication->issued = (object) array(
            "date-parts" => array(array(htmlentities($this->get_year(), ENT_QUOTES | ENT_HTML5 | ENT_SUBSTITUTE , 'UTF-8', false))),
            "literal" => htmlentities($this->get_year(), ENT_QUOTES | ENT_HTML5 | ENT_SUBSTITUTE , 'UTF-8', false)
        );

        $publication->event = htmlentities($this->get_event_title(), ENT_QUOTES | ENT_HTML5 | ENT_SUBSTITUTE , 'UTF-8', false);
        $publication->{"event-date"} = htmlentities($this->get_event_dates(), ENT_QUOTES | ENT_HTML5 | ENT_SUBSTITUTE , 'UTF-8', false);
        $publication->{"event-place"} = htmlentities($this->get_event_location(), ENT_QUOTES | ENT_HTML5 | ENT_SUBSTITUTE , 'UTF-8', false);

        $publication->medium = htmlentities($this->get_output_media(), ENT_QUOTES | ENT_HTML5 | ENT_SUBSTITUTE , 'UTF-8', false);

        $publication->performance_type = htmlentities($this->get_performance_type(), ENT_QUOTES | ENT_HTML5 | ENT_SUBSTITUTE , 'UTF-8', false);

        $publication->{"container-title"} = htmlentities($this->get_book_title(), ENT_QUOTES | ENT_HTML5 | ENT_SUBSTITUTE , 'UTF-8', false);

        $publication->{"number-of-pages"} = htmlentities($this->get_pages(), ENT_QUOTES | ENT_HTML5 | ENT_SUBSTITUTE , 'UTF-8', false);

        // Convert author & editor fields
        $publication->author = array();
        $publication->editor = array();

        foreach ($this->get_authors() as $author) {
            $record = array(
                "given" => htmlentities($author->get_firstname(), ENT_QUOTES | ENT_HTML5 | ENT_SUBSTITUTE , 'UTF-8', false),
                "family" => htmlentities($author->get_lastname(), ENT_QUOTES | ENT_HTML5 | ENT_SUBSTITUTE , 'UTF-8', false)
            );
            $publication->author[] = (object)$record;
        }

        foreach ($this->get_editors() as $editor) {
            $record = array(
                "given" => htmlentities($editor->get_firstname(), ENT_QUOTES | ENT_HTML5 | ENT_SUBSTITUTE , 'UTF-8', false),
                "family" => htmlentities($editor->get_lastname(), ENT_QUOTES | ENT_HTML5 | ENT_SUBSTITUTE , 'UTF-8', false)
            );
            $publication->editor[] = (object)$record;
        }

        // Currently unused fields - left blank.
        $publication->{"citation-label"} = '';
       
        $publication->documents = array();
        $publication->edition = '';
        $publication->issue = '';
        $publication->note = '';
        return $publication;
    }


    /**
     * Converts KAR type to CiteProc type.
     * 
     * @return array Mappings.
     */
    public function get_citeproc_type() {
        $kartype = $this->get_type();

        switch ($kartype) {
            // Unsure.
            case 'artefact':
            case 'exhibition':
            case 'software':
            case 'scholarlyed':
                return "article";
            case 'audio':
                return "speech";
            case 'performance':
                return "song";
            case 'monograph':
                return "thesis";
            case 'design':
                return "figure";

            // "Probably" right.
            case 'article':
                return "article";
            case 'book':
            case 'edbook':
                return "book";
            case 'book_section';
                return "chapter";
            case 'composition':
                return "musical_score";
            case 'conference_item':
                return "paper-conference";
            case 'confidential_report':
                return "report";
            case 'dataset':
                return "dataset";
            case 'image':
                return "graphic";
            case 'internet':
                return "webpage";
            case 'patent':
                return "patent";
            case 'research_report':
                return "report";
            case 'review':
                return "review";
            case 'thesis':
                return "thesis";
            case 'video':
                return "motion_picture";

            // Return article as the default.
            case 'other':
            default:
                return "article";
        }
    }

    /**
     * Format for CiteProc
     * 
     * @internal
     * @param string $csl - reference format csl APA/IEEE etc
     * @return object $parser - Parser for given reference format
     */
    protected function get_citeproc_parser($csl) {
        static $parsers = array();

        if (!isset($parsers[$csl])) {
            $safecsl = preg_replace("([^a-z0-9\-])", '', $csl);

            $filename = dirname(__FILE__) . "/csls/" . $safecsl . ".csl";
            if (!file_exists($filename)) {
                throw new \Exception("Invalid CSL: " . $csl);
            }

            $cslcontent = file_get_contents($filename);
            $parsers[$csl] = new \academicpuma\citeproc\CiteProc($cslcontent);
        }

        return $parsers[$csl];
    }
}
