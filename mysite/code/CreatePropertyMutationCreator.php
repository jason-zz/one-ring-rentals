<?php use GraphQL\Type\Definition\Type;
use SilverStripe\GraphQL\MutationCreator;
use SilverStripe\GraphQL\OperationResolver;

class CreatePropertyMutationCreator extends MutationCreator implements OperationResolver
{
    public function attributes()
    {
        return [
            'name' => 'createProperty',
            'description' => 'Creates a property without permissions or group assignments'
        ];
    }
    
    public function type()
    {
        return $this->manager->getType('Property');
    }
    
    public function args()
    {
        return [
            'Title' => ['type' => Type::nonNull(Type::string())],
            'Bedrooms' => ['type' => Type::int(), 'description' => 'The number of bedrooms this property has'],
            'Bathrooms' => ['type' => Type::int(), 'description' => 'The number of bathrooms this property has'],
            'AvailableStart' => ['type' => Type::string(), 'description' => 'The date this property is available from'],
            'AvailableEnd' => ['type' => Type::string(), 'description' => 'The date this property is available to'],
            'Description' => ['type' => Type::string(), 'description' => 'A description of the property'],
        ];
    }
    
    /**
     * @param mixed $object
     * @param array $args
     * @param mixed $context
     * @param \GraphQL\Type\Definition\ResolveInfo $info
     * @return Property
     */
    public function resolve($object, array $args, $context, \GraphQL\Type\Definition\ResolveInfo $info)
    {
        $property = Property::create($args);
        
        $property->write();
        
        return $property;
    }
}