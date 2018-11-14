<?php
/**
 * Created by PhpStorm.
 * User: diedegulpers
 * Date: 07/11/2018
 * Time: 14:58
 */

namespace Cell0\LGT\ResourceTest;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

trait SetsResource
{
    /**
     * Builds up the resource's response.
     *
     * @throws \ReflectionException
     */
    protected function setResource()
    {
        $this->model = factory($this->modelClass)->make(['id' => 0]);
        $this->resourceResponse = $this->setResourceResponse($this->model);
    }

    /**
     * Creates models for all expected relations and sets it to the resource
     *
     * @param null $model
     *
     * @throws \ReflectionException
     */
    protected function setResourceWithRelations($model = null)
    {
        $model = ($model) ?: factory($this->modelClass)->create();
        $this->modelWithRelations = $model;

        foreach ($this->relations as $modelRelation) {
            $relationClassName = get_class($model->$modelRelation()->getRelated());
            $relatedModel = factory($relationClassName)->make();
            $model->$modelRelation()->create($relatedModel->toArray());
            $model->load($modelRelation);
        }
        $this->responseWithRelations = $this->setResourceResponse($model);
    }

    /**
     * Pass the model through the resource and store the response
     *
     * @param $model
     *
     * @return array
     *
     * @throws \ReflectionException
     */
    private function setResourceResponse(Model $model)
    {
        $resource = new $this->resourceClass($model);
        $data = $resource->response()->getData()->data;
        return (array) $data;
    }
}
