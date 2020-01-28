<?php

namespace Brightfox\Http\Transformers;

use Brightfox\TaggingLog;

class TaggingLogTransformer {
    function transform($users) {
        $logs = TaggingLog::all();
        foreach ($users as $user) {
            $tags = 0;
            foreach($logs as $log) {
                if($user->id == $log->instructor_id) {
                    $tags++;
                }
            }
            $user->tags = $tags;
        }
        return $users;
    }
}