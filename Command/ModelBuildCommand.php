<?php

/**
 * This file is part of the PropelBundle package.
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @license    MIT License
 */

namespace Propel\PropelBundle\Command;

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputInterface;
use Propel\Generator\Command\AbstractCommand as PropelAbstractCommand;

use Propel\Generator\Command\ModelBuildCommand as BaseModelBuildCommand;

/**
 * @author Kévin Gomez <contact@kevingomez.fr>
 */
class ModelBuildCommand extends WrappedCommand
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        parent::configure();

        $this
            ->setName('propel:model:build')
            ->setDescription('Build the model classes based on Propel XML schemas')
            ->addOption('platform',  null, InputOption::VALUE_REQUIRED,  'The platform', PropelAbstractCommand::DEFAULT_PLATFORM)
            ->addOption('connection', null, InputOption::VALUE_IS_ARRAY | InputOption::VALUE_OPTIONAL, 'Connection to use. Example: default, bookstore')            ->addOption('output', null, InputArgument::OPTIONAL, 'The directory to output models to')
            ->addOption('output-dir', null, InputOption::VALUE_OPTIONAL, 'The directory to output models to')
            ->addArgument('bundle', InputArgument::OPTIONAL, 'The bundle to generate model classes from')
        ;
    }

    /**
     * {@inheritdoc}
     */
    protected function createSubCommandInstance()
    {
        return new BaseModelBuildCommand();
    }

    /**
     * {@inheritdoc}
     */
    protected function getSubCommandArguments(InputInterface $input)
    {
        $parameters = array();
        $buildProperties = $input->getOption('build.properties');
        $outputOption = $input->getOption('output-dir');

        if (null !== $outputOption) {
            $parameters['--output-dir'] = $outputOption;
        }

        if (array_key_exists('propel.schema.dir', $buildProperties)) {
            $parameters['--input-dir'] = $buildProperties['propel.schema.dir'];
        }

        if (array_key_exists('propel.output.dir', $buildProperties)) {
            $parameters['--output-dir'] = $buildProperties['propel.output.dir'];
        }

        return $parameters;
    }
}
