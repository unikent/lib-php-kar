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
     *
     * @param object
     */
    private $_api;

    /**
     * Data.
     *
     * @internal
     *
     * @param array
     */
    private $_data;

    /**
     * People.
     *
     * @internal
     *
     * @param array
     */
    private $_people;

    /**
     * Divisions.
     *
     * @internal
     *
     * @param array
     */
    private $_divisions;

    /**
     * Files.
     *
     * @internal
     *
     * @param array
     */
    private $_files;

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
        $this->_data = array();
        $this->_people = null;
        $this->_divisions = null;
        $this->_files = null;
    }

    /**
     * Create a publication from a JSON object.
     *
     * @internal
     *
     * @param object $api  The API we are related to.
     * @param object $data The data.
     */
    public static function create_from_api($api, $data)
    {
        $obj = new static($api);
        $obj->_data = (array) $data;

        return $obj;
    }

    /**
     * Get all people associated with this publication.
     */
    private function build_people_cache()
    {
        if ($this->_people !== null) {
            return;
        }

        $eprintid = $this->get_id();
        if (empty($eprintid)) {
            return;
        }

        $this->_people = array(
            'creator'  => array(),
            'editor'   => array(),
            'reviewer' => array(),
            'funder'   => array(),
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
    public function get_authors()
    {
        $this->build_people_cache();

        return $this->_people['creator'];
    }

    /**
     * Get all editors.
     */
    public function get_editors()
    {
        $this->build_people_cache();

        return $this->_people['editor'];
    }

    /**
     * Get all reviewers.
     */
    public function get_reviewers()
    {
        $this->build_people_cache();

        return $this->_people['reviewer'];
    }

    /**
     * Get all funders.
     */
    public function get_funders()
    {
        $this->build_people_cache();

        return $this->_people['funder'];
    }

    /**
     * Returns the publication ID.
     */
    public function get_id()
    {
        return $this->_data['eprintid'];
    }

    /**
     * Returns the publication title.
     */
    public function get_title()
    {
        return $this->_data['title'];
    }

    /**
     * Returns the publication subtitle.
     */
    public function get_subtitle()
    {
        return $this->_data['subtitle'];
    }

    /**
     * Returns the publication abstract.
     */
    public function get_abstract()
    {
        return $this->_data['abstract'];
    }

    /**
     * Returns the publication.
     */
    public function get_publication()
    {
        return $this->_data['publication'];
    }

    /**
     * Returns the volume.
     */
    public function get_volume()
    {
        return $this->_data['volume'];
    }

    /**
     * Returns the number.
     */
    public function get_number()
    {
        return $this->_data['number'];
    }

    /**
     * Returns the page range.
     */
    public function get_page_range()
    {
        return $this->_data['pagerange'];
    }

    /**
     * Returns the publication publisher.
     */
    public function get_publisher()
    {
        return $this->_data['publisher'];
    }

    /**
     * Returns the publication issn.
     */
    public function get_isbn()
    {
        return $this->_data['isbn'];
    }

    /**
     * Returns the publication issn.
     */
    public function get_issn()
    {
        return $this->_data['issn'];
    }

    /**
     * Returns the publication year.
     */
    public function get_year()
    {
        return $this->_data['date_year'];
    }

    /**
     * Returns the publication type.
     */
    public function get_type()
    {
        return $this->_data['type'];
    }

    /**
     * Returns an array of valid types.
     */
    public static function get_valid_types()
    {
        static $map = array(
            'article'             => 'Article',
            'book_section'        => 'Book section',
            'conference_item'     => 'Conference or workshop item',
            'dataset'             => 'Datasets / databases',
            'edbook'              => 'Edited book',
            'book'                => 'Book',
            'exhibition'          => 'Show / exhibition',
            'internet'            => 'Internet publication',
            'monograph'           => 'Monograph',
            'other'               => 'Other',
            'patent'              => 'Patent',
            'performance'         => 'Performance',
            'research_report'     => 'Research report (external)',
            'review'              => 'Review',
            'scholarlyed'         => 'Scholarly edition',
            'thesis'              => 'Thesis',
            'video'               => 'Visual media',
            'artefact'            => 'Artefact',
            'composition'         => 'Composition',
            'image'               => 'Image',
            'audio'               => 'Audio',
            'experiment'          => 'Experiment',
            'confidential_report' => 'Confidential report',
            'design'              => 'Design',
            'device'              => 'Device',
            'edjournal'           => 'Edited journal',
            'software'            => 'Software',
        );

        return $map;
    }

    /**
     * Returns the pretty type.
     */
    public function get_pretty_type()
    {
        $map = static::get_valid_types();

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
    public function get_location()
    {
        return $this->_data['location'];
    }

    /**
     * Returns the id number.
     */
    public function get_id_number()
    {
        return $this->_data['id_number'];
    }

    /**
     * Returns the book title.
     */
    public function get_book_title()
    {
        return $this->_data['book_title'];
    }

    /**
     * Returns the series.
     */
    public function get_series()
    {
        return $this->_data['series'];
    }

    /**
     * Returns the place of publication.
     */
    public function get_place_of_pub()
    {
        return $this->_data['place_of_pub'];
    }

    /**
     * Returns the pages.
     */
    public function get_pages()
    {
        return $this->_data['pages'];
    }

    /**
     * Returns the event title.
     */
    public function get_event_title()
    {
        return $this->_data['event_title'];
    }

    /**
     * Returns the event dates.
     */
    public function get_event_dates()
    {
        return $this->_data['event_dates'];
    }

    /**
     * Returns the event location.
     */
    public function get_event_location()
    {
        return $this->_data['event_location'];
    }

    /**
     * Returns the monograph type.
     */
    public function get_monograph_type()
    {
        return $this->_data['monograph_type'];
    }

    /**
     * Returns the output media.
     */
    public function get_output_media()
    {
        return $this->_data['output_media'];
    }

    /**
     * Returns the size.
     */
    public function get_size()
    {
        return $this->_data['size'];
    }

    /**
     * Returns the performance type.
     */
    public function get_performance_type()
    {
        return $this->_data['performance_type'];
    }

    /**
     * Returns the num pieces.
     */
    public function get_num_pieces()
    {
        return $this->_data['num_pieces'];
    }

    /**
     * Returns the manufacturer.
     */
    public function get_manufacturer()
    {
        return $this->_data['manufacturer'];
    }

    /**
     * Returns the thesis type.
     */
    public function get_thesis_type()
    {
        return $this->_data['thesis_type'];
    }

    /**
     * Is it published?
     */
    public function get_ispublished()
    {
        return $this->_data['ispublished'];
    }

    /**
     * Returns the full text status.
     */
    public function get_full_text_status()
    {
        return $this->_data['full_text_status'];
    }

    /**
     * Returns the institution.
     */
    public function get_institution()
    {
        return $this->_data['institution'];
    }

    /**
     * Returns the patent applicant.
     */
    public function get_patent_applicant()
    {
        return $this->_data['patent_applicant'];
    }

    /**
     * Returns the reviewed item.
     */
    public function get_reviewed_item()
    {
        return $this->_data['reviewed_item'];
    }

    /**
     * Returns the official url.
     */
    public function get_official_url()
    {
        return $this->_data['official_url'];
    }

    /**
     * Is it published?
     */
    public function is_published()
    {
        return $this->get_ispublished() == 'pub';
    }

    /**
     * Get all divisions associated with this publication.
     */
    private function build_division_cache()
    {
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
    public function get_divisions()
    {
        $this->build_division_cache();

        return $this->_divisions;
    }

    /**
     * Get all files associated with this publication.
     */
    private function build_file_cache()
    {
        if ($this->_files !== null) {
            return;
        }

        $eprintid = $this->get_id();
        if (empty($eprintid)) {
            return;
        }

        // Grab files from the API.
        $this->_files = $this->_api->get_files($eprintid);
    }

    /**
     * Returns files this item belongs to.
     */
    public function get_files()
    {
        $this->build_file_cache();

        return $this->_files;
    }

    /**
     * Returns the publication's document's file name.
     *
     * @deprecated 3.0 See get_files()
     */
    public function get_filename()
    {
        error_log('\\unikent\\KAR\\Publication::get_filename is deprecated - please use get_files instead.');
        $files = $this->get_files();
        if (empty($files)) {
            return;
        }

        $file = reset($files);

        return '/' . $this->get_id() . '/' . $file->get_pos() . '/' . $this->_api->encode_string($file->get_filename());
    }

    /**
     * Returns the publication's document's URL.
     *
     * @deprecated 3.0 See File::get_url()
     */
    public function get_file_url()
    {
        error_log('\\unikent\\KAR\\Publication::get_file_url is deprecated - please use File API instead.');

        $files = $this->get_files();
        if (empty($files)) {
            return;
        }

        $file = reset($files);

        return $file->get_url();
    }

    /**
     * Returns the publication's document's Type.
     *
     * @deprecated 3.0 See File::get_url()
     */
    public function get_file_type()
    {
        error_log('\\unikent\\KAR\\Publication::get_file_type is deprecated - please use File API instead.');

        $files = $this->get_files();
        if (empty($files)) {
            return;
        }

        $file = reset($files);

        return $file->get_mimetype();
    }

    /**
     * Returns the publication URL.
     */
    public function get_url()
    {
        return $this->_api->get_url() . '/' . $this->get_id();
    }

    /**
     * To String.
     */
    public function __toString()
    {
        $str = $this->get_title() . "\n";
        $authors = $this->get_authors();
        if (!empty($authors)) {
            $str .= 'By ' . implode(', ', $authors) . "\n";
        }
        $str .= 'See ' . $this->get_url();

        return $str;
    }

    /**
     * Return formatted citation in given reference style.
     *
     * @param string $csl The name of the csl file to use.
     */
    public function as_citation($csl = 'apa')
    {
        // Get parser for this citation format.
        $parser = $this->get_citeproc_parser($csl);

        // Return formatted citation.
        return static::tidy_citation($parser->render($this->get_for_citeproc($csl)));
    }

    /**
     * Encode for citeproc.
     */
    protected function encode_for_citeproc($string) {
        return $string;
    }

    /**
     * Format for CiteProc.
     *
     * @internal
     */
    protected function get_for_citeproc($csl)
    {
        // Format data in order to build
        $publication = new \stdClass();

        // Add basic params to pub object
        $publication->id = $this->encode_for_citeproc($this->get_id());
        $publication->type = $this->encode_for_citeproc($this->get_citeproc_type($csl));

        $publication->DOI = $this->encode_for_citeproc($this->get_id_number());
        $publication->ISSN = $this->encode_for_citeproc($this->get_issn());
        $publication->ISBN = $this->encode_for_citeproc($this->get_isbn());

        $publication->abstract = $this->encode_for_citeproc($this->get_abstract());
        $publication->number = $this->encode_for_citeproc($this->get_number());
        $publication->page = $this->encode_for_citeproc($this->get_page_range());

        $publication->publisher = $this->encode_for_citeproc($this->get_publisher());
        $publication->{'publisher-place'} = $this->encode_for_citeproc($this->get_place_of_pub());

        $publication->title = $this->encode_for_citeproc($this->get_title());
        $publication->URL = $this->encode_for_citeproc($this->get_official_url());

        $publication->volume = $this->encode_for_citeproc($this->get_volume());
        $publication->issued = (object) array(
            'date-parts' => array(array($this->encode_for_citeproc($this->get_year()))),
            'literal'    => $this->encode_for_citeproc($this->get_year()),
        );

        $publication->event = $this->encode_for_citeproc($this->get_event_title());
        $publication->{'event-date'} = $this->encode_for_citeproc($this->get_event_dates());
        $publication->{'event-place'} = $this->encode_for_citeproc($this->get_event_location());

        $publication->medium = $this->encode_for_citeproc($this->get_output_media());

        $publication->performance_type = $this->encode_for_citeproc($this->get_performance_type());

        // Articles & reports should use "publication" rather than book title
        if (in_array($publication->type, array('article', 'article-journal', 'report', 'webpage', 'review'))) {
            $publication->{'container-title'} = $this->encode_for_citeproc($this->get_publication());
        } elseif ($publication->type == 'paper-conference') {
            $publication->{'container-title'} = $this->encode_for_citeproc($this->get_event_title());
        } else {
            $publication->{'container-title'} = $this->encode_for_citeproc($this->get_book_title());
        }

        $publication->{'number-of-pages'} = $this->encode_for_citeproc($this->get_pages());

        // Convert author & editor fields
        $publication->author = array();
        $publication->editor = array();

        foreach ($this->get_authors() as $author) {
            $firstname = ($csl !== 'chicago-author-date') ? substr($author->get_firstname(), 0, 1) : $author->get_firstname();
            $lastname = $author->get_lastname();
            $record = array(
                'given'  => $this->encode_for_citeproc($firstname),
                'family' => $this->encode_for_citeproc($lastname),
            );
            $publication->author[] = (object) $record;
        }

        foreach ($this->get_editors() as $editor) {
            $record = array(
                'given'  => $this->encode_for_citeproc($editor->get_firstname()),
                'family' => $this->encode_for_citeproc($editor->get_lastname()),
            );
            $publication->editor[] = (object) $record;
        }


        // Currently unused fields - left blank.
        $publication->{'citation-label'} = '';

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
    public function get_citeproc_type($csl)
    {
        $kartype = $this->get_type();

        switch ($kartype) {
            // Unsure.
            case 'artefact':
            case 'exhibition':
            case 'software':
            case 'scholarlyed':
                return 'article';
            case 'audio':
                return 'speech';
            case 'performance':
                return 'song';
            case 'monograph':
                return 'thesis';
            case 'design':
                return 'figure';

            // "Probably" right.
            case 'article':
                return $csl === 'apa' ? 'article-journal' : 'article';
            case 'book':
            case 'edbook':
                return 'book';
            case 'book_section':
                return 'chapter';
            case 'composition':
                return 'musical_score';
            case 'conference_item':
                return 'paper-conference';
            case 'confidential_report':
                return 'report';
            case 'dataset':
                return 'dataset';
            case 'image':
                return 'graphic';
            case 'internet':
                return 'webpage';
            case 'patent':
                return 'patent';
            case 'research_report':
                return 'report';
            case 'review':
                return 'review';
            case 'thesis':
                return 'thesis';
            case 'video':
                return 'motion_picture';

            // Return article as the default.
            case 'other':
            default:
                return $csl === 'apa' ? 'article-journal' : 'article';
        }
    }

    /**
     * Format for CiteProc.
     *
     * @internal
     *
     * @param string $csl - reference format csl APA/IEEE etc
     *
     * @return object $parser - Parser for given reference format
     */
    protected function get_citeproc_parser($csl)
    {
        static $parsers = array();

        if (!isset($parsers[$csl])) {
            $safecsl = preg_replace("([^a-z0-9\-])", '', $csl);

            $filename = dirname(__FILE__) . '/csls/' . $safecsl . '.csl';
            if (!file_exists($filename)) {
                throw new \Exception('Invalid CSL: ' . $csl);
            }

            $cslcontent = file_get_contents($filename);
            $parsers[$csl] = new \academicpuma\citeproc\CiteProc($cslcontent);
        }

        return $parsers[$csl];
    }

    /**
     * Tidy up after citeproc parser has done its thing.
     *
     * @param string $citation The citation to tidy up.
     */
    protected static function tidy_citation($citation)
    {
        // remove full-stops when strings are already punctuated.
        foreach (array('?</span>.' => '?</span>', '!</span>.' => '!</span>') as $search => $replace) {
            $citation = str_replace($search, $replace, $citation);
        }

        return $citation;
    }
}
