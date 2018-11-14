<?php
/**
 * Created by PhpStorm.
 * User: diedegulpers
 * Date: 14/11/2018
 * Time: 17:03
 */

namespace Tests\SupportingFiles;

use \Illuminate\Database\Eloquent\Model;

class Laser extends Model
{
    // - has
    protected $attributes = [
        'heat'      => 'test',
        'thickness' => 'test',
        'color'     => 'test',
        'pet_name'  => 'test',
    ];
}
