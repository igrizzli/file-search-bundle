<?php
namespace Vilks\FileSearchBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Vilks\FileSearchBundle\Engine\FileSearchEngineInterface;

class FindFileCommand extends ContainerAwareCommand
{
    /** @var FileSearchEngineInterface */
    private $engine;

    /** @var string */
    private $defaultEngine;

    /** @var string[] */
    private $engines;

    /**
     * @param null|string $defaultEngine
     * @param array $engines
     */
    public function __construct($defaultEngine, $engines = [])
    {
        $this->engines = $engines;
        $this->defaultEngine = $defaultEngine;

        parent::__construct();
    }

    /**
     * @inheritDoc
     */
    protected function configure()
    {
        $this
            ->setName('find-file')
            ->setDescription('Find file in directory by content')
            ->addOption(
                'path',
                'p',
                InputOption::VALUE_REQUIRED,
                'Path for searching. Default current directory.',
                './'
            )
            ->addOption(
                'engine',
                null,
                InputOption::VALUE_REQUIRED,
                sprintf('Engine for searching. Allowed - %s', implode(', ', array_keys($this->engines))),
                $this->defaultEngine
            )
            ->addArgument('needle', InputArgument::REQUIRED, 'Searched content')
        ;
    }

    /**
     * @inheritDoc
     */
    protected function initialize(InputInterface $input, OutputInterface $output)
    {
        $this->engine = $this->getContainer()->get('vilks.file_search.registry')->get($input->getOption('engine'));
    }

    /**
     * @inheritDoc
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $needle = $input->getArgument('needle');
        $path = $input->getOption('path');

        foreach ($this->engine->search($needle, $path) as $filePath) {
            $output->writeln($filePath);
        }
    }
}
