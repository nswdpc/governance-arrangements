<?php

namespace NSWDPC\GovernanceArrangements\Test;

require_once( dirname(__FILE__) . '/GovernanceArrangementsServiceTest.php' );

/**
 * Test for v20230701
 */
class v20230701Test extends GovernanceArrangementsServiceTest {

    /**
     * @var float
     */
    protected $dataVersion = 20230701;

    /**
     * @var int|null
     */
    protected $totalClusters = 10;

    public function testClusterDepartments() {
        $data = $this->serviceInstance->getData();
        $clusterData = $this->serviceInstance->getCluster('Premier and Cabinet');
        $this->assertEquals(2, count($clusterData['departments']));
    }

    public function testClusterFromDepartment() {
        $data = $this->serviceInstance->getData();
        $clusterName = $this->serviceInstance->getClusterFromDepartment('Premier\'s Department');
        $this->assertEquals('Premier and Cabinet', $clusterName);
    }

    public function testGetClusterFromAgency() {
        $data = $this->serviceInstance->getData();
        $cluster = $this->serviceInstance->getClusterFromAgency('Premier\'s Department');
        $this->assertEquals('Premier and Cabinet', $cluster );
    }
}
