<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Courier extends Model
{
    public function getPhotosAttribute($photos)
    {
        if (is_string($photos)) {
            return json_decode($photos, true);
        }

        return $photos;
    }

    public function setPhotosAttribute($photos)
    {
        if (is_array($photos)) {
            $this->attributes['photos'] = json_encode($photos);
        }
    }
}
