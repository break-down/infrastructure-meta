<?php

namespace BreakDown\Core\Meta;

use BreakDown\Core\Meta\Meta\Transformers\EntityFromRow;
use BreakDown\Core\Meta\Meta\Transformers\EntityToRow;
use BreakDown\Core\Meta\Meta\Transformers\ToSelect;
use BreakDown\Protocols\Entity\IEntity;
use Exception;

abstract class Meta
{

    /**
     *
     * @var string
     */
    protected $type;

    /**
     *
     * @var string
     */
    protected $table;

    /**
     *
     * @var string
     */
    protected $prefix;

    /**
     *
     * @var MetaAttributeMap
     */
    protected $map;

    /**
     *
     * @var EntityToRow
     */
    protected $to;

    /**
     *
     * @var EntityFromRow
     */
    protected $from;

    /**
     *
     * @var string
     */
    protected $selectString = null;

    public function __construct($typeClassName, $table)
    {
        $this->type($typeClassName);
        $this->table = $table;

        $this->map = new MetaAttributeMap();
        $this->initialize($this->map);

        $this->to = new EntityToRow($this->map);
        $this->from = new EntityFromRow($this->map);
    }

    abstract protected function initialize(MetaAttributeMap $map);

    public function getTable()
    {
        return $this->table;
    }

    public function getPrefix()
    {
        return $this->prefix;
    }

    public function getTableWithPrefix()
    {
        if ($this->prefix) {
            return "{$this->table} AS {$this->prefix}";
        }

        return $this->table;
    }

    public function getSelect()
    {
        if (!$this->selectString) {
            $selectTransformer = new ToSelect($this->map);
            $selectTransformer->prefix($this->prefix);
            $this->selectString = $selectTransformer->transform();
        }
        return $this->selectString;
    }

    public function type($typeClassName)
    {
        if (!class_exists($typeClassName)) {
            throw new Exception("{$typeClassName} was not found.");
        }

        $this->type = $typeClassName;
        return $this;
    }

    public function table($table)
    {
        $this->table = $table;
        return $this;
    }

    public function prefix($prefix)
    {
        $this->prefix = $prefix;
        return $this;
    }

    /**
     *
     * @return MetaAttribute
     */
    public function getSurrogateAttribute()
    {
        return $this->map->getSurrogate();
    }

    /**
     *
     * @return IEntity
     */
    protected function createEntityInstance()
    {
        $type = $this->type;
        return new $type();
    }

    /**
     *
     * @return IEntity
     */
    public function fromRow($row)
    {
        $entity = $this->createEntityInstance();

        $this->from->prefix($this->prefix);
        $this->from->transform($entity, $row);

        return $entity;
    }

    /**
     *
     * @return array
     */
    public function toRow(IEntity $entity)
    {
        $this->to->prefix($this->prefix);
        return $this->to->transform($entity);
    }

}
