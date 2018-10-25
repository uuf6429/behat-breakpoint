<?php

namespace uuf6429\BehatBreakpoint;

use Behat\Behat\Context\Context as ContextInterface;

class Context implements ContextInterface
{
    /**
     * @var Factory
     */
    protected $breakpointFactory;

    public function __construct(Factory $factory)
    {
        $this->breakpointFactory = $factory ?: new Factory();
    }

    // TODO add steps
}
