<?php

namespace NSWDPC\GovernanceArrangements\Test;

require_once(__DIR__ . '/GovernanceArrangementsServiceTestCase.php');

/**
 * Test for v20240807
 */
class v20240807Test extends GovernanceArrangementsServiceTestCase
{
    /**
     * @var float
     */
    protected $dataVersion = 20240807;

    /**
     * @var int|null
     */
    protected $totalClusters = 11;

    public function testClusterDepartments(): void
    {
        $this->serviceInstance->getData();
        $clusterData = $this->serviceInstance->getCluster('Premier and Cabinet');
        $this->assertEquals(2, count($clusterData['departments']));
    }

    public function testClusterFromDepartment(): void
    {
        $this->serviceInstance->getData();
        $clusterName = $this->serviceInstance->getClusterFromDepartment("Premier's Department");
        $this->assertEquals('Premier and Cabinet', $clusterName);
    }

    public function testGetClusterFromAgency(): void
    {
        $this->serviceInstance->getData();
        $cluster = $this->serviceInstance->getClusterFromAgency("Premier's Department");
        $this->assertEquals('Premier and Cabinet', $cluster);
    }
}
