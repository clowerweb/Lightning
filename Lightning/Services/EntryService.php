<?php

namespace Lightning\Services;

use Lightning\Models\EntryModel;

class EntryService extends BaseService
{
    protected EntryModel $entryModel;

    public function __construct()
    {
        parent::__construct();
        $this->entryModel = new EntryModel();
    }

    public function createPage(int $authorId, string $title, string $slug, string $content): int
    {
        // For now, we'll hardcode the entry type ID for a page as 1
        $entryTypeId = 1;
        $pageContent = ['title' => $title, 'content' => $content];
        return $this->entryModel->createEntry($entryTypeId, $authorId, $slug, $pageContent);
    }

    public function getPageBySlug(string $slug): ?array
    {
        $entry = $this->entryModel->getEntryBySlug($slug);
        if ($entry) {
            $entry['content'] = json_decode($entry['content'], true);
        }
        return $entry;
    }
}
