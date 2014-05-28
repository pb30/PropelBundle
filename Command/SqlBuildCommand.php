<?php

/**
 * This file is part of the PropelBundle package.
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @license    MIT License
 */

namespace Propel\PropelBundle\Command;

use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputInterface;
use Propel\Generator\Command\AbstractCommand as PropelAbstractCommand;

/**
 * @author Kévin Gomez <contact@kevingomez.fr>
 */
class SqlBuildCommand extends WrappedCommand
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        parent::configure();

        $this
            ->setName('propel:sql:build')
            ->setDescription('Build SQL files')
            ->addOption('platform',  null, InputOption::VALUE_REQUIRED,  'The platform', PropelAbstractCommand::DEFAULT_PLATFORM)
            ->addOption('connection', null, InputOption::VALUE_IS_ARRAY | InputOption::VALUE_OPTIONAL, 'Connection to use. Example: default, bookstore')
        ;
    }

    /**
     * {@inheritdoc}
     */
    protected function createSubCommandInstance()
    {
        return new \Propel\Generator\Command\SqlBuildCommand();
    }

    /**
     * {@inheritdoc}
     */
    protected function getSubCommandArguments(InputInterface $input)
    {
        $parameters = array();
        $buildProperties = $input->getOption('build.properties');
        $connectionOption = $input->getOption('connection');

        if (null !== $connectionOption) {
            $parameters['--connection'] = $connectionOption;
        }

        if (array_key_exists('propel.schema.dir', $buildProperties)) {
            $parameters['--input-dir'] = $buildProperties['propel.schema.dir'];
        }

        if (array_key_exists('propel.sql.dir', $buildProperties)) {
            $parameters['--output-dir'] = $buildProperties['propel.sql.dir'];
        }

        return $parameters;
    }
}
