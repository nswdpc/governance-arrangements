<?php

namespace NSWDPC\GovernanceArrangements\Test;

require_once(__DIR__ . '/GovernanceArrangementsServiceTestCase.php');

/**
 * Test for 20251201
 */
class v20251201Test extends GovernanceArrangementsServiceTestCase
{
    /**
     * @var float
     */
    protected $dataVersion = 20251201;

    /**
     * @var int|null
     */
    protected $totalClusters = 11;

    public function testClusterDepartments(): void
    {
        $this->serviceInstance->getData();
        $clusterData = $this->serviceInstance->getCluster('Customer Service');
        $this->assertEquals(1, count($clusterData['departments']));
    }

    public function testClusterFromDepartment(): void
    {
        $this->serviceInstance->getData();
        $clusterName = $this->serviceInstance->getClusterFromDepartment('NSW Early Learning Commission');
        $this->assertEquals('Education', $clusterName);
    }

    public function testGetClusterFromAgency(): void
    {
        $this->serviceInstance->getData();
        $cluster = $this->serviceInstance->getClusterFromAgency('NSW Early Learning Commission');
        $this->assertEquals('Education', $cluster);
    }
}
