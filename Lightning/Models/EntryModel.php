<?php

namespace Lightning\Models;

class EntryModel extends BaseModel
{
    protected string $table = 'entries';

    public function getEntryBySlug(string $slug): ?array
    {
        return $this->db->fetch("SELECT * FROM {$this->table} WHERE slug = ?", [$slug]);
    }

    public function createEntry(int $entryTypeId, int $authorId, string $slug, array $content): int
    {
        $contentJson = json_encode($content);
        $this->db->execute(
            "INSERT INTO {$this->table} (entry_type_id, author_id, slug, content) VALUES (?, ?, ?, ?)",
            [$entryTypeId, $authorId, $slug, $contentJson]
        );
        return $this->db->lastInsertId();
    }
}
