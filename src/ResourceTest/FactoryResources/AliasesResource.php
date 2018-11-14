<?php
/**
 * Created by PhpStorm.
 * User: diedegulpers
 * Date: 14/11/2018
 * Time: 13:23
 */

namespace Cell0\LGT\ResourceTest\FactoryResources;


class AliasesResource extends AttributesResource
{

    public function __construct($factory)
    {
        parent::__construct($factory);
    }

    public function assert_specs_met()
    {
        parent::assert_specs_met();
        $this->its_response_has_the_expected_aliased_attributes();
    }

    /**
     * TODO: add testcase for this situation
     *
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

}
