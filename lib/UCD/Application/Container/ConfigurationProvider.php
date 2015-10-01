<?php

namespace UCD\Application\Container;

use Pimple\Container;
use Pimple\ServiceProviderInterface;
use Symfony\Component\Console\Input\ArgvInput;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Yaml\Yaml;

class ConfigurationProvider implements ServiceProviderInterface
{
    /**
     * @param Container $pimple
     */
    public function register(Container $pimple)
    {
        $config = $this->defaultConfiguration();
        $input = $pimple['symfony.input'];

        if ($input->hasOption('config')) {
            $parser = new Yaml();
            $filePath = $input->getOption('config');
            $fileInfo = new \SplFileInfo($filePath);
            $config += $this->parseConfigFile($parser, $fileInfo);
        }

        foreach ($config as $id => $value) {
            $pimple[$id] = $value;
        }
    }

    /**
     * @return array
     */
    private function defaultConfiguration()
    {
        return [
            'config.repository.php.database_path' => __DIR__ . '/../../../../resources/generated/db',
            'config.repository.xml.ucd_file_path' => __DIR__ . '/../../../../resources/ucd.all.flat.xml'
        ];
    }

    /**
     * @param Yaml $parser
     * @param \SplFileInfo $fileInfo
     * @return array
     */
    private function parseConfigFile(Yaml $parser, \SplFileInfo $fileInfo)
    {
        $file = $fileInfo->openFile('r');
        $content = $file->fread($file->getSize());

        return $parser->parse($content);
    }
}