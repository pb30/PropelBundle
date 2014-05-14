<?php

/**
 * This file is part of the PropelBundle package.
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @license    MIT License
 */

namespace Propel\PropelBundle\Command;

use Propel\Generator\Command\AbstractCommand as BaseCommand;

use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @author Kévin Gomez <contact@kevingomez.fr>
 */
abstract class WrappedCommand extends AbstractCommand
{
    /**
     * Creates the instance of the Propel sub-command to execute.
     *
     * @return \Symfony\Component\Console\Command\Command
     */
    abstract protected function createSubCommandInstance();

    /**
     * Returns all the arguments and options needed by the Propel sub-command.
     *
     * @return array
     */
    abstract protected function getSubCommandArguments(InputInterface $input);

    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->addOption('platform',  null, InputOption::VALUE_REQUIRED,  'The platform', BaseCommand::DEFAULT_PLATFORM)
        ;
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $buildProperties = $this->getBuildProperties();
        $params = $this->getSubCommandArguments($input, $buildProperties);

        $command = $this->createSubCommandInstance();

        $this->setupBuildTimeFiles();

        return $this->runCommand($command, $params, $input, $output);
    }

    /**
     * Retrieve the Propel build_properties values
     *
     * @return array
     */
    private function getBuildProperties()
    {
        $buildProperties = $this->getContainer()->getParameter('propel.build_properties');
        if (!is_array($buildProperties)) {
            $buildProperties = array();
        }
        return $buildProperties;
    }

    /**
     * Get parameters affected by build_properties
     *
     * Inspects the build properties that have been set in the
     * application configs and sets default parameters based on
     * settings that would be affected by build properties.
     *
     * @return array
     */
    private function getBuildPropertiesParameters()
    {
        $buildProperties = $this->getContainer()->getParameter('propel.build_properties');
        $output = array();

        if (array_key_exists('propel.project.dir', $buildProperties)) {
            $output['--input-dir'] = $buildProperties['propel.schema.dir'];
        }

        if (array_key_exists('propel.schema.dir', $buildProperties)) {
            $output['--input-dir'] = $buildProperties['propel.schema.dir'];
        }

        if (array_key_exists('propel.output.dir', $buildProperties)) {
            $output['--output-dir'] = $buildProperties['propel.output.dir'];
        }

        return $output;
    }
}
