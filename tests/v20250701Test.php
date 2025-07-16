<?php

namespace NSWDPC\GovernanceArrangements\Test;

require_once( dirname(__FILE__) . '/GovernanceArrangementsServiceTest.php' );

/**
 * Test for 20250701
 */
class v20250701Test extends GovernanceArrangementsServiceTest {

    /**
     * @var float
     */
    protected $dataVersion = 20250701;

    /**
     * @var int|null
     */
    protected $totalClusters = 11;

    public function testClusterDepartments() {
        $data = $this->serviceInstance->getData();
        $clusterData = $this->serviceInstance->getCluster('Customer Service');
        $this->assertEquals(1, count($clusterData['departments']));
    }

    public function testClusterFromDepartment() {
        $data = $this->serviceInstance->getData();
        $clusterName = $this->serviceInstance->getClusterFromDepartment('SafeWork NSW Agency');
        $this->assertEquals('Customer Service', $clusterName);
    }

    public function testGetClusterFromAgency() {
        $data = $this->serviceInstance->getData();
        $cluster = $this->serviceInstance->getClusterFromAgency('SafeWork NSW Agency');
        $this->assertEquals('Customer Service', $cluster );
    }
}
