<?php

namespace BreakDown\Core\Meta;

class MetaAttribute
{

    /**
     *
     * @var boolean
     */
    protected $surrogate = false;

    /**
     *
     * @var boolean
     */
    protected $primary = false;

    /**
     *
     * @var string
     */
    protected $attribute;

    /**
     *
     * @var string
     */
    protected $column;

    /**
     *
     * @var MetaAttributeType
     */
    protected $type;

    /**
     *
     * @var string
     */
    protected $getter;

    /**
     *
     * @var string
     */
    protected $setter;

    public function __construct($attribute, $column)
    {
        $this->attribute = $attribute;
        $this->column = $column;
        $this->type = new MetaAttributeType();

        $this->getter = "get" . ucfirst($attribute);
        $this->setter = "set" . ucfirst($attribute);
    }

    public function getSurrogate()
    {
        return $this->surrogate;
    }

    public function getPrimary()
    {
        return $this->primary;
    }

    public function getAttribute()
    {
        return $this->attribute;
    }

    public function getColumn()
    {
        return $this->column;
    }

    public function getGetter()
    {
        return $this->getter;
    }

    public function getSetter()
    {
        return $this->setter;
    }

    /**
     *
     * @return MetaAttributeType
     */
    public function getType()
    {
        return $this->type;
    }

    public function surrogate()
    {
        $this->surrogate = true;
        return $this;
    }

    public function primary()
    {
        $this->primary = true;
        return $this;
    }

    public function asState()
    {
        $this->type->toState();
        return $this;
    }

    public function asString()
    {
        $this->type->toString();
        return $this;
    }

    public function asInteger()
    {
        $this->type->toInteger();
        return $this;
    }

    public function asFloat()
    {
        $this->type->toFloat();
        return $this;
    }

    public function asBooleanYesNo()
    {
        $this->type->toBooleanYesNo();
        return $this;
    }

    public function asBooleanInteger()
    {
        $this->type->toBooleanInteger();
        return $this;
    }

    public function asDatetime()
    {
        $this->type->toDatetime();
        return $this;
    }

    public function asJson()
    {
        $this->type->toJson();
        return $this;
    }

    public function asJsonSerializable()
    {
        $this->type->toJsonSerializable();
        return $this;
    }

//    public function asValueObject()
//    {
//        $this->type->toValueObject();
//        return $this;
//    }

    public function getEntityAttribute($entity)
    {
        $getter = $this->getter;
        return $entity->$getter();
    }

    public function setEntityAttribute($entity, $value)
    {
        $setter = $this->setter;
        return $entity->$setter($value);
    }

    public function setEntityAttributeIfNotNull($entity, $value)
    {
        if ($value === null) {
            return;
        }
        $setter = $this->setter;
        return $entity->$setter($value);
    }

}
