<?php

namespace App\Models\Traits;

trait MainTrait
{
    use StatusOptionsTrait;

    public function scopeActive($query)
    {
        return $query->where($this->getTable().'.status', 1);
    }

    public function getImgAttribute()
    {
        return $this->image ? config('app.url').'/uploads/'.$this->image : null;
    }

    public function getImgsAttribute()
    {
        $imgs = collect($this->images);
        return $imgs->map(function ($img) {
			if (is_array($img)) {
				dd($this);
			}
            return config('app.url').'/'.$img;
        });
    }
}
