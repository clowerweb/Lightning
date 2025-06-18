<?php

namespace Lightning\Controllers;

use Lightning\Services\EntryService;

class EntryController extends BaseController
{
    protected EntryService $entryService;

    public function __construct()
    {
        parent::__construct();
        $this->entryService = new EntryService();
    }

    public function createPage(array $args): array
    {
        // Assuming you have a way to get the current user's ID
        $authorId = 1; // Hardcoded for now
        $pageId = $this->entryService->createPage($authorId, $args['title'], $args['slug'], $args['content']);
        return ['id' => $pageId];
    }

    public function getPage(array $args): ?array
    {
        return $this->entryService->getPageBySlug($args['slug']);
    }
}
