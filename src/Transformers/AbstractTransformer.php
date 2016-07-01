<?php

namespace BreakDown\Core\Meta\Meta\Transformers;

use BreakDown\Protocols\Entity\IEntity;
use DateTime;

abstract class AbstractTransformer
{

    /**
     *
     * @var string
     */
    protected $prefix;

    public function prefix($prefix)
    {
        $this->prefix = $prefix;
        return $this;
    }

    protected function getValidDatetime($value)
    {
        $date = DateTime::createFromFormat('Y-m-d H:i:s', $value);
        $lastErrors = DateTime::getLastErrors();
        if ($lastErrors['warning_count'] == 0 && $lastErrors['error_count'] == 0) {
            return $date;
        }
        return null;
    }

    protected function rowValueFor(IEntity $entity, &$row, $key)
    {

        $valueKey = $this->rowKey($key);

        if (property_exists($row, $valueKey)) {
            return $row->$valueKey;
        }

        $entity->setIsReadOnly(true);
        $entity->setReadOnlyReason("{$entity->getReadOnlyReason()}, {$key}");

        return null;
    }

    protected function rowKey($key)
    {
        $valueKey = $key;
        if ($this->prefix) {
            $key = ucwords($key);
            $valueKey = $this->prefix . $key;
        }
        return $valueKey;
    }

}
