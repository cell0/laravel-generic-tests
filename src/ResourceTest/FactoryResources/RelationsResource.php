<?php
/**
 * Created by PhpStorm.
 * User: diedegulpers
 * Date: 14/11/2018
 * Time: 13:31
 */

namespace Cell0\LGT\ResourceTest\FactoryResources;


use Cell0\LGT\ResourceTest\CanMessage;

class RelationsResource extends AttributesResource
{
    use CanMessage;

    public function __construct($factory)
    {
        parent::__construct($factory);
        $this->relations = $factory->getRelations();
        $this->setResourceWithRelations();
        $this->expected = array_merge($factory->getAttributes(), $factory->getRelations());
        $this->resourceResponse = $this->responseWithRelations;
    }

    public function assert_specs_met()
    {
        $this->it_does_respond_with_loaded_relations();
        parent::assert_specs_met();
        $this->it_does_not_respond_with_not_loaded_relations();
    }

    public function its_values_matches_those_of_the_model()
    {
        $this->setResource();
        return parent::its_values_matches_those_of_the_model();
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
            $this->assertArrayHasKey($relationNameOnResource, $this->resourceResponse, $failure);
        }
    }
}
