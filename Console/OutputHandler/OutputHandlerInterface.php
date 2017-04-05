<?php

namespace PlentyConnector\Console\OutputHandler;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Interface OutputHandlerInterface
 */
interface OutputHandlerInterface
{
    /**
     * Initialize the handler. This needs to be done inside each console command
     *
     * @param InputInterface  $input
     * @param OutputInterface $output
     */
    public function initialize(InputInterface $input, OutputInterface $output);

    /**
     * Start the progressbar
     *
     * @param int $count
     */
    public function startProgressBar($count);

    /**
     * Advance the progressbar one step
     */
    public function advanceProgressBar();

    /**
     * Finish the progressbar
     */
    public function finishProgressBar();

    /**
     * Write
     *
     * @param string $messages
     */
    public function writeLine($messages);

    /**
     * @param array $headers
     * @param array $rows
     */
    public function createTable(array $headers, array $rows);
}
