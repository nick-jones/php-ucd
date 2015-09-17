<?php

namespace UCD\Infrastructure\Repository\CharacterRepository\XMLRepository;

class XMLReader extends \XMLReader
{
    /**
     * @var string
     */
    private $openUri;

    /**
     * @var string|null
     */
    private $openEncoding;

    /**
     * @var int
     */
    private $openOptions;

    /**
     * @param string $uri
     * @param string|null $encoding
     * @param int $options
     * @return bool
     */
    public function open($uri, $encoding = null, $options = 0)
    {
        $this->openUri = $uri;
        $this->openEncoding = $encoding;
        $this->openOptions = $options;

        return parent::open($uri, $encoding, $options);
    }

    /**
     * @return bool
     */
    public function rewind()
    {
        return $this->close()
            && parent::open($this->openUri, $this->openEncoding, $this->openOptions);
    }
} 