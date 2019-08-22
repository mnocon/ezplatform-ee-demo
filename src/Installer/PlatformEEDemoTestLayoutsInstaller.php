<?php

/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
declare(strict_types=1);

namespace App\Installer;

use EzSystems\PlatformInstallerBundle\Installer\CoreInstaller;

class PlatformEEDemoTestLayoutsInstaller extends CoreInstaller
{
    use InstallerCommandExecuteTrait;

    public function importData(): void
    {
        $migrationCommands = [
            'cache:clear',
            'kaliop:migration:migrate --path=src/MigrationVersions/landing_page_tests.yml -n',
        ];

        foreach ($migrationCommands as $cmd) {
            $this->output->writeln(sprintf('executing migration: %s', $cmd));
            $this->executeCommand($this->output, $cmd, 0);
        }
    }

    public function importSchema()
    {
    }

    public function importBinaries()
    {
    }
}
