<?php

/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace Tests\App\Behat\PageElement\Blocks;

use EzSystems\EzPlatformPageBuilder\Tests\Behat\PageElement\Blocks\VideoBlock;

class DemoVideoBlock extends VideoBlock
{
    public function setDefaultTestingConfiguration(string $blockName): void
    {
        $this->setInputField('Name', $blockName);
        $this->addContent('Media/Images/Our Picks');
        $this->submitForm();
    }

    public function getDefaultPreviewData(): array
    {
        return ['parameter1' => '/var/site/storage/images/7/7/6/1/1677-1-eng-GB/Our-Picks.jpg'];
    }
}
