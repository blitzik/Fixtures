<?php declare(strict_types=1);

namespace blitzik\Fixtures\DI;

use blitzik\Fixtures\Commands\LoadBasicDataCommand;
use blitzik\Fixtures\IFixtureProvider;
use Nette\DI\CompilerExtension;

class FixturesExtension extends CompilerExtension
{
    public function loadConfiguration(): void
    {
        $cb = $this->getContainerBuilder();

        $loadBasicDataCommand = $cb->addDefinition($this->prefix('loadBasicDataCommand'));
        $loadBasicDataCommand->setClass(LoadBasicDataCommand::class);
        $loadBasicDataCommand->addTag('kdyby.console.command');
    }


    public function beforeCompile(): void
    {
        $cb = $this->getContainerBuilder();

        $loadBasicDataCommand = $cb->getDefinition($this->prefix('loadBasicDataCommand'));

        //-- fixtures
        foreach ($this->compiler->getExtensions() as $extension) {
            if (!$extension instanceof IFixtureProvider) {
                continue;
            }

            foreach ($extension->getDataFixtures() as $directory => $fixturesClassNames) {
                foreach ($fixturesClassNames as $fixtureClassName) {
                    $loadBasicDataCommand->addSetup('addFixture', [$fixtureClassName]);
                }
            }
        }
    }

}