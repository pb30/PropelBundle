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

use Propel\Generator\Command\MigrationMigrateCommand as BaseMigrationCommand;

/**
 * @author Kévin Gomez <contact@kevingomez.fr>
 */
class MigrationMigrateCommand extends WrappedCommand
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        parent::configure();

        $this
            ->setName('propel:migration:migrate')
            ->setDescription('Execute all pending migrations')

            ->addOption('connection',       null, InputOption::VALUE_IS_ARRAY | InputOption::VALUE_OPTIONAL, 'Connection to use. Example: default, bookstore')
            ->addOption('migration-table',  null, InputOption::VALUE_REQUIRED,  'Migration table name', BaseMigrationCommand::DEFAULT_MIGRATION_TABLE)
            ->addOption('output-dir',       null, InputOption::VALUE_OPTIONAL,  'The output directory')
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

        return $parameters;
    }
}
