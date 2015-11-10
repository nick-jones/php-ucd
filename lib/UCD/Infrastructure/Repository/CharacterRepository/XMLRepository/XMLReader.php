<?php

namespace UCD\Infrastructure\Repository\CharacterRepository\XMLRepository;

use UCD\Exception\InvalidArgumentException;
use UCD\Exception\RuntimeException;

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
     * @throws InvalidArgumentException
     * @throws RuntimeException
     */
    public function __construct($uri, $encoding = null, $options = 0)
    {
        if (!is_readable($uri)) {
            throw new InvalidArgumentException(sprintf('Invalid URI: %s', $uri));
        }

        $result = $this->open($uri, $encoding, $options);

        if ($result !== true) {
            throw new RuntimeException(sprintf('Could not open %s', $uri));
        }
    }

    /**
     * {@inheritDoc}
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
    public function reopen()
    {
        return parent::open($this->openUri, $this->openEncoding, $this->openOptions);
    }
} 