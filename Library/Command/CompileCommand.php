<?php

/**
 * This file is part of the Zephir.
 *
 * (c) Zephir Team <team@zephir-lang.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Zephir\Command;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Zephir\Command\CompileCommand
 *
 * Compile a Zephir extension.
 *
 * @package Zephir\Command
 */
class CompileCommand extends ContainerAwareCommand implements DevelopmentModeAwareInterface
{
    use DevelopmentModeAwareTrait;

    protected function configure()
    {
        $this
            ->setName('compile')
            ->setDescription('Compile a Zephir extension')
            ->addOption(
                'backend',
                null,
                InputOption::VALUE_REQUIRED,
                'Used backend to compile extension',
                'ZendEngine3'
            )
            ->addOption('dev', null, InputOption::VALUE_NONE, 'Compile the extension in development mode')
            ->addOption('no-dev', null, InputOption::VALUE_NONE, 'Compile the extension in production mode')
            ->setHelp($this->getDevelopmentModeHelp());
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        /** TODO: Process flags
        -f([a-z0-9\-]+)     Enables compiler optimizations
        -fno-([a-z0-9\-]+)  Disables compiler optimizations
        -w([a-z0-9\-]+)     Turns a warning on
        -W([a-z0-9\-]+)     Turns a warning off
         */

        // TODO: Move all the stuff from the compiler
        $this->compiler->compile(
            $this->isDevelopmentModeEnabled($input)
        );

        return 0;
    }

    /**
     * {@inheritdoc}
     *
     * @return string
     */
    public function getDevelopmentModeHelp()
    {
        return <<<EOT
Compile a Zephir extension.

Using "--dev" option will force compiling the extension in development mode
(debug symbols and no optimizations). An extension compiled with debugging symbols means
you can run a program or library through a debugger and the debugger's output will be user
friendlier. These debugging symbols also enlarge the program or library significantly.

Note: Zephir development mode will be enabled silently if your PHP binary was compiled in
a debug configuration.

In some cases, we would like to get production ready extension even if the PHP binary was
compiled in a debug configuration. Use "--no-dev" option to achieve this behavior.
EOT;
    }
}