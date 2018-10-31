<?php
/**
 * Created by PhpStorm.
 * User: diedegulpers
 * Date: 31/10/2018
 * Time: 17:19
 */

namespace Cell0\LGT\ResourceTest;


trait Asserts
{
    /**
     * Asserts that a given or current attribute can become a null value on the response.
     *
     * @param null $attribute
     *
     * @throws \ReflectionException
     */
    protected function assertIsNullable($attribute = null)
    {
        $modelAttribute = ($attribute) ?: $this->currentAttribute;
        $model = factory($this->modelClass)->create([$modelAttribute => null]);
        $response = $this->setResourceResponse($model);
        $this->assertNull($response[$this->currentAttribute]);
    }
}
