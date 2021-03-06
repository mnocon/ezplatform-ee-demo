<?php
/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace App\Twig;

use App\PremiumContent\HtmlRenderer;
use App\User\UserGroups;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;
use Twig\TwigFilter;

/**
 * Twig helper for premium content.
 */
class PremiumContentExtension extends AbstractExtension
{
    /** @var \App\PremiumContent\HtmlRenderer */
    private $htmlRenderer;

    /** @var \App\User\UserGroups */
    private $userGroups;

    /** @var int[] */
    private $allowedUserGroupsLocationIds;

    /** @var bool */
    private $hasAccess;

    /**
     * @param \App\PremiumContent\HtmlRenderer $htmlRenderer
     * @param \App\User\UserGroups $userGroups
     * @param array $allowedUserGroupsLocationIds
     */
    public function __construct(
        HtmlRenderer $htmlRenderer,
        UserGroups $userGroups,
        array $allowedUserGroupsLocationIds
    ) {
        $this->htmlRenderer = $htmlRenderer;
        $this->userGroups = $userGroups;
        $this->allowedUserGroupsLocationIds = $allowedUserGroupsLocationIds;
    }

    /**
     * Returns the name of the extension.
     *
     * @return string The extension name
     */
    public function getName()
    {
        return 'premium_content_extension';
    }

    /**
     * Returns a list of functions to add to the existing list.
     *
     * @return Twig_SimpleFunction[]
     */
    public function getFunctions()
    {
        return [
            new TwigFunction('ez_has_access_to_premium_content', [$this, 'hasAccessToPremiumContent']),
        ];
    }

    /**
     * Returns a list of filters to add to the existing list.
     *
     * @return Twig_SimpleFilter[]
     */
    public function getFilters()
    {
        return [
            new TwigFilter('previewPremiumContent', [$this, 'previewPremiumContent'], ['is_safe' => ['html']]),
        ];
    }

    /**
     * Allows to display certain number of paragraphs.
     *
     * @param string $document
     * @param int $numberOfDisplayedElements
     *
     * @return string
     */
    public function previewPremiumContent(string $document, int $numberOfDisplayedElements = 2): string
    {
        return $this->htmlRenderer->renderElements($document, $numberOfDisplayedElements);
    }

    /**
     * Checks if user has access to premium content.
     *
     * @return bool
     */
    public function hasAccessToPremiumContent(): bool
    {
        if (null !== $this->hasAccess) {
            return $this->hasAccess;
        }

        return $this->hasAccess = $this->userGroups->isCurrentUserInOneOfTheGroups($this->allowedUserGroupsLocationIds);
    }
}
