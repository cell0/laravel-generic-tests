<?php
/**
 * Created by PhpStorm.
 * User: diedegulpers
 * Date: 31/10/2018
 * Time: 17:17
 */

namespace Cell0\LGT\ResourceTest;


use Tests\TestCase;
use Cell0\LGT\ResourceTest\FactoryResources\PositiveResource as TransformationsResource;
use Cell0\LGT\ResourceTest\FactoryResources\PositiveResource as RelationsTransformationsResource;
use Cell0\LGT\ResourceTest\FactoryResources\PositiveResource as RelationsAliasesResource;
use Cell0\LGT\ResourceTest\FactoryResources\PositiveResource as RelationsAliasesTransformationsResource;
use Cell0\LGT\ResourceTest\FactoryResources\AttributesResource;
use Cell0\LGT\ResourceTest\FactoryResources\AliasesResource;
use Cell0\LGT\ResourceTest\FactoryResources\RelationsResource;

/**
 * Class FactoryImplementation
 *
 * TODO fix the dependency on the Tests\TestCase;
 *
 * @package Cell0\LGT\ResourceTest
 *
 * @author Diede Gulpers <diede@cell-0.com>
 */
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

    public function getRelations()
    {
        return $this->relations;
    }

    public function getAttributes()
    {
        return $this->attributes;
    }

    public function getModelClass()
    {
        return $this->modelClass;
    }

    public function getResourceClass()
    {
        return $this->resourceClass;
    }

    /**
     * @test 
     * Tests all attributes of the resource response based on the specification
     */
    public function it_passes_the_spec()
    {
        switch (true) {
            case ($this->has_aliases() &&  $this->misses_relations() && $this->misses_transformations()):
                $testcase = new AliasesResource($this);
                break;
            case ($this->misses_aliases() && $this->has_relations() && $this->misses_transformations()):
                $testcase = new RelationsResource($this);
                break;
            case ($this->misses_aliases() && $this->misses_relations() && $this->has_transformations()):
                $testcase = new TransformationsResource($this);
                break;
            case ($this->misses_aliases() && $this->has_relations() && $this->has_transformations()):
                $testcase = new RelationsTransformationsResource($this);
                break;
            case ($this->has_aliases() && $this->has_relations() && $this->misses_transformations()):
                $testcase = new RelationsAliasesResource($this);
                break;
            case ($this->has_aliases() && $this->has_relations() && $this->has_transformations()):
                $testcase = new RelationsAliasesTransformationsResource($this);
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
