<?php


namespace App\Http\Controllers\Api\Admin;

use Mnabialek\LaravelEloquentFilter\Filters\SimpleQueryFilter;

class UserFilter extends SimpleQueryFilter
{
    protected $simpleFilters = ['search'];
    protected $simpleSorts = ['name', 'email', 'created_at'];

    /**
     * @return bool
     */
    public function hasFilterParamter()
    {
        $contains = $this->parser->getFilters()->contains(function ($filter) {
            return $filter->getField() === 'search' && !empty($filter->getValue());
        });
        return $contains;
    }

    /**
     * @param $value
     */
    protected function applySearch($value)
    {
        $this->query->where('name', 'LIKE', "%$value%")
            ->orWhere('email', 'LIKE', "%$value%");
    }
}
