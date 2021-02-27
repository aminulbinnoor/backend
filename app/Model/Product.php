<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $table = "products";

    public function getImagesAttribute()
    {
      return json_decode($this->attributes['images']);
    }

}
