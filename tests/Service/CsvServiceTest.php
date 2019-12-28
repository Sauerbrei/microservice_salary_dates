<?php
declare(strict_types=1);

namespace App\Tests\Service;

use App\Service\CsvService;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Serializer\Serializer;

/**
 * Class CsvServiceTest
 *
 * @package App\Tests\Service
 */
class CsvServiceTest extends WebTestCase
{
    private const TEST_DIR = 'test/';
    private const TEST_FILE_NAME = 'test.txt';
    private const TEST_FILE_PATH = self::TEST_DIR . self::TEST_FILE_NAME;

    /**
     * @var \App\Service\CsvService
     */
    private $csvService;

    /**
     * @var Filesystem
     */
    private $fileSystem;

    /**
     * @var string
     */
    private $projectDir;

    /**
     * @var string
     */
    private $filePath;

    /**
     *
     */
    protected function setUp()
    {
        parent::setUp();
        $kernel = self::createKernel();
        $kernel->boot();
        $container = $kernel->getContainer();
        /** @var Filesystem $fileSystem */
        $this->fileSystem = $container->get('filesystem');
        /** @var Serializer $serializer */
        $serializer = $container->get('serializer');
        $this->projectDir = $kernel->getProjectDir();
        $this->csvService = new CsvService(
            $this->fileSystem,
            $serializer,
            $this->projectDir
        );
        $this->fileSystem->mkdir(self::TEST_DIR);
    }

    /**
     *
     */
    public function testWrite(): void
    {
       $this->csvService->write(
           self::TEST_FILE_PATH,
           ['test']
       );
       $this->filePath = $this->projectDir . DIRECTORY_SEPARATOR . 'var' . DIRECTORY_SEPARATOR . self::TEST_FILE_PATH;

       $this->assertFileExists($this->filePath, 'File must exists');
       $this->assertTrue(strlen(file_get_contents($this->filePath)) > 0, 'File should have contet');
       $this->assertFileNotExists(self::TEST_FILE_PATH, 'File must not exist at this path');
    }

    /**
     *
     */
    protected function tearDown(): void
    {
        $this->fileSystem->remove($this->filePath);
        $this->fileSystem = null;
        $this->csvService = null;
        $this->projectDir = null;
        $this->filePath = null;
        parent::tearDown();
    }
}
