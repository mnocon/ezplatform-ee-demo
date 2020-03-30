<?php

/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
declare(strict_types=1);

namespace App\Event\Listener;

use App\User\UserGroups;
use eZ\Publish\Core\MVC\Symfony\Security\Authorization\Attribute;
use EzSystems\EzPlatformAdminUi\Menu\Event\ConfigureMenuEvent;
use EzSystems\EzRecommendationClient\Config\CredentialsResolverInterface;
use Knp\Menu\ItemInterface;
use Knp\Menu\Util\MenuManipulator;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

class RenderMenuListener
{
    private const PERSONALIZATION_MODULE = 'personalization';
    private const PERSONALIZATION_FUNCTION = 'view';
    private const EZTAGS_MENU_ITEM = 'eztags';

    /** @var \Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface */
    private $authorizationChecker;

    /** @var \EzSystems\EzRecommendationClient\Config\CredentialsCheckerInterface */
    private $credentialsChecker;

    /** @var \App\User\UserGroups */
    private $userGroups;

    private $translator;

    public function __construct(
        AuthorizationCheckerInterface $authorizationChecker,
        CredentialsResolverInterface $credentialsChecker,
        UserGroups $userGroups,
        TranslatorInterface $translator
    ) {
        $this->authorizationChecker = $authorizationChecker;
        $this->credentialsChecker = $credentialsChecker;
        $this->userGroups = $userGroups;
        $this->translator = $translator;
    }

    /** @param \EzSystems\EzPlatformAdminUi\Menu\Event\ConfigureMenuEvent $event */
    public function renderMenu(ConfigureMenuEvent $event): void
    {
        $menu = $event->getMenu();
        $manipulator = new MenuManipulator();

        $this->configureEzTags($menu, $manipulator);
//        $this->configurePersonalization($menu, $manipulator);
    }

    /**
     * @param \Knp\Menu\ItemInterface $item
     * @param \Knp\Menu\Util\MenuManipulator $menuManipulator
     */
    private function configureEzTags(ItemInterface $item, MenuManipulator $menuManipulator): void
    {
        if ($item->getChild(self::EZTAGS_MENU_ITEM)) {
            $menuManipulator->moveToLastPosition($item[self::EZTAGS_MENU_ITEM]);
        }
    }

    /**
     * @param \Knp\Menu\ItemInterface $item
     * @param \Knp\Menu\Util\MenuManipulator $menuManipulator
     */
    private function configurePersonalization(ItemInterface $item, MenuManipulator $menuManipulator): void
    {
        $attribute = new Attribute(self::PERSONALIZATION_MODULE, self::PERSONALIZATION_FUNCTION);

        if ($this->authorizationChecker->isGranted($attribute) && $this->credentialsChecker->hasCredentials()) {
            $item->addChild(self::PERSONALIZATION_MODULE, [
                'label' => $this->translator->trans('Personalization'),
                'uri' => 'https://admin.yoochoose.net/?ez.no=1#/scenarios/',
                'linkAttributes' => [
                    'target' => '_blank',
                    'rel' => 'noopener noreferrer'
                ]
            ]);

            $menuManipulator->moveToLastPosition($item[self::PERSONALIZATION_MODULE]);
        }
    }
}
