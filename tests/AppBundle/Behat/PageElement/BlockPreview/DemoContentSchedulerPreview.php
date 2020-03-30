<?php

/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace Tests\App\Behat\PageElement\BlockPreview;

use EzSystems\EzPlatformPageBuilder\Tests\Behat\PageElement\BlockPreview\ContentSchedulerPreview;

class DemoContentSchedulerPreview extends ContentSchedulerPreview
{
    private $previewType;

    public function setExpectedData(array $data)
    {
        $this->expectedItems = explode(',', $data['parameter1']);
        $this->previewType = $data['parameter2'];

        if ($this->previewType === 'card') {
            $this->fields['itemsSelector'] = sprintf('%s %s', $this->blockSelector, '.card .card-title');
        }
    }
}
