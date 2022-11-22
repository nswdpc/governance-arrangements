<?php

namespace NSWDPC\GovernanceArrangements\Services;

use Symfony\Component\Yaml\Parser as YamlParser;

/**
 * GovernanceArrangementsService
 *
 * A service class to provide some methods to retrieve data from a
 * YML source file representing governance arrangements at a certain date
 *
 * @author James
 */
class GovernanceArrangementsService {

    /**
     * Version in use for this instance
     * @var string|float|null
     */
    protected $dataVersion = null;

    /**
     * The data sourced from the yml file
     * This value is cached to allow multiple calls on the structure
     * @var array
     */
    protected $data = [];

    /**
     * A data structure, keys are clusters, values are agencies
     * This value is cached to allow multiple calls on the structure
     * @var array
     */
    protected $clusterAgencies = [];

    /**
     * A flat data structure, keys are agency names, value is the cluster
     * This value is cached to allow multiple calls on the structure
     * @var array
     */
    protected $agencies = [];

    /**
     * The source file location for data, relative to library root
     * @var string
     */
    public static $sourceDirectory = "data/yml/governance-arrangements";


    /**
     * To get an instance of this class, call GovernanceArrangementsService::create( VERSION );
     */
    private function __construct($dataVersion) {
        if(!$dataVersion) {
            throw new \Exception("Please provide a valid version");
        }
        $this->dataVersion = $dataVersion;
    }

    /**
     * Return an instance of this class locked to the provided data version
     */
    public static function create($dataVersion) : self {
        return new static($dataVersion);
    }

    /**
     * Reset the values of properties, called when a data version is changed
     */
    public function resetValues() : void {
        $this->data = [];
        $this->clusterAgencies = [];
        $this->agencies = [];
    }

    /**
     * Return the filesystem path to a YML data file
     * @throws \Exception
     */
    public function getDataPath($dataVersion) : string {
        // the path is relative to the current file
        $path = dirname(__FILE__)
                    . '/../../'
                    . self::$sourceDirectory
                    . '/' . $dataVersion . '.yml';
        $realPath = realpath($path);
        if(!$realPath || !is_readable($realPath)) {
            throw new \Exception("Path '{$path}' not found or readable for {$this->dataVersion} request");
        }
        return $realPath;
    }

    /**
     * Get all data from the source file (possibly a URL in the future)
     * @param bool $force if true, will ignore previously parsed data and refetch from source
     * @throws \Exception
     */
    public function getData(bool $force = false) : array {

        if(!$this->dataVersion) {
            throw new \Exception("No data version found!");
        }

        // Return data if already parsed
        if(!empty($this->data[ $this->dataVersion ]) && !$force) {
            return $this->data[ $this->dataVersion ];
        }

        $this->resetValues();

        $path = $this->getDataPath($this->dataVersion);

        // Grab parsed YML
        $yml = file_get_contents($path);
        if($yml) {
            $parser = new YamlParser();
            $data = $parser->parse($yml);
            if(!empty($data['version'])) {
                if(empty($data['version']) || $data['version'] != $this->dataVersion) {
                    throw new \Exception("Version mismatch between requested and that found in source file");
                }
                $this->data[ $this->dataVersion ] = $data;
            }
        }

        if(empty($this->data[ $this->dataVersion ])) {
            throw new \Exception("Source data is empty");
        }
        return $this->data[ $this->dataVersion ];
    }

    /**
     * Return the meta keys
     */
    public static function getMetaKeys() : array {
        return [
            'name','version','supersedes','created','fromdate','title',
            'author','source','description'
        ];
    }

    /**
     * Return the meta data from a YMLfile
     */
    public function getMetaData() : array {
        $meta = [];
        $data = $this->getData();

        $intersect = array_filter(
            $data,
            function($k) {
                return in_array($k, self::getMetaKeys() );
            },
            ARRAY_FILTER_USE_KEY
        );
        return $intersect;
    }

    /**
     * Retrieve all clusters and their child data
     * The return value is an array, each key is an org type, values are orgs in that type
     */
    public function getClusters() : array {
        $data = $this->getData();
        return !empty($data['clusters']) ? $data['clusters'] : [];
    }

    /**
     * Get data for a named cluster
     * @param string $cluster e.g 'Treasury'
     * @throws \Exception
     */
    public function getCluster(string $cluster) : array {
        $clusters = $this->getClusters();
        if(isset($clusters[$cluster])) {
            return $clusters[$cluster];
        }
        throw new \Exception("Cluster {$cluster} not found");
    }

    /**
     * Give an department name, find the cluster it is in
     * This returns the value from {@link self::getClusterFromAgency()}
     * @param string $department e.g 'Treasury'
     */
    public function getClusterFromDepartment(string $department) : string {
        return $this->getClusterFromAgency($department);
    }

    /**
     * Give an agency, find the cluster it is in
     * @param string $cluster e.g 'Treasury'
     */
    public function getClusterFromAgency(string $agency) : string {
        if(!$agency) {
            return '';
        }
        $this->getAllAgencies();
        return isset($this->agencies[$agency]) ? $this->agencies[$agency] : '';
    }

    /**
     * Optionally create and return the cluster/agency data structure
     * The return value is suitable for use in a select field with
     * optgroup / option child elements
     * @return array
     */
    public function getClusterAgencies() : array {
        $this->getAllAgencies();
        return $this->clusterAgencies;
    }

    /**
     * Get a structured list of all agencies
     * keys are the agency, each value is a cluster name
     * @param bool $force whether to force
     */
    public function getAllAgencies(bool $force = false) : array {
        if(!empty($this->agencies) && !$force) {
            return $this->agencies;
        }

        // get all clusters
        $clusters = $this->getClusters();
        $this->agencies = $this->clusterAgencies = [];
        foreach($clusters as $clusterName => $meta) {

            // meta is an array with a key ( org type ) values are the names of the orgs in that type
            $this->clusterAgencies[$clusterName] = [];

            // Store cluster keyed by clustername, values are agencies (orgs)
            array_walk(
                $meta,
                function( array $orgs, string $orgType ) use ($clusterName)  {
                    foreach($orgs as $org) {
                        $this->clusterAgencies[ $clusterName ][ $org ] = $org;
                        // NB this assumes $org is unique
                        $this->agencies[ $org ] = $clusterName;
                    }
                }
            );
        }

        return $this->agencies;
    }

}
