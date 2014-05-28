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

/**
 * @author Kévin Gomez <contact@kevingomez.fr>
 */
class SqlInsertCommand extends WrappedCommand
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        parent::configure();

        $this
            ->setName('propel:sql:insert')
            ->setDescription('Insert SQL statements')
            ->addOption('connection', null, InputOption::VALUE_OPTIONAL, 'Connection to use. Example: default, bookstore', null)
            ->addOption('output-dir', null, InputOption::VALUE_REQUIRED, 'Directory to output SQL files to')
            ;
    }

    /**
     * {@inheritdoc}
     */
    protected function createSubCommandInstance()
    {
        return new \Propel\Generator\Command\SqlInsertCommand();
    }

    /**
     * {@inheritdoc}
     */
    protected function getSubCommandArguments(InputInterface $input)
    {
        $parameters = array();
        $buildProperties = $input->getOption('build.properties');
        $connectionOption = $input->getOption('connection');

        $defaultConnection = $this->getDefaultConnection();

        $parameters['--connection'] = [
            sprintf(
                'mainframe=%s;user=%s;password=%s;',
                $defaultConnection['connection']['dsn'],
                $defaultConnection['connection']['user'],
                $defaultConnection['connection']['password']
            )
        ];

        if (null !== $connectionOption) {
            $parameters['--connection'] = $connectionOption;
        }

        if (array_key_exists('propel.sql.dir', $buildProperties)) {
            $parameters['--sql-dir'] = $buildProperties['propel.sql.dir'];
        }

        return $parameters;
    }
}
