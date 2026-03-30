<?php

namespace NSWDPC\GovernanceArrangements\Test;

use NSWDPC\GovernanceArrangements\Services\GovernanceArrangementsService;
use PHPUnit\Framework\TestCase;

require_once(__DIR__ . '/../src/Services/GovernanceArrangementsService.php');

abstract class GovernanceArrangementsServiceTestCase extends TestCase
{
    /**
     * @var GovernanceArrangementsService
     */
    protected $serviceInstance;

    /**
     * @var float|null
     */
    protected $dataVersion;

    /**
     * @var int|null
     */
    protected $totalClusters;

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

    public function testServiceCreation(): void
    {
        $data = $this->serviceInstance->getData();
        $this->assertEquals($this->dataVersion, $data['version']);
    }

    public function testMetaData(): void
    {
        $data = $this->serviceInstance->getMetaData();
        $keys = GovernanceArrangementsService::getMetaKeys();
        $this->assertEquals(count($keys), count($data));
    }

    public function testClusters(): void
    {
        $data = $this->serviceInstance->getData();
        $this->assertArrayHasKey('clusters', $data);
        $this->assertEquals($this->totalClusters, count($data['clusters']));
    }

    public function testClusterDepartments(): void
    {
        $this->serviceInstance->getData();
        $clusterData = $this->serviceInstance->getCluster('Premier and Cabinet');
        $this->assertGreaterThan(0, count($clusterData['departments']));
    }

    public function testClusterExecutiveAgencies(): void
    {
        $this->serviceInstance->getData();
        $clusterData = $this->serviceInstance->getCluster('Premier and Cabinet');
        $this->assertGreaterThan(0, count($clusterData['executive_agencies']));
    }

    public function testClusterSeparateAgencies(): void
    {
        $this->serviceInstance->getData();
        $clusterData = $this->serviceInstance->getCluster('Premier and Cabinet');
        $this->assertGreaterThan(0, count($clusterData['separate_agencies']));
    }

    public function testClusterFromDepartment(): void
    {
        $this->serviceInstance->getData();
        $clusterName = $this->serviceInstance->getClusterFromDepartment('Department of Premier and Cabinet');
        $this->assertEquals('Premier and Cabinet', $clusterName);
    }

    public function testGetClusterAgencies(): void
    {
        $this->serviceInstance->getData();
        $clusterAgencies = $this->serviceInstance->getClusterAgencies();
        $this->assertArrayHasKey('Premier and Cabinet', $clusterAgencies);
        $this->assertGreaterThan(0, count($clusterAgencies['Premier and Cabinet']));
    }

    public function testGetClusterFromAgency(): void
    {
        $this->serviceInstance->getData();
        $cluster = $this->serviceInstance->getClusterFromAgency('Department of Premier and Cabinet');
        $this->assertEquals('Premier and Cabinet', $cluster);
    }

}
