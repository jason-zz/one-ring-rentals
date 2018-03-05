<?php
use GraphQL\Type\Definition\Type;
use SilverStripe\GraphQL\Pagination\Connection;
use SilverStripe\GraphQL\Pagination\PaginatedQueryCreator;

class PaginatedReadPropertiesQueryCreator extends PaginatedQueryCreator
{
    public function createConnection()
    {
        return Connection::create('paginatedReadProperties')
        ->setConnectionType($this->manager->getType('Property'))
        ->setArgs([
            'Title' => [
                'type' => Type::string(),
                'description' => 'Filter props by title'
            ]
        ])
        ->setDefaultLimit(10)
        ->setConnectionResolver( function($obj, $args, $context){
            $props = Property::get();
            if( isset($args['Title']) ){
                $props = $props->filter('Title:StartsWith',$args['Title']);
            }
            return $props;
        } )
        ;
    }
}