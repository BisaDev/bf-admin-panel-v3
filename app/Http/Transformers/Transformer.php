<?php

namespace Brightfox\Http\Transformers;

abstract class Transformer
{
    public function transformCollection($items)
    {
        return $items->transform(function ($item, $key) {
            return $this->transform($item);
        })->toArray();
    }

    public abstract function transform($item);
}
