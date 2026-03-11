<?php

namespace Padam87\RasterizeBundle;

use Symfony\Component\Process\InputStream;
use Symfony\Component\Process\Process;

class ConfigHelper
{
    public function __construct(private string $projectDir, private array $config)
    {
    }

    public function buildProcess(InputStream $input, array $arguments = [], array $env = []): Process
    {
        return new Process(
            array_merge(
                [
                    $this->config['script']['callable'],
                    $this->projectDir . DIRECTORY_SEPARATOR . $this->config['script']['path']
                ],
                array_values(array_merge($this->config['arguments'], $arguments))
            ),
            null,
            array_merge($this->config['env_vars'], $env),
            $input
        );
    }
}
