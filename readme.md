#Laravel Generic Tests : LGT

The goal of this package is to enable Laravel developers of bigger applications to be 
able to easily test the generic parts of their Laravel application in a quick but 
test driven manner. 

### ResourceTestCase Main idea
 
We want a generic class to test all the JSON resource responses 
from our API. Since our responses are always a striped down
version of a corresponding Eloquent model with some modifications
we may assume the following:  

if we know the Resource we want to test and the 
model we want to test we start validating that all attributes
on a response that are not modified are the same value as the 
model. All other attributes on the resource will need to be 
specified by the resourceTestCase to indicate in what manner we
expect them to be modified. 

This resource tester will run a single test called `it_passes_the_spec()`
in it, based on the given spec a custom set of assertions are made 
to assert the Resource fits the spec. 

### Usage

##### Basic
The most generic responses only have values and keys corresponding
to the originating Model. 

To set this up you need to extend the `ResourceTestCase` and 
set the `$modelclass`, `$resourceclass` and the `$attributes` you 
expect the Resource to have. 

We decided on declaring what you expect to be the attribute
to allow for a test driven approach.


```php
class Response extends BaseResourceTestCase
{
    protected $modelClass = Model::class;
    protected $resourceClass = ModelResource::class;

    protected $attributes = [
        'id',
        'name',
        'lasers',
    ];
}
```

##### Aliases
A more advanced situation is encountered when keys on the 
response are a alias for the actual attribute on a model. To add it, on the class as shown above add 
an array aliases on which the key of the alias is the name of the attribute on the resource and the 
value is the attribute name on the model.

```php
    protected $aliases = [
        'full_name' => 'name'
    ];

```

##### Relations

For Resources that use relations we make that relations only are show
once the resource has loaded them. To test for relationships add a array
to your class shown in the first example. 

The array can either be a value list, each value being the name of the  relation, 
or it can be a key value list, when the relations are aliases or a combination
of the two. 

What we assert is that the relation name shows up on the resource response, we
do not assert if the values are returned correctly, since we expect that you 
use other resources to build up these values, and these resource already have 
their own tests. 

```php
    protected $relations = [
        // key name on resource => attribute name on model 
        'alias_name' => 'relation_name',
        // key name and attribute name are the same. 
        'relation_name'
    ];
```

##### Transformations

For Resources that transform data we have a transformations array. 
A transformation is any event where the data from the model that has
become modified when when set on a Resource. This is the case for 
instance for dates that become formatted or for accessors.   

```php
    protected $transformations = [
        'order_date',
        'full_name'
    ];
```
Since we expect that accessors are the most common use case for transformations, 
the default behavior of this array is used to check if a attribute with the same
name exists on the model.  

In the more complex cases, such as date formats, you will need to write
custom methods used to assert the transformation happens as expected. 
To do this, you need to camelcase the transformation key given 
and prepend it with the word assert leading to this: 

 `assert'CamelCasedAttributeName'($model, $resourceAttrValue)'`
 
 With the above example the order function would be: 

 ```php
    public function assertOrderDate($model, $resourceAttrValue){
        $this->assertEquals($resourceAttrValue, $model->created_at->format('Y-d-m h:m:s');
    }
 ```
##### Misc 

The only assertion function our current solution adds is the function: 

`assertIsNullable($attribute)`

This function checks if a given attribute can be null on the resource 
response. This function can only be called in transformation assertoins you make,
and if you do not pass it an attribute, it checks if the attribute 
currently being evaluated is nullable. 


##### Future Development
We plan to take the next steps with the resource tester:   
  
1. Enable it through a service provider, so it is easier to switch out. 
2. Apply the ResourceTester to the full suite of resources available. 
3. Use the factory pattern to pick what tester to use 
(currently we need a lot of `if` checks to know what we need to test)
4. Enable tests for resources that have transformations but have no relations 
