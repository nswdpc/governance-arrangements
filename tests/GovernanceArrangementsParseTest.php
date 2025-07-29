<?php

namespace NSWDPC\GovernanceArrangements\Test;

use NSWDPC\GovernanceArrangements\Services\GovernanceArrangementsService;
use PHPUnit\Framework\TestCase;

require_once(__DIR__ . '/../src/Services/GovernanceArrangementsService.php');

class GovernanceArrangementsParseTest extends TestCase
{
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
        $sourcePath = realpath(__DIR__ . "/../" . $sourceDirectory);
        if ($sourcePath === '' || $sourcePath === '0' || $sourcePath === false || !is_readable($sourcePath)) {
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

    public function testServiceCreationFromFile(): void
    {
        foreach ($this->ymlFiles as $file) {
            $dataVersion = basename((string) $file, ".yml");
            $service = GovernanceArrangementsService::create($dataVersion);
            $data = $service->getData();
            $this->assertEquals($dataVersion, $data['version']);
        }
    }

}
