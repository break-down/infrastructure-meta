<?php

namespace BreakDown\Core\Meta;

use BreakDown\Core\Entity\State\EntityStateFixed;

class MetaAttributeType extends EntityStateFixed
{

    const STATE = 10;
    const STRING = 20;
    const INTEGER = 30;
    const FLOAT = 40;
    const BOOLEAN_YES_NO = 50;
    const BOOLEAN_INTEGER = 51;
    const DATETIME = 60;
    const JSON = 70;
    const JSON_SERIALIZABLE = 80;
    const VALUE_OBJECT = 90;

    public static function getCodeNameMap()
    {
        return [
            self::STATE => 'State',
            self::STRING => 'String',
            self::INTEGER => 'Integer',
            self::FLOAT => 'Float',
            self::BOOLEAN_YES_NO => 'Boolean Yes No',
            self::BOOLEAN_INTEGER => 'Boolean Integer',
            self::DATETIME => 'Datetime',
            self::JSON => 'Json',
            self::JSON_SERIALIZABLE => 'Json Serializable',
            self::VALUE_OBJECT => 'Value Object',
        ];
    }

    public function toState()
    {
        $this->setCode(self::STATE);
    }

    public function toString()
    {
        $this->setCode(self::STRING);
    }

    public function toInteger()
    {
        $this->setCode(self::INTEGER);
    }

    public function toFloat()
    {
        $this->setCode(self::FLOAT);
    }

    public function toBooleanYesNo()
    {
        $this->setCode(self::BOOLEAN_YES_NO);
    }

    public function toBooleanInteger()
    {
        $this->setCode(self::BOOLEAN_INTEGER);
    }

    public function toDatetime()
    {
        $this->setCode(self::DATETIME);
    }

    public function toJson()
    {
        $this->setCode(self::JSON);
    }

    public function toJsonSerializable()
    {
        $this->setCode(self::JSON_SERIALIZABLE);
    }

    public function toValueObject()
    {
        $this->setCode(self::VALUE_OBJECT);
    }

    public function isState()
    {
        return $this->getCode() == self::STATE;
    }

    public function isString()
    {
        return $this->getCode() == self::STRING;
    }

    public function isInteger()
    {
        return $this->getCode() == self::INTEGER;
    }

    public function isFloat()
    {
        return $this->getCode() == self::FLOAT;
    }

    public function isBooleanYesNo()
    {
        return $this->getCode() == self::BOOLEAN_YES_NO;
    }

    public function isBooleanInteger()
    {
        return $this->getCode() == self::BOOLEAN_INTEGER;
    }

    public function isDatetime()
    {
        return $this->getCode() == self::DATETIME;
    }

    public function isJson()
    {
        return $this->getCode() == self::JSON;
    }

    public function isJsonSerializable()
    {
        return $this->getCode() == self::JSON_SERIALIZABLE;
    }

    public function isValueObject()
    {
        return $this->getCode() == self::VALUE_OBJECT;
    }

}
