<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Courier extends Model
{

    public function getPhotosAttribute($photos)
    {
        return json_decode($photos, true);
    }

    public function setPhotosAttribute($photos)
    {
        \Log::info(json_encode($photos));
        if (is_array($photos)) {
            $this->attributes['photos'] = json_encode($photos);
        }
    }

    public function votes()
    {

        return $this->hasMany(Vote::class);
    }
}
