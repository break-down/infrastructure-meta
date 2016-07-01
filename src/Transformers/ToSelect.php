<?php

namespace BreakDown\Core\Meta\Meta\Transformers;

use BreakDown\Core\Meta\MetaAttribute;
use BreakDown\Core\Meta\MetaAttributeMap;

class ToSelect extends AbstractTransformer
{

    /**
     *
     * @var MetaAttributeMap
     */
    protected $map;

    public function __construct(MetaAttributeMap $map)
    {
        $this->map = $map;
    }

    public function transform()
    {
        $select = [];

        foreach ($this->map->getAttributes() as $attribute) {
            /* @var $attribute MetaAttribute */

            $attributeKey = $attribute->getAttribute();
            if ($this->prefix) {
                $attributeKey = ucfirst($attribute->getAttribute());
                $select[] = "{$this->prefix}.{$attribute->getColumn()} AS {$this->prefix}{$attributeKey}";
            } else {
                $select[] = "{$attribute->getColumn()} AS {$attributeKey}";
            }
        }

        if (count($select)) {
            return implode(', ', $select);
        }

        return null;
    }

}
