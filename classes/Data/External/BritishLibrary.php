<?php
/**
 * CLA system mock-up
 * 
 * @author Skylar Kelty <skylarkelty@gmail.com>
 */

namespace Data\Query;

/**
 * Class that imports data from the British Library.
 */
class BritishLibrary extends \EasyRdf_Sparql_Client
{
	/**
	 * Constructor
	 */
	public function __construct() {
		\EasyRdf_Namespace::set('bibo', 'http://purl.org/ontology/bibo/');
		\EasyRdf_Namespace::set('bio', 'http://purl.org/vocab/bio/0.1/');
		\EasyRdf_Namespace::set('blt', 'http://www.bl.uk/schemas/bibliographic/blterms#');
		\EasyRdf_Namespace::set('dct', 'http://purl.org/dc/terms/');
		\EasyRdf_Namespace::set('event', 'http://purl.org/NET/c4dm/event.owl#>');
		\EasyRdf_Namespace::set('foaf', 'http://xmlns.com/foaf/0.1/');
		\EasyRdf_Namespace::set('geo', 'http://www.w3.org/2003/01/geo/wgs84_pos#');
		\EasyRdf_Namespace::set('isbd', 'http://iflastandards.info/ns/isbd/elements/');
		\EasyRdf_Namespace::set('org', 'http://www.w3.org/ns/org#');
		\EasyRdf_Namespace::set('owl', 'http://www.w3.org/2002/07/owl#');
		\EasyRdf_Namespace::set('rda', 'http://RDVocab.info/ElementsGr2/');
		\EasyRdf_Namespace::set('rdf', 'http://www.w3.org/1999/02/22-rdf-syntax-ns#');
		\EasyRdf_Namespace::set('rdfs', 'http://www.w3.org/2000/01/rdf-schema#');
		\EasyRdf_Namespace::set('skos', 'http://www.w3.org/2004/02/skos/core#');
		\EasyRdf_Namespace::set('xsd', 'http://www.w3.org/2001/XMLSchema#');
		\EasyRdf_Namespace::set('void', 'http://rdfs.org/ns/void#');

		parent::__construct('http://bnb.data.bl.uk/sparql');
	}

	/**
	 * Search BL for $title
	 *
	 * @param string $title The title
	 */
	public function search_title($title) {
		$query = <<<SPARQL
			SELECT ?book ?title WHERE {
			   ?book dct:title "$title";
		       dct:title ?title;
			}
SPARQL;

		$titles = array();

	    $result = $this->query($query);
	    foreach ($result as $row) {
	        print_r($row);
	    	$title = $row->title->getValue();
	        $titles[$title] = 1;
	    }

	    return array_keys($titles);
	}

	/**
	 * Search BL for $author
	 *
	 * @param string $author The author
	 */
	public function search_author($author) {
		$query = <<<SPARQL
			SELECT ?person ?name WHERE {
			   ?person foaf:name "$author";
		       foaf:name ?name;
			}
SPARQL;

		$names = array();

	    $result = $this->query($query);
	    foreach ($result as $row) {
	    	$name = $row->name->getValue();
	        $names[$name] = 1;
	    }

	    return array_keys($names);
	}

	/**
	 * Search BL for $isbn
	 *
	 * @param string $isbn The isbn
	 */
	public function search_isbn($isbn) {
		$query = <<<SPARQL
			SELECT ?book ?bnb ?title ?creator ?name WHERE {
			   ?book bibo:isbn13 "$isbn";
			      blt:bnb ?bnb;
			      dct:creator ?creator;
			      dct:title ?title.
			}
SPARQL;

	    $result = $this->query($query);
	    foreach ($result as $row) {
	        print_r($row);
	    }
	}

	/**
	 * Search BL for $issn
	 *
	 * @param string $issn The issn
	 */
	public function search_issn($issn) {
		$query = <<<SPARQL
			SELECT ?serial ?bnb ?title WHERE {
			   ?serial bibo:issn "$issn" ;
			      blt:bnb ?bnb ;
			      dct:title ?title .
			}
SPARQL;

	    $result = $this->query($query);
	    foreach ($result as $row) {
	        print_r($row);
	    }
	}
}
