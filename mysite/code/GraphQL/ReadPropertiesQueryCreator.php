<?php

use GraphQL\Type\Definition\ResolveInfo;
use GraphQL\Type\Definition\Type;
use SilverStripe\GraphQL\OperationResolver;
use SilverStripe\GraphQL\QueryCreator;

class ReadPropertiesQueryCreator extends QueryCreator implements OperationResolver
{
    public function attributes()
    {
        return [
            'name' => 'readProperties'
        ];
    }

    /**
     * The readProperties schema accepts on attribute, ID, to filter on resolve.
     *
     * @return array
     */
    public function args()
    {
        return [
            'ID' => ['type' => Type::int()]
        ];
    }

    public function type()
    {
        return Type::listOf($this->manager->getType('Property'));
    }
    
//     public function fields()
//     {
//         return [
//             'ID' => ['type' => Type::nonNull(Type::id()), 'description' => 'The Primary Key of this property'],
//             'Title' => ['type' => Type::string(), 'description' => 'The property title or name'],
//             'Bedrooms' => ['type' => Type::int(), 'description' => 'The number of bedrooms this property has'],
//             'Bathrooms' => ['type' => Type::int(), 'description' => 'The number of bathrooms this property has'],
//             'AvailableStart' => ['type' => Type::string(), 'description' => 'The date this property is available from'],
//             'AvailableEnd' => ['type' => Type::string(), 'description' => 'The date this property is available to'],
//             'Description' => ['type' => Type::string(), 'description' => 'A description of the property'],
//         ];
//     }

    public function resolve($object, array $args, $context, ResolveInfo $info)
    {
        $property = Property::singleton();
        if (!$property->canView($context['currentUser'])) {
            throw new \InvalidArgumentException(sprintf(
                '%s view access not permitted',
                Property::class
            ));
        }
        $list = Property::get();

        // Optional filtering by properties
        if (isset($args['ID'])) {
            $list = $list->filter('ID:ExactMatch', (int) $args['ID']);
        }

        return $list;
    }
}
