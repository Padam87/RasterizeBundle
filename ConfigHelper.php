<?php

namespace Padam87\RasterizeBundle;

use Symfony\Component\Process\InputStream;
use Symfony\Component\Process\Process;

class ConfigHelper
{
    private $config;
    private $projectDir;

    public function __construct(string $projectDir, array $config)
    {
        $this->config = $config;
        $this->projectDir = $projectDir;
    }

    public function buildProcess(InputStream $input, array $arguments = []): Process
    {
        $process = new Process(
            array_merge(
                [
                    $this->config['script']['callable'],
                    $this->projectDir . DIRECTORY_SEPARATOR . $this->config['script']['path']
                ],
                array_values(array_merge($this->config['arguments'], $arguments))
            ),
            null,
            [],
            $input
        );

        return $process;
    }
}
