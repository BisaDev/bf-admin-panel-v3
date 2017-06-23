<?php

namespace Brightfox\Traits;
use Brightfox\Tag;

trait HasTags
{
    public function getTagsToSync($tag_array)
    {
        $tags_to_sync = [];
        foreach ($tag_array as $tag_name) {
            $tag = Tag::firstOrCreate(['name' => $tag_name]);
            array_push($tags_to_sync, $tag->id);
        }
        return $tags_to_sync;
    }
}