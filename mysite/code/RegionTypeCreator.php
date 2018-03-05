<?php

use GraphQL\Type\Definition\Type;
use SilverStripe\GraphQL\TypeCreator;

class RegionTypeCreator extends TypeCreator
{
    public function attributes()
    {
        return [
            'name' => 'Region'
        ];
    }
    
    public function fields()
    {
        return [
            'ID' => ['type' => Type::nonNull(Type::id()), 'description' => 'The Primary Key of this region'],
            'Title' => ['type' => Type::string(), 'description' => 'The region title or name'],
            'Description' => ['type' => Type::string(), 'description' => 'A description of the region'],
        ];
    }
}