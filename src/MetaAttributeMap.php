<?php

namespace BreakDown\Core\Meta;

use BreakDown\Core\Meta\Utils\AssociativeList;
use Exception;

class MetaAttributeMap
{

    /**
     *
     * @var AssociativeList
     */
    protected $attributes = null;

    /**
     *
     * @var MetaAttribute
     */
    protected $surrogate;

    public function __construct()
    {
        $this->attributes = new AssociativeList();
    }

    public function getAttributes()
    {
        return $this->attributes;
    }

    public function getSurrogate()
    {
        return $this->surrogate;
    }

    /**
     *
     * @param string $attribute
     * @param string $column
     * @return MetaAttribute
     */
    public function attribute($attribute, $column)
    {
        $this->selectString = null;

        $entityAttribute = new MetaAttribute($attribute, $column);
        $this->attributes[$attribute] = $entityAttribute;
        return $entityAttribute;
    }

    /**
     *
     * @param type $attribute
     * @param type $column
     * @return MetaAttribute
     * @throws Exception
     */
    public function attributeSurrogate($attribute, $column)
    {
        if ($this->surrogate) {
            throw new Exception("Entity Meta can't have two surrogate columns.");
        }

        $entityAttribute = $this->attribute($attribute, $column);
        $entityAttribute->surrogate();

        $this->surrogate = $entityAttribute;

        return $entityAttribute;
    }

    public function countAttributes()
    {
        return $this->attributes->count();
    }

}
