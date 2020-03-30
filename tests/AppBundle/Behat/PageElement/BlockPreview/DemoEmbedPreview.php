<?php

/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace Tests\App\Behat\PageElement\BlockPreview;

use EzSystems\EzPlatformAdminUi\Behat\Helper\UtilityContext;
use EzSystems\EzPlatformPageBuilder\Tests\Behat\PageElement\BlockPreview\EmbedPreview;

class DemoEmbedPreview extends EmbedPreview
{
    public function __construct(UtilityContext $context)
    {
        parent::__construct($context);
        $this->fields['headerSelector'] = sprintf('%s %s', $this->blockSelector, 'article.embed h5');
    }
}
