<?php

namespace Padam87\RasterizeBundle;

use Symfony\Component\Process\InputStream;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\ProcessBuilder;

class ConfigHelper
{
    /**
     * @var array
     */
    protected $config;

    /**
     * @var string
     */
    protected $rootDir;

    /**
     * @param array  $config
     * @param string $rootDir
     */
    public function __construct(array $config, $rootDir)
    {
        $this->config = $config;
        $this->rootDir = $rootDir;
    }

    /**
     * @param InputStream $input
     * @param array       $arguments
     *
     * @return Process
     */
    public function buildProcess($input, $arguments = array())
    {
        $builder = new ProcessBuilder();
        $builder
            ->setPrefix($this->config['phantomjs']['callable'])
            ->setArguments(
                array_merge(
                    $this->processPhantomjsOptions(),
                    [$this->config['script']],
                    array_values(array_merge($this->config['arguments'], $arguments))
                )
            )
            ->setInput($input)
        ;

        return $builder->getProcess();
    }

    /**
     * @return array
     */
    protected function processPhantomjsOptions()
    {
        $options = array();

        foreach ($this->config['phantomjs']['options'] as $name => $value) {
            if (is_numeric($name)) {
                $options[] = $value;
            } else {
                if (is_bool($value)) {
                    $value = ($value) ? 'true' : 'false';
                }
                $options[] = sprintf('%s=%s', $name, $value);
            }
        }

        return $options;
    }
}
