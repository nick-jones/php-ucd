<?php

namespace UCD\Infrastructure\Repository\CharacterRepository\FileRepository;

class FileIterator extends \CallbackFilterIterator
{
    /**
     * @param \SplFileInfo $path
     * @return FileIterator
     */
    public static function fromPath(\SplFileInfo $path)
    {
        $pathName = $path->getPathname();

        if (strpos($path, ':') === strlen($pathName) - 1) {
            $pathName = sprintf('%s//', $pathName);
        }

        $directory = new \FilesystemIterator($pathName, \FilesystemIterator::CURRENT_AS_FILEINFO);

        return new self($directory, function (\SplFileInfo $file) {
            return $file->isFile();
        });
    }
}