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

use Propel\Generator\Command\MigrationDiffCommand as BaseMigrationCommand;

/**
 * @author Phillip Brand
 */
class MigrationDiffCommand extends WrappedCommand
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        parent::configure();

        $this
            ->setName('propel:migration:diff')
            ->setDescription('Generate diff classes')
            ->addOption('input-dir', null, InputOption::VALUE_REQUIRED,  'The input directory')
            ->addOption('connection',       null, InputOption::VALUE_IS_ARRAY | InputOption::VALUE_OPTIONAL, 'Connection to use. Example: default, bookstore')
            ->addOption('migration-table',  null, InputOption::VALUE_REQUIRED,  'Migration table name', BaseMigrationCommand::DEFAULT_MIGRATION_TABLE)
            ->addOption('output-dir',       null, InputOption::VALUE_OPTIONAL,  'The output directory')
            ->addOption('table-renaming',   null, InputOption::VALUE_NONE,  'Detect table renaming', null)
            ->addOption('editor',           null, InputOption::VALUE_OPTIONAL,  'The text editor to use to open diff files', null)
        ;
    }

    /**
     * {@inheritdoc}
     */
    protected function createSubCommandInstance()
    {
        return new BaseMigrationCommand();
    }

    /**
     * {@inheritdoc}
     */
    protected function getSubCommandArguments(InputInterface $input)
    {
        $parameters = array(
            '--migration-table' => $input->getOption('migration-table'),
            '--table-renaming' => $input->getOption('table-renaming'),
            '--editor' => $input->getOption('editor'),
        );

        $buildProperties = $input->getOption('build.properties');
        $connectionOption = $input->getOption('connection');

        if (!empty($connectionOption)) {
            $parameters['--connection'] = $this->getConnections($connectionOption);
        } else {
            $parameters['--connection'] = $this->getDefaultConnectionDsn();
        }

        if (array_key_exists('propel.migration.dir', $buildProperties)) {
            $parameters['--output-dir'] = $buildProperties['propel.migration.dir'];
        }

        if (array_key_exists('propel.schema.dir', $buildProperties)) {
            $parameters['--input-dir'] = $buildProperties['propel.schema.dir'];
        }

        return $parameters;
    }
}
