<?php

namespace NSWDPC\GovernanceArrangements\Test;

use NSWDPC\GovernanceArrangements\Services\GovernanceArrangementsService;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Yaml\Parser as YamlParser;

require_once( dirname(__FILE__) . '/../src/Services/GovernanceArrangementsService.php' );

abstract class GovernanceArrangementsServiceTest extends TestCase {

    /**
     * @var GovernanceArrangementsService
     */
    protected $serviceInstance = null;

    /**
     * @var float|nullS
     */
    protected $dataVersion = null;

    /**
     * @var int|null
     */
    protected $totalClusters = null;

    /**
     * @inheritdoc
     */
    protected function setUp(): void
    {
        parent::setUp();
        $this->serviceInstance = GovernanceArrangementsService::create($this->dataVersion);
    }

    /**
     * @inheritdoc
     */
    protected function tearDown(): void
    {
        parent::tearDown();
    }

    public function testServiceCreation() {
        $data = $this->serviceInstance->getData();
        $this->assertEquals($this->dataVersion, $data['version']);
    }

    public function testMetaData() {
        $data = $this->serviceInstance->getMetaData();
        $keys = GovernanceArrangementsService::getMetaKeys();
        $this->assertEquals(count($keys), count($data));
    }

    public function testClusters() {
        $data = $this->serviceInstance->getData();
        $this->assertArrayHasKey('clusters', $data);
        $this->assertEquals($this->totalClusters, count($data['clusters']));
    }

    public function testClusterDepartments() {
        $data = $this->serviceInstance->getData();
        $clusterData = $this->serviceInstance->getCluster('Premier and Cabinet');
        $this->assertGreaterThan(0, count($clusterData['departments']));
    }

    public function testClusterExecutiveAgencies() {
        $data = $this->serviceInstance->getData();
        $clusterData = $this->serviceInstance->getCluster('Premier and Cabinet');
        $this->assertGreaterThan(0, count($clusterData['executive_agencies']));
    }

    public function testClusterSeparateAgencies() {
        $data = $this->serviceInstance->getData();
        $clusterData = $this->serviceInstance->getCluster('Premier and Cabinet');
        $this->assertGreaterThan(0, count($clusterData['separate_agencies']));
    }

    public function testClusterFromDepartment() {
        $data = $this->serviceInstance->getData();
        $clusterName = $this->serviceInstance->getClusterFromDepartment('Department of Premier and Cabinet');
        $this->assertEquals('Premier and Cabinet', $clusterName);
    }

    public function testGetClusterAgencies() {
        $data = $this->serviceInstance->getData();
        $clusterAgencies = $this->serviceInstance->getClusterAgencies();
        $this->assertArrayHasKey( 'Premier and Cabinet', $clusterAgencies );
        $this->assertGreaterThan(0, count($clusterAgencies['Premier and Cabinet']) );
    }

    public function testGetClusterFromAgency() {
        $data = $this->serviceInstance->getData();
        $cluster = $this->serviceInstance->getClusterFromAgency('Department of Premier and Cabinet');
        $this->assertEquals('Premier and Cabinet', $cluster );
    }

}
