<?php

namespace UCD\Application\Container;

use Pimple\Container;
use Pimple\ServiceProviderInterface;

use Symfony\Component\Yaml\Yaml;

class ConfigurationProvider implements ServiceProviderInterface
{
    const CONFIG_KEY_DB_PATH = 'config.repository.php.database_path';
    const CONFIG_KEY_XML_PATH = 'config.repository.xml.ucd_file_path';

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
            self::CONFIG_KEY_DB_PATH => sprintf('%s/../../../../resources/generated/ucd', __DIR__),
            self::CONFIG_KEY_XML_PATH => sprintf('%s/../../../../resources/ucd.all.flat.xml', __DIR__)
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