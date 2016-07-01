<?php

namespace BreakDown\Core\Meta\Meta\Transformers;

use BreakDown\Core\Meta\MetaAttribute;
use BreakDown\Core\Meta\MetaAttributeMap;
use BreakDown\Protocols\Entity\IEntity;
use BreakDown\Protocols\Entity\IEntityState;
use BreakDown\Protocols\Entity\IEntityValueObjectSerializable;
use DateTime;

class EntityToRow extends AbstractTransformer
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

    public function transform(IEntity $entity)
    {
        $row = [];

        foreach ($this->map->getAttributes() as $attribute) {
            /* @var $attribute MetaAttribute */

            if ($attribute->getSurrogate()) {
                continue;
            }

            $value = $attribute->getEntityAttribute($entity);

            if ($attribute->getType()->isState()) {
                $value = $this->handleState($value);
            } else if ($attribute->getType()->isJsonSerializable()) {
                $value = $this->handleJsonSerializable($value);
            } else {
                if ($attribute->getType()->isJson()) {
                    $value = $this->handleJson($value);
                } else if ($attribute->getType()->isBooleanYesNo()) {
                    $value = $this->handleBooleanYesNo($value);
                } else if ($attribute->getType()->isBooleanInteger()) {
                    $value = $this->handleBooleanInteger($value);
                } else if ($attribute->getType()->isDatetime()) {
                    $value = $this->handleDateTime($value);
                }
            }

            $row[$attribute->getColumn()] = $value;
        }

        return $row;
    }

    protected function handleState($value)
    {
        if ($value instanceof IEntityState) {
            $value = $value->getCode();
        } else {
            $value = null;
        }
        return $value;
    }

    protected function handleJsonSerializable($value)
    {
        if ($value instanceof IEntityValueObjectSerializable) {
            $value = $value->serialize();
            if ($value) {
                $value = json_encode($value);
            } else {
                $value = null;
            }
        } else {
            $value = null;
        }
        return $value;
    }

    protected function handleJson($value)
    {
        if (is_array($value)) {
            $value = json_encode($value);
        } else {
            $value = null;
        }
        return $value;
    }

    protected function handleDateTime($value)
    {
        if ($value instanceof DateTime) {
            $value = $value->format('Y-m-d H:i:s');
        } else {
            $value = null;
        }
        return $value;
    }

    protected function handleBooleanYesNo($value)
    {
        if ($value === true) {
            return 'Yes';
        } else {
            return 'No';
        }
    }

    protected function handleBooleanInteger($value)
    {
        return ( $value === true ? 1 : 0 );
    }

}
