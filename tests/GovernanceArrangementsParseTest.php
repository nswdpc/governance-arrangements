<?php

namespace NSWDPC\GovernanceArrangements\Test;

use NSWDPC\GovernanceArrangements\Services\GovernanceArrangementsService;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Yaml\Parser as YamlParser;

require_once( dirname(__FILE__) . '/../src/Services/GovernanceArrangementsService.php' );

class GovernanceArrangementsParseTest extends TestCase {

    /**
     * @var array
     */
    protected $ymlFiles = [];

    /**
     * @inheritdoc
     */
    protected function setUp(): void
    {
        parent::setUp();

        $sourceDirectory = GovernanceArrangementsService::$sourceDirectory;
        $sourcePath = realpath( dirname(__FILE__) . "/../" . $sourceDirectory );
        if(!$sourcePath || !is_readable($sourcePath)) {
            throw new \Exception("source path  {$sourceDirectory} is not readable");
        }
        $this->ymlFiles = glob("{$sourcePath}/*.yml");
    }

    /**
     * @inheritdoc
     */
    protected function tearDown(): void
    {
        parent::tearDown();
    }

    public function testServiceCreationFromFile() {
        foreach($this->ymlFiles as $file) {
            $dataVersion = basename($file, ".yml");
            $service = GovernanceArrangementsService::create($dataVersion);
            $data = $service->getData();
            $this->assertEquals($dataVersion, $data['version']);
        }
    }

}
