<?php

namespace BreakDown\Core\Meta\Meta\Transformers;

use BreakDown\Core\Meta\MetaAttribute;
use BreakDown\Core\Meta\MetaAttributeMap;
use BreakDown\Protocols\Entity\IEntity;
use BreakDown\Protocols\Entity\IEntityState;
use BreakDown\Protocols\Entity\IEntityValueObjectSerializable;

class EntityFromRow extends AbstractTransformer
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

    public function transform(IEntity $entity, $row)
    {
        foreach ($this->map->getAttributes() as $attribute) {
            /* @var $attribute MetaAttribute */

            if ($attribute->getType()->isState()) {
                $this->handleState($attribute, $entity, $row);
            } else if ($attribute->getType()->isJsonSerializable()) {
                $this->handleJsonSerializable($attribute, $entity, $row);
            } else {

                $value = $this->rowValueFor($entity, $row, $attribute->getAttribute());
                if ($value === null) {
                    continue;
                }

                if ($attribute->getType()->isJson()) {
                    $value = $this->handleJson($value);
                } else if ($attribute->getType()->isInteger()) {
                    $value = $this->handleInteger($value);
                } else if ($attribute->getType()->isFloat()) {
                    $value = $this->handleFloat($value);
                } else if ($attribute->getType()->isBooleanYesNo()) {
                    $value = $this->handleBooleanYesNo($value);
                } else if ($attribute->getType()->isBooleanInteger()) {
                    $value = $this->handleBooleanInteger($value);
                } else if ($attribute->getType()->isDatetime()) {
                    $value = $this->handleDateTime($value);
                }

                if ($attribute->getType()->isDatetime()) {
                    $attribute->setEntityAttributeIfNotNull($entity, $value);
                } else {
                    $attribute->setEntityAttribute($entity, $value);
                }
            }
        }

        return $entity;
    }

    protected function handleState(MetaAttribute $attribute, IEntity $entity, $row)
    {
        $stateInstance = $attribute->getEntityAttribute($entity);

        if ($stateInstance instanceof IEntityState) {
            $stateInstance->setCode($this->rowValueFor($entity, $row, $attribute->getAttribute()));
        } else {
            $entity->setIsReadOnly(true);
        }
    }

    protected function handleJsonSerializable(MetaAttribute $attribute, IEntity $entity, $row)
    {
        $valueInstance = $attribute->getEntityAttribute($entity);

        if ($valueInstance instanceof IEntityValueObjectSerializable) {
            $value = $this->rowValueFor($entity, $row, $attribute->getAttribute());
            $value = json_decode($value, true);
            if ($value !== null && $valueInstance->isDeSerializeable($value)) {
                $valueInstance->deSerialize($value);
            } else {
                $entity->setIsReadOnly(true);
            }
        } else {
            $entity->setIsReadOnly(true);
        }
    }

    protected function handleJson($value)
    {
        $value = json_decode($value, true);
        if ($value === null) {
            $value = null;
        }
        return $value;
    }

    protected function handleInteger($value)
    {
        if (is_numeric($value)) {
            $value = (int) $value;
        }
        return $value;
    }

    protected function handleFloat($value)
    {
        if (is_numeric($value)) {
            $value = (float) $value;
        }
        return $value;
    }

    protected function handleBooleanYesNo($value)
    {
        if ($value == 'Yes') {
            return true;
        } else {
            return false;
        }
    }

    protected function handleBooleanInteger($value)
    {
        return ( $value == 1 ? true : false);
    }

    protected function handleDateTime($value)
    {
        if ($value) {
            $value = $this->getValidDatetime($value);
        }
        return $value;
    }

}
