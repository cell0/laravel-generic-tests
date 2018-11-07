<?php
/**
 * Created by PhpStorm.
 * User: diedegulpers
 * Date: 24/10/2018
 * Time: 17:02
 */

namespace Cell0\LGT\ResourceTest;

use Tests\TestCase;

/**
 * Class BaseResourceTestCase.
 *
 * Tests the response as generated by a Resource. Tests all standard attributes and relations of the response and calls
 * custom assert functions for non-standard attributes
 *
 * @package Tests\Unit\Resources
 *
 * @author Diede Gulpers <diede@cell-0.com>
 * @author TomasdePomas <tomas@cell-0.com>
 */
class SingleClassImplementation extends TestCase
{
    use CanMessage,
        Asserts,
        SetsResource;

    /**
     * @var string Namespaced ModelClass name.
     */
    protected $modelClass;

    /**
     * @var string Namespaced ResourceClass name.
     */
    protected $resourceClass;

    /**
     * @var array all expected resource attributes (except for relations)
     */
    protected $attributes;

    /**
     * @var EloquentModel the model instance.
     */
    private $model;

    /**
     * @var array transformed model as the resource returns it.
     */
    private $resourceResponse;

    /**
     * @var array attributes in the resource that are transformed.
     * A function should exist for these attributes: 'assert`CamelCasedAttributeName`($model, $resourceAttrValue)'
     */
    protected $transformations = [];

    /**
     * @var array Attributes that have a different name on the model.
     * Key indicating the key in the response, the value the attribute on the model.
     */
    protected $aliases = [];

    /**
     * @var array Relations expected to be present on the resource.
     * Aliased relationships can be indicated by key value pair, key indicating the key in the response, the value the
     * relationship on the model.
     */
    protected $relations = [];

    /**
     * @var string, contains the name of the current attribute evaluated
     */
    private $currentAttribute;


    /**
     * @var array transformed model with relationships loaded
     */
    private $responseWithRelations;

    /**
     * @var Model instance with the relations loaded on it
     */
    private $modelWithRelations;

    /**
     * BaseResourceTestCase constructor
     *
     * @param string|null $name
     * @param array $data
     * @param string $dataName
     *
     * @throws \Exception
     */
    public function __construct(string $name = null, array $data = [], string $dataName = '')
    {
        parent::__construct($name, $data, $dataName);
        if (empty($this->modelClass)
            || empty($this->resourceClass)
            || empty($this->attributes)
        ) {
            throw new MinimalRequirementException();
        }
    }

    /**
     * Prepare model and response
     *
     * @throws \ReflectionException
     */
    protected function setUp()
    {
        parent::setUp();
        $this->setResource();
        if(!empty($this->relations)){
            $this->setResourceWithRelations();
        }
    }

    /**
     * @test Tests all attributes of the resource response based on the specification
     */
    public function it_passes_the_spec()
    {
        $this->its_response_has_all_attributes()
            ->its_response_has_the_expected_amount_of_attributes()
            ->its_responses_non_transformed_values_match_those_on_the_model();
        if (!empty($this->aliases)) {
            $this->its_response_has_the_expected_aliased_attributes();
        }
        if (!empty($this->relations)) {
            $this->it_does_not_respond_with_not_loaded_relations();
            $this->it_does_respond_with_loaded_relations();
        }
        if (!empty($this->transformations)) {
            $this->its_response_has_the_expected_transformed_attributes();
        }
    }

    /**
     * asserts all attributes specified can be found in the resource's response.
     */
    protected function its_response_has_all_attributes()
    {
        foreach ($this->attributes as $attribute) {
            $this->assertArrayHasKey($attribute, $this->resourceResponse,
                $this->message("the resource has the wrong attribute:"));
        }
        return $this;
    }

    /**
     * asserts the response has the same amount as attributes expected
     */
    protected function its_response_has_the_expected_amount_of_attributes()
    {
        $expectedAttributes = array_merge($this->attributes, array_keys($this->aliases), $this->transformations);
        $expectedAttributes =  (!empty($this->relations)) ? array_merge($expectedAttributes, array_keys($this->relations)) : $expectedAttributes;
        $response = (!empty($this->relations)) ? $this->responseWithRelations : $this->resourceResponse;
        $this->assertEquals(count($expectedAttributes), count($response),
            $this->unexpected_attribute_amount());
        return $this;
    }

    /**
     * asserts the non transformed values set on the response match those on the model exactly
     */
    protected function its_responses_non_transformed_values_match_those_on_the_model()
    {
        $transformedAttributes = array_merge($this->transformations, array_keys($this->aliases), $this->relations);
        $nonTransformedAttributes = array_except($this->resourceResponse, $transformedAttributes);
        $unknownValues = array_diff($nonTransformedAttributes, $this->model->getAttributes());

        $this->assertEmpty($unknownValues, "found unknown values on the Resource: \n" .
            $this->prettyJson($unknownValues));
        return $this;
    }

    /**
     * asserts the values set on the response match their aliases on the model.
     */
    protected function its_response_has_the_expected_aliased_attributes()
    {
        foreach ($this->aliases as $alias => $modelAttribute) {
            $this->assertEquals($this->model->$modelAttribute, $this->resourceResponse[$alias],
                'alias: ' . $alias . ' does not correspond with attribute: ' . $modelAttribute
            );
        }
    }

    /**
     * asserts all relationships are not returned when they have not been eager loaded.
     */
    protected function it_does_not_respond_with_not_loaded_relations()
    {
        foreach ($this->relations as $relation) {
            $this->assertArrayNotHasKey($relation, $this->resourceResponse,
                "the resource's relation was loaded when it shouldn't be: $relation");
        }
    }

    /**
     * asserts all relationships are present as expected in the resource response
     */
    protected function it_does_respond_with_loaded_relations()
    {
        $failure = $this->message("the resource's relation was not loaded when it should have:");
        foreach ($this->relations as $resourceRelation => $modelRelation) {
            $relationNameOnResource = !is_numeric($resourceRelation) ? $resourceRelation : $modelRelation;
            $this->assertArrayHasKey($relationNameOnResource, $this->responseWithRelations, $failure);
        }
    }

    /**
     * calls a assert function for each transformed attributes:
     * 'assert`CamelCasedAttributeName`($model, $resourceAttrValue)'
     */
    protected function its_response_has_the_expected_transformed_attributes()
    {
        foreach ($this->transformations as $attribute) {
            $this->currentAttribute = $attribute;
            $functionName = 'assert' . ucfirst(camel_case($attribute));
            if(!method_exists($this, $functionName)){
                $this->assertEquals($this->modelWithRelations->{$attribute}, $this->responseWithRelations[$attribute],
                    "No assert function specified to check transformed attributed $attribute.");
            } else {
                $this->$functionName($this->modelWithRelations, $this->responseWithRelations[$attribute]);
            }
        }
    }
}
