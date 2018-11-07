<?php
/**
 * Created by PhpStorm.
 * User: diedegulpers
 * Date: 31/10/2018
 * Time: 17:17
 */

namespace Cell0\LGT\ResourceTest;

use PHPUnit\Framework\TestCase;
use Cell0\LGT\ResourceTest\FactoryResources\PositiveResource as AliasesResource;
use Cell0\LGT\ResourceTest\FactoryResources\PositiveResource as RelationsResource;
use Cell0\LGT\ResourceTest\FactoryResources\PositiveResource as TransformationsResource;
use Cell0\LGT\ResourceTest\FactoryResources\PositiveResource as RelationsTransformationsResource;
use Cell0\LGT\ResourceTest\FactoryResources\PositiveResource as RelationsAliasesResource;
use Cell0\LGT\ResourceTest\FactoryResources\PositiveResource as RelationsAliasesTransformationsResource;
use Cell0\LGT\ResourceTest\FactoryResources\AttributesResource;

class FactoryImplementation extends TestCase implements ResourceTestable
{
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

    public function getAttributes()
    {
        return $this->attributes;
    }

    /**
     * @test 
     * Tests all attributes of the resource response based on the specification
     */
    public function it_passes_the_spec()
    {
        switch (true) {
            case ($this->has_aliases() &&  $this->misses_relations() && $this->misses_transformations()):
                $testcase = new AliasesResource();
                break;
            case ($this->misses_aliases() && $this->has_relations() && $this->misses_transformations()):
                $testcase = new RelationsResource();
                break;
            case ($this->misses_aliases() && $this->misses_relations() && $this->has_transformations()):
                $testcase = new TransformationsResource();
                break;
            case ($this->misses_aliases() && $this->has_relations() && $this->has_transformations()):
                $testcase = new RelationsTransformationsResource();
                break;
            case ($this->has_aliases() && $this->has_relations() && $this->misses_transformations()):
                $testcase = new RelationsAliasesResource();
                break;
            case ($this->has_aliases() && $this->has_relations() && $this->has_transformations()):
                $testcase = new RelationsAliasesTransformationsResource();
                break;
            default:
                $testcase = new AttributesResource($this);
        }

        $testcase->assert_specs_met();
    }

    private function misses_aliases()
    {
        return empty($this->aliases);
    }

    private function misses_transformations()
    {
        return empty($this->transformations);
    }

    private function misses_relations()
    {
        return empty($this->relations);
    }

    private function has_aliases()
    {
        return !empty($this->aliases);
    }

    private function has_relations()
    {
        return !empty($this->relations);
    }

    private function has_transformations()
    {
        return !empty($this->transformations);
    }
}
