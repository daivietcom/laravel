<?php namespace VnSource\Traits;

use Illuminate\Database\Eloquent\Collection;

trait ExtendTrait {

    protected $allColumnName;

    public function setExtend($key, $value)
    {
        if (isset($this->{$this->extendColumnName}[$key]))
        {
            $tempExtend = $this->{$this->extendColumnName};
            $tempExtend[$key] = $value;
            $this->{$this->extendColumnName} = $tempExtend;
            unset($tempExtend);
        }
        else
        {
            $this->{$this->extendColumnName} = array_add($this->{$this->extendColumnName}, $key, $value);
        }
    }


    public function deleteExtend($key)
    {
        if (isset($this->{$this->extendColumnName}[$key]))
        {
            $tempExtend = $this->{$this->extendColumnName};
            array_forget($tempExtend, $key);
            $this->{$this->extendColumnName} = $tempExtend;
            unset($tempExtend);
        }
    }

    public function getExtend($key)
    {
        if (isset($this->{$this->extendColumnName}[$key]))
        {
            return $this->{$this->extendColumnName}[$key];
        }
        else {
            return null;
        }
    }

    public function getAttribute($key)
    {
        $inAttributes = array_key_exists($key, $this->attributes);

        if ($inAttributes || $this->hasGetMutator($key))
        {
            return $this->getAttributeValue($key);
        }

        if (array_key_exists($key, $this->relations))
        {
            return $this->relations[$key];
        }

        $camelKey = camel_case($key);
        if (method_exists($this, $camelKey))
        {
            return $this->getRelationshipFromMethod($key, $camelKey);
        }

        return $this->getExtend($key);
    }

    protected function getAllColumnsNames() {

        if (!isset($this->columnName))
        {
            if (empty($this->allColumnName)) return $this->allColumnName = $this->getConnection()->getSchemaBuilder()->getColumnListing($this->getTable());
            return $this->allColumnName;
        }
        return $this->columnName;
    }

    public function setAttribute($key, $value)
    {
        $columns = $this->getAllColumnsNames();
        if (in_array($key, $columns)) {
            parent::setAttribute($key, $value);
        } else {
            $this->setExtend($key, $value);
        }
    }

    public function __unset($key)
    {
        unset($this->attributes[$key]);
        unset($this->relations[$key]);
        $this->deleteExtend($key);
    }

    public function scopeLeftJoinExtend($query, $table, $key, $value, $field = true) {
        $extend = $this->extendColumnName;
        $tb = $table;
        if(strpos($table, ' as ') != -1) {
            $tb = trim(explode(' as ', $table)[1]);
        }

        if (!$field) {
            $value = '\''.$value.'\'';
        }

        return $query->leftJoin($table, function ($join) use ($tb, $key, $value, $extend) {
            $join->on(\DB::raw($tb.'.'.$extend.' REGEXP CONCAT(\'"'.$key.'":["]{0,1}\', '.$value.', \'["]{0,1}[,}]{1}\')'), \DB::raw(''), \DB::raw(''));
        });
    }

    public function scopeWhereExtend($query, $key, $value, $field = true) {

        if (!$field) {
            $value = '\''.$value.'\'';
        }

        return $query->where($this->extendColumnName, 'REGEXP', '"'.$key.'":["]{0,1}'.$value.'["]{0,1}[,}]{1}');
    }

    public function scopeOrWhereExtend($query, $key, $value, $field = true) {

        if (!$field) {
            $value = '\''.$value.'\'';
        }

        return  $query->orWhere($this->extendColumnName, 'REGEXP', '"'.$key.'":["]{0,1}'.$value.'["]{0,1}[,}]{1}');
    }

    public function scopeWhereInExtend($query, $key, $values) {

        return $query->where($this->extendColumnName, 'REGEXP', '"'.$key.'":["]{0,1}('.implode('|', $values).')["]{0,1}[,}]{1}');
    }
}
