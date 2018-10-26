<?php

namespace uuf6429\BehatBreakpoint;

if (interface_exists(\Behat\MinkExtension\Context\MinkAwareContext::class)) {
    class MinkAwareContextHandler implements \Behat\MinkExtension\Context\MinkAwareContext
    {
        /**
         * @var \Behat\Mink\Mink
         */
        protected $mink;

        /**
         * @var array
         */
        protected $minkParameters;

        public function setMink(\Behat\Mink\Mink $mink)
        {
            $this->mink = $mink;
        }

        public function setMinkParameters(array $parameters)
        {
            $this->minkParameters = $parameters;
        }

        /**
         * @return \Behat\Mink\Mink
         */
        public function getMink()
        {
            return $this->mink;
        }

        /**
         * @return array
         */
        public function getMinkParameters()
        {
            return $this->minkParameters;
        }
    }
} else {
    class MinkAwareContextHandler implements \Behat\Behat\Context\Context
    {
        public function getMink()
        {
            $this->throwMinkRequiredError();
        }

        public function getMinkParameters()
        {
            $this->throwMinkRequiredError();
        }

        protected function throwMinkRequiredError()
        {
            throw new \RuntimeException(
                'MinkExtension is not installed! Ensure `behat/mink-extension` is in your project\'s `composer.json`.'
            );
        }
    }
}
