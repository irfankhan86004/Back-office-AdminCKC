<?php

namespace App\Traits;

use App\Models\Media;
use App\Models\MediaType;

trait MorphManyMedias
{
    public function medias()
    {
        return $this->morphMany('App\Models\MediaType', null, 'type', 'type_id')
                    ->join('medias', 'medias_types.media_id', '=', 'medias.id')
                    ->orderBy('medias_types.position', 'ASC')
                    ->select('medias_types.*');
    }

    public function getMedias()
    {
        $medias = [];
        foreach ($this->medias as $m) {
            $media = $m->media;
            if ($media) {
                $medias[] = $media;
            }
        }
        return $medias;
    }

    public function getMediasTypes()
    {
        return $this->medias;
    }

    private function mediaQuery($type, $position = null)
    {
        $get_media = $this->medias();
        if ($type) {
            $get_media = $get_media->where('medias.type', $type);
        }
        if ($position) {
            $get_media = $get_media->skip($position - 1)->limit(1);
        }
        return $get_media;
    }

    public function firstMedia($type = null)
    {
        $media = $this->mediaQuery($type)->first();
        return $media ? $media->media : null;
    }

    public function selectMedia($type, $position = 1)
    {
        $media = $this->mediaQuery($type, $position)->first();
        return $media ? $media->media : null;
    }

    public function mediasGallery($type = null, $all = false)
    {
        $medias = [];
        $firstMedia = $this->firstMedia($type);
        foreach ($this->getMedias() as $m) {
            if ((!$type || $m->type == $type) && (($m->id != $firstMedia->id && !$all) || $all) && !$m->hasCategory('backgrounds')) {
                $medias[] = $m;
            }
        }
        return $medias;
    }

    public function backgroundPicture()
    {
        foreach ($this->getMedias() as $m) {
            if ($m->type == 'picture' && $m->hasCategory('backgrounds')) {
                return $m;
            }
        }
        return null;
    }

    public function picturePreview($width, $height, $text = 'ECA Group', $position = 1)
    {
        $picture = $this->selectMedia('picture', $position);
        if ($picture) {
            return $picture->route($width, $height);
        } else {
            return 'https://placeholdit.imgix.net/~text?txtsize=25&bg=0089c5&txt='.urlencode($text).'&w='.$width.'&h='.$height.'&txtcolor=fff';
        }
    }

    public function picturePreviewAttribute($attribute, $position = 1)
    {
        $return = '';
        $firstPicture = $this->selectMedia('picture', $position);
        if ($firstPicture) {
            $return = $firstPicture->{$attribute};
        }
        return $return;
    }

    public function picturePage($big = false)
    {
        $width = 450;
        $height = 250;
        $logo = asset('images/logo-search.jpg');
        if ($big) {
            $width = 800;
            $height = 400;
            $logo = asset('images/logo-search-big.jpg');
        }
        if (get_class($this) == 'App\Models\Page' && $this->category) {
            $category_media = $this->category->firstMedia('picture');
            if ($category_media) {
                return $category_media->route($width, $height);
            }
        }
        $media = $this->firstMedia('picture');
        if ($media) {
            $dimensions = explode('x', $media->resolution);
            if (isset($dimensions[0]) && $dimensions[0] > 100) {
                return $media->route($width, $height);
            }
        }
        return $logo;
    }
}
