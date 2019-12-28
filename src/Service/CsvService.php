<?php
declare(strict_types=1);

namespace App\Service;

use Symfony\Component\Filesystem\Exception\IOException;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Serializer\Serializer;

/**
 * Class CsvService
 *
 * @package App\Service
 */
class CsvService
{
    public const CSV_DELIMITER = ';';
    public const CSV_ENCLOSURE = '"';
    public const CSV_ESCAPE_CHAR = '\\';

    /**
     * @var FileSystem
     */
    private $fileSystem;

    /**
     * @var Serializer
     */
    private $serializer;

    /**
     * @var string
     */
    private $projectDir;

    /**
     * CsvService constructor.
     *
     * @param Filesystem $fileSystem
     * @param Serializer $serializer
     * @param string     $projectDir
     */
    public function __construct(
        Filesystem $fileSystem,
        Serializer $serializer,
        string $projectDir
    ) {
        $this->fileSystem = $fileSystem;
        $this->serializer = $serializer;
        $this->projectDir = $projectDir;
    }

    /**
     * @param string $path
     * @param array  $data
     * @return string
     * @throws IOException
     */
    public function write(string $path, array $data): string
    {
        // needs to be sanitized and jailed
        // you can override /etc/passwd with it
        // not part of this demo, though
        $filePath = $this->projectDir . DIRECTORY_SEPARATOR .
            'var' . DIRECTORY_SEPARATOR .
            $path
        ;
        $this->prepareFile($filePath);
        $this->commitData($filePath, $data);

        return $filePath;
    }

    /**
     * @param resource $resource
     * @param array    $data
     *
     * @return int
     * @throws \RuntimeException
     */
    private function commitData(string $path, array $data): int
    {
        $csvData = $this->serializer->serialize($data, 'csv');
        $length = file_put_contents($path, $csvData);

        if ($length === false) {
            $this->fileSystem->remove($path);
            throw new \RuntimeException(
                sprintf(
                    'Unable to write row with data %s',
                    serialize($data)
                )
            );
        }

        return $length;
    }

    /**
     * @param string $filePath
     */
    private function prepareFile(string $filePath): void
    {
        $this->fileSystem->mkdir(dirname($filePath));
        $this->fileSystem->touch($filePath);
    }
}
