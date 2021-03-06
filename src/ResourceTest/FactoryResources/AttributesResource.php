<?php
/**
 * Created by PhpStorm.
 * User: diedegulpers
 * Date: 31/10/2018
 * Time: 17:49
 */

namespace Cell0\LGT\ResourceTest\FactoryResources;

use App\Models\Roles\Role;
use Cell0\LGT\ResourceTest\CanMessage;
use Cell0\LGT\ResourceTest\SetsResource;
use PHPUnit\Framework\TestCase;

class AttributesResource extends TestCase {

    use CanMessage,
        SetsResource;

    private $factory;

    protected $expected;

    public function __construct($factory)
    {
        $this->modelClass = $factory->getModelClass();
        $this->resourceClass = $factory->getResourceClass();
        $this->setResource();
        $this->expected = $factory->getAttributes();
    }

    public function assert_specs_met()
    {
        $this->its_response_has_all_attributes()
            ->its_response_has_the_expected_amount_of_attributes()
            ->its_values_matches_those_of_the_model();
    }

    /**
     * asserts all attributes specified can be found in the resource's response.
     */
    protected function its_response_has_all_attributes()
    {
        foreach ($this->expected as $attribute) {
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
        $this->assertEquals(count($this->expected), count($this->resourceResponse),
            $this->unexpected_attribute_amount($this->expected));
        return $this;
    }

    /**
     * asserts the non transformed values set on the response match those on the model exactly
     */
    protected function its_values_matches_those_of_the_model()
    {
        $unknownValues = array_diff($this->resourceResponse, $this->model->getAttributes());

        $this->assertEmpty($unknownValues, "found unknown values on the Resource: \n" .
            $this->prettyJson($unknownValues));
        return $this;
    }
}
