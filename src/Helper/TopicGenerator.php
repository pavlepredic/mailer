<?php

namespace HelloFresh\Mailer\Helper;

class TopicGenerator
{
    const DEFAULT_SEPARATOR = '.';

    /**
     * @var string $namespace
     */
    private $namespace;

    /**
     * @var string $separator
     */
    private $separator;

    /**
     * Topic constructor.
     * @param string $namespace
     * @param string $separator
     */
    public function __construct($namespace = null, $separator = null)
    {
        $this->namespace = $namespace;
        if (is_null($separator)) {
            $separator = self::DEFAULT_SEPARATOR;
        }
        $this->separator = $separator;
    }

    /**
     * @param array|string $topicElements
     * @return string
     */
    public function generate($topicElements)
    {
        if (!is_array($topicElements)) {
            $topicElements = [$topicElements];
        }

        if ($this->namespace) {
            array_unshift($topicElements, $this->namespace);
        }

        return join($this->separator, $topicElements);
    }
}
