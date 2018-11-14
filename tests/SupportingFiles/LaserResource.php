<?php
/**
 * Created by PhpStorm.
 * User: diedegulpers
 * Date: 14/11/2018
 * Time: 17:05
 */

namespace Tests\SupportingFiles;

use Illuminate\Http\Resources\Json\Resource;

class LaserResource extends Resource
{
    // - has heat, thickness, color.

    public function toArray($request)
    {
        return [
            'heat'      => $this->heat,
            'thickness' => $this->thickness,
            'color'     => $this->color
        ];
    }

}
