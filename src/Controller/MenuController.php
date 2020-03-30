<?php
/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use eZ\Publish\API\Repository\SearchService;
use App\QueryType\MenuQueryType;
use Twig\Environment;

class MenuController extends AbstractController
{
    /** @var \eZ\Publish\API\Repository\SearchService */
    protected $searchService;

    /** @var \App\QueryType\MenuQueryType */
    protected $menuQueryType;

    /** @var int */
    protected $topMenuParentLocationId;

    /** @var array */
    protected $topMenuContentTypeIdentifier;

    public function __construct(
        SearchService $searchService,
        MenuQueryType $menuQueryType,
        $topMenuParentLocationId,
        $topMenuContentTypeIdentifier
    ) {
        $this->searchService = $searchService;
        $this->menuQueryType = $menuQueryType;
        $this->topMenuParentLocationId = $topMenuParentLocationId;
        $this->topMenuContentTypeIdentifier = $topMenuContentTypeIdentifier;
    }

    /**
     * Renders top menu with child items.
     *
     * @param string $template
     * @param string|null $pathString
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function getChildNodesAction($template, $pathString = '')
    {
        $query = $this->menuQueryType->getQuery([
            'parent_location_id' => $this->topMenuParentLocationId,
            'included_content_type_identifier' => $this->topMenuContentTypeIdentifier,
        ]);

        $locationSearchResults = $this->searchService->findLocations($query);

        $menuItems = [];
        foreach ($locationSearchResults->searchHits as $hit) {
            $menuItems[] = $hit->valueObject;
        }

        $pathArray = $pathString ? explode("/", $pathString) : [];

        $content = '';
        $content = $this->renderView(
            $template, [
                'menuItems' => $menuItems,
                'pathArray' => $pathString,
            ]
        );

//        $response = new Response();
//        $response->setVary('X-User-Hash');
//
//        return $response;

        return new Response(sprintf('<div>%s, %s, %s</div>', $content, $template, $pathString), 200);

    }
}
