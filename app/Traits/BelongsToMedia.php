<?php

namespace App\Traits;

use Illuminate\Http\Request;
use App\Models\Media;
use App\Models\MediaType;
use Image;

trait BelongsToMedia
{
    public function media()
    {
        return $this->belongsTo('App\Models\Media', 'media_id');
    }

    public function setMedia(Request $request, $public = 1, $request_name = 'media', $object = 'media', $column = 'media_id')
    {
        if ($request->hasFile($request_name)) {
            if ($request->file($request_name)->isValid()) {
                $extension = $request->file($request_name)->getClientOriginalExtension();
                if (in_array(trim(strtolower($extension)), picturesExtensions())) {
                    $original_filename = $request->file($request_name)->getClientOriginalName();
                    $filename = uniqid().'-'. str_slug($original_filename);
                    $path = config('paths.medias').$filename;
                    $new_picture = Image::make($request->file($request_name))->orientate()->save($path);
                    if ($new_picture) {
                        $old_media_type = MediaType::where('media_id', $this->{$column})
                            ->where('type_id', $this->id)
                            ->where('type', get_class($this))
                            ->first();
                        if ($old_media_type) {
                            $old_media_type->delete();
                        }
                        $media = Media::create([
                            'name' => $filename,
                            'original_name' => $original_filename,
                            'media_name' => $original_filename,
                            'size' => $new_picture->filesize(),
                            'md5' => md5_file($path),
                            'resolution' => $new_picture->width().'x'.$new_picture->height(),
                            'extension' => $extension,
                            'type' => 'picture',
                            'admin_id' => auth()->guard('admin')->user()->id,
                            'status' => 'published',
                            'public' => $public,
                        ]);
                        MediaType::create([
                           'media_id' => $media->id,
                           'type_id' => $this->id,
                           'type' => get_class($this),
                        ]);
                        $this->update([$column => $media->id]);
                    }
                }
            }
        }
    }

    public function removeMedia($object = 'media', $column = 'media_id')
    {
        if ($this->{$object}) {
            $cache_path = config('paths.cache').$this->{$object}->id;
            if (is_dir($cache_path)) {
                deleteDir($cache_path);
            }
            $path_to_delete = config('paths.medias').$this->{$object}->name;
            if (file_exists($path_to_delete)) {
                if (is_dir($path_to_delete)) {
                    deleteDir($path_to_delete);
                } else {
                    unlink($path_to_delete);
                }
            }
            $this->update([$column => null]);
            $this->{$object}->delete();
        }
    }
}
