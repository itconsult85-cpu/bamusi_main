<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class MigrateLegacyHomepageItems extends Migration
{
    public function up()
    {
        if (!$this->db->tableExists('homepage_section_items')) {
            return;
        }

        $items = $this->db->table('homepage_section_items');
        $now = date('Y-m-d H:i:s');
        $insert = static function (array $row) use ($items, $now): void {
            if ($items->where(['section_key' => $row['section_key'], 'item_key' => $row['item_key']])->countAllResults() > 0) {
                return;
            }
            $row['created_at'] = $now;
            $row['updated_at'] = $now;
            $items->insert($row);
        };

        if ($this->db->tableExists('programs')) {
            foreach ($this->db->table('programs')->orderBy('sort_order', 'ASC')->orderBy('id', 'ASC')->get()->getResultArray() as $row) {
                $insert(['section_key' => 'program', 'item_key' => $row['slug'], 'label' => $row['name'], 'label_en' => $row['name_en'], 'title' => $row['name'], 'title_en' => $row['name_en'], 'body' => $row['description'], 'body_en' => $row['description_en'], 'media_url' => $row['image_url'], 'options_json' => json_encode(['slug' => $row['slug'], 'division' => $row['division']], JSON_UNESCAPED_UNICODE), 'sort_order' => $row['sort_order'], 'published' => $row['published']]);
            }
        }
        if ($this->db->tableExists('about_values')) {
            foreach ($this->db->table('about_values')->orderBy('sort_order', 'ASC')->orderBy('id', 'ASC')->get()->getResultArray() as $row) {
                $insert(['section_key' => 'nilai', 'item_key' => 'value-' . $row['id'], 'label' => $row['label'], 'label_en' => $row['label_en'], 'title' => $row['label'], 'title_en' => $row['label_en'], 'body' => $row['description'], 'body_en' => $row['description_en'], 'sort_order' => $row['sort_order'], 'published' => $row['published']]);
            }
        }
        if ($this->db->tableExists('board_members')) {
            foreach ($this->db->table('board_members')->orderBy('group_order', 'ASC')->orderBy('member_order', 'ASC')->orderBy('sort_order', 'ASC')->get()->getResultArray() as $row) {
                $insert(['section_key' => 'board', 'item_key' => 'member-' . $row['id'], 'label' => $row['name'], 'label_en' => $row['name'], 'title' => $row['name'], 'title_en' => $row['name'], 'body' => $row['role'], 'body_en' => $row['role_en'], 'media_url' => $row['photo_url'], 'options_json' => json_encode(['group_name' => $row['group_name'], 'group_name_en' => $row['group_name_en'], 'group_order' => $row['group_order'], 'member_order' => $row['member_order']], JSON_UNESCAPED_UNICODE), 'sort_order' => $row['sort_order'], 'published' => $row['published']]);
            }
        }
        if ($this->db->tableExists('cms_items')) {
            foreach ($this->db->table('cms_items')->whereIn('kind', ['agenda', 'article'])->where('published', 1)->orderBy('created_at', 'DESC')->get()->getResultArray() as $row) {
                $section = $row['kind'] === 'agenda' ? 'agenda' : 'writing';
                $insert(['section_key' => $section, 'item_key' => $row['kind'] . '-' . $row['id'], 'label' => $row['title'], 'label_en' => $row['title_en'], 'title' => $row['title'], 'title_en' => $row['title_en'], 'body' => $row['summary'] ?: $row['body'], 'body_en' => $row['summary_en'] ?: $row['body_en'], 'url' => $row['url'] ?: ($row['kind'] === 'article' ? '/artikel/' . $row['id'] : '/agenda/' . $row['id']), 'media_url' => $row['image_url'], 'options_json' => json_encode(['event_date' => $row['event_date'], 'category' => $row['category']], JSON_UNESCAPED_UNICODE), 'sort_order' => 0, 'published' => $row['published']]);
            }
        }
        if ($this->db->tableExists('partners')) {
            foreach ($this->db->table('partners')->orderBy('sort_order', 'ASC')->orderBy('id', 'ASC')->get()->getResultArray() as $row) {
                $insert(['section_key' => 'partners', 'item_key' => 'partner-' . $row['id'], 'label' => $row['name'], 'label_en' => $row['name_en'], 'title' => $row['name'], 'title_en' => $row['name_en'], 'url' => $row['website_url'], 'media_url' => $row['logo_url'], 'sort_order' => $row['sort_order'], 'published' => $row['published']]);
            }
        }
        if ($this->db->tableExists('section_links')) {
            foreach ($this->db->table('section_links')->where('section_key', 'about')->orderBy('sort_order', 'ASC')->orderBy('id', 'ASC')->get()->getResultArray() as $row) {
                $insert(['section_key' => 'about_links', 'item_key' => 'link-' . $row['id'], 'label' => $row['label'], 'label_en' => $row['label_en'], 'title' => $row['label'], 'title_en' => $row['label_en'], 'body' => $row['sublabel'], 'body_en' => $row['sublabel_en'], 'url' => $row['url'], 'sort_order' => $row['sort_order'], 'published' => $row['published']]);
            }
        }
    }

    public function down()
    {
        // Tidak menghapus item canonical karena dapat berisi edit baru dari CMS.
    }
}
