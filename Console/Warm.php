<?php
/**
 * Copyright © 2015 Dexa. All rights reserved.
 * See LABELS.txt for license details.
 */
namespace Dexa\Warmer\Console;

/**
 * Class Warmer
 * @package Dexa\Warmer\Cron
 */
class Warm extends \Symfony\Component\Console\Command\Command
{
    /**
     * @var \Dexa\Warmer\Cron\Warm
     */
    protected $cron;

    /**
     * Warm constructor.
     * @param \Dexa\Warmer\Cron\Warm $cron
     * @param null $name
     */
    public function __construct(
        \Dexa\Warmer\Cron\Warm $cron,
        $name = null
    ) {
        parent::__construct($name);

        $this->cron = $cron;
    }

    /**
     * configure
     */
    protected function configure()
    {
        $this->setName('xade_warmer:warm')->setDescription('Warm Site');
    }

    /**
     * execute
     * @param InputInterface $input
     * @param OutputInterface $output
     */
    protected function execute(\Symfony\Component\Console\Input\InputInterface $input, \Symfony\Component\Console\Output\OutputInterface $output)
    {
        $this->cron->warm(true);
    }
}
