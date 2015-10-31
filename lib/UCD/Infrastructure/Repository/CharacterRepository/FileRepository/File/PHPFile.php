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
        return require $this->fileInfo->getPathname();
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
        $file = $this->fileInfo->openFile('w');
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
            $key = new LNumber($key);
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
}