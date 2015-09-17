<?php

namespace UCD\Infrastructure\Repository\CharacterRepository\FileRepository;

use PhpParser\Node\Expr\Array_;
use PhpParser\Node\Expr\ArrayItem;
use PhpParser\Node\Scalar\LNumber;
use PhpParser\Node\Scalar\String_;
use PhpParser\Node\Stmt\Return_;
use PhpParser\PrettyPrinter\Standard;
use UCD\Exception\InvalidArgumentException;

class PHPRangeFile extends RangeFile
{
    const FILE_NAME_REGEX  = '/^(?P<start>\d+)-(?P<end>\d+)!(?P<total>\d+)\.php$/';
    const FILE_PATH_FORMAT = '%s/%08d-%08d!%04d.php';

    /**
     * @param \SplFileInfo $fileInfo
     * @return PHPRangeFile
     * @throws InvalidArgumentException
     */
    public static function fromFileInfo(\SplFileInfo $fileInfo)
    {
        if (preg_match(self::FILE_NAME_REGEX, $fileInfo->getBasename(), $matches) !== 1) {
            throw new InvalidArgumentException();
        }

        $range = new Range(
            (int)$matches['start'],
            (int)$matches['end']
        );

        return new self($range, $fileInfo, (int)$matches['total']);
    }

    /**
     * @param string $dbPath
     * @param Range $range
     * @param int $total
     * @return PHPRangeFile
     */
    public static function fromRange($dbPath, Range $range, $total)
    {
        $filePath = self::generateFilePath($dbPath, $range, $total);
        $fileInfo = new \SplFileInfo($filePath);

        return new self($range, $fileInfo, $total);
    }

    /**
     * @param string $dbPath
     * @param Range $range
     * @param int $total
     * @return string
     */
    private static function generateFilePath($dbPath, Range $range, $total)
    {
        return sprintf(
            self::FILE_PATH_FORMAT,
            $dbPath,
            $range->getStart(),
            $range->getEnd() - 1,
            $total
        );
    }

    /**
     * @return array
     */
    public function read()
    {
        return require (string)$this->fileInfo;
    }

    /**
     * @param array $map
     * @return bool
     */
    public function write(array $map)
    {
        $content = $this->generateFileContent($map);
        $file = $this->fileInfo->openFile('w');
        $file->fwrite($content);

        return $file->fflush();
    }

    /**
     * @param array $map
     * @return string
     */
    private function generateFileContent(array $map)
    {
        $items = [];

        foreach ($map as $key => $value) {
            $key = new LNumber($key);
            $value = new String_($value);
            $item = new ArrayItem($value, $key);
            array_push($items, $item);
        }

        $statements = [new Return_(new Array_($items))];
        $printer = new Standard();

        return $printer->prettyPrintFile($statements);
    }
}