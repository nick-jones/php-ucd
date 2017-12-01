<?php

namespace UCD\Infrastructure\Repository\CharacterRepository\FileRepository\File;

use PhpParser\Node\Expr\Array_;
use PhpParser\Node\Expr\ArrayItem;
use PhpParser\Node\Scalar\LNumber;
use PhpParser\Node\Scalar\String_;
use PhpParser\Node\Stmt\Return_;
use PhpParser\PrettyPrinter\Standard;

use UCD\Exception\UnexpectedValueException;
use UCD\Infrastructure\Repository\CharacterRepository\FileRepository\File;

class PHPFile implements File
{
    /**
     * @var \SplFileInfo
     */
    private $fileInfo;

    /**
     * @param \SplFileInfo $fileInfo
     */
    public function __construct(\SplFileInfo $fileInfo)
    {
        $this->fileInfo = $fileInfo;
    }

    /**
     * @return string[]
     * @throws UnexpectedValueException
     */
    public function readArray()
    {
        $value = $this->readFile();
        $this->verifyType($value, 'array');

        return $value;
    }

    /**
     * @return mixed
     */
    private function readFile()
    {
        $pathname = $this->fileInfo->getPathname();

        return require $this->isZippedFile() ? 'compress.zlib://' . $pathname : $pathname;
    }

    /**
     * @param string $value
     * @param string $expectedType
     * @throws UnexpectedValueException
     */
    private function verifyType($value, $expectedType)
    {
        $actualType = gettype($value);

        if ($actualType !== $expectedType) {
            throw new UnexpectedValueException(sprintf('Expected %s, got %s', $expectedType, $actualType));
        }
    }

    /**
     * @param string[] $items
     * @return bool
     */
    public function writeArray(array $items)
    {
        $content = $this->generateFileContentForArray($items);

        $fileInfo = $this->isZippedFile()
            ? new \SplFileInfo('compress.zlib://' . $this->fileInfo->getPathname())
            : $this->fileInfo;
        $file = $fileInfo->openFile('w');
        $file->fwrite($content);

        return $file->fflush();
    }

    /**
     * @param array $items
     * @return string
     */
    private function generateFileContentForArray(array $items)
    {
        $nodes = [];

        foreach ($items as $key => $value) {
            $key = is_int($key) ? new LNumber($key) : new String_($key);
            $value = new String_($value);
            $node = new ArrayItem($value, $key);
            array_push($nodes, $node);
        }

        $statements = [new Return_(new Array_($nodes))];
        $printer = new Standard();

        return $printer->prettyPrintFile($statements);
    }

    /**
     * @return \SplFileInfo
     */
    public function getInfo()
    {
        return $this->fileInfo;
    }

    /**
     * @param array $items
     * @return boolean
     */
    private function isZippedFile()
    {
        if (preg_match('`^phpvfs[0-9a-z]*://`i', $this->fileInfo->getPathname())) {
            // php-vfs does not support combining stream wrappers
            return false;
        }

        return $this->fileInfo->getExtension() === 'gz';
    }
}
