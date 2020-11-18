<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Media;
use Crypt;
use Image;
use Response;
use DB;

class MediaController extends Controller
{
    private function checkRobots($media)
    {
        if ($media->robots) {
            header('X-Robots-Tag: '.$media->robots, true);
        }
        return;
    }

    public function picture($id, $width, $height, $filename)
    {
        $picture = Media::find($id);
        if ($picture && $picture->type == 'picture') {
            $picture_path = config('paths.medias').$picture->name;
            if (file_exists($picture_path)) {
                if ($filename != $picture->formattedOriginalName()) {
                    abort(404);
                }
                $this->checkRobots($picture);
                $cache_save = false;
                if (!$this->checkPrivacy($picture)) {
                    abort(404);
                }
                header('Last-Modified: '.gmdate('D, d M Y H:i:s', strtotime($picture->updated_at)).' GMT');
                $content_type_header = 'Content-type: '.mime_content_type($picture_path);
                if (!request('force_download')) {
                    if ($picture->public) {
                        $cache_save = true;
                        $cache_folder_path = config('paths.cache').$picture->id.'/';
                        $cache_path = $cache_folder_path.$width.'x'.$height.'.'.$picture->extension;
                        if (!is_dir($cache_folder_path)) {
                            mkdir($cache_folder_path, 0777, true);
                        }
                        if (is_file($cache_path)) {
                            header($content_type_header);
                            readfile($cache_path);
                            die();
                        }
                    }
                } else {
                    header('Content-Transfer-Encoding: Binary');
                    header('Content-disposition: attachment; filename="'.$picture->original_name.'"');
                    header($content_type_header);
                    readfile($picture_path);
                    die();
                }
                $quality = $picture->extension == 'jpg' ? 75 : 90;
                $img = Image::make($picture_path)->fit($width, $height);
                if ($cache_save) {
                    $img->save($cache_path, $quality);
                }
                $response = Response::make($img->encode($picture->extension, $quality));
                $response->header('Content-Type', mime_content_type($picture_path));
                return $response;
            }
        }
        abort(404);
    }

    public function file($id, $filename)
    {
        $file = Media::find($id);
        if ($file && ($file->type == 'file' || $file->type == 'video')) {
            $file_path = config('paths.medias').$file->name;
            if (file_exists($file_path)) {
                if ($filename != $file->formattedOriginalName()) {
                    abort(404);
                }
                $this->checkRobots($file);
                if (!$this->checkPrivacy($file)) {
                    abort(404);
                }
                header('Content-type: '.mime_content_type($file_path));
                if (($file->extension != 'pdf' && !$file->isVideo()) || request('force_download')) {
                    header('Content-Transfer-Encoding: Binary');
                    header('Content-disposition: attachment; filename="'.$file->original_name.'"');
                }
                readfile($file_path);
                die();
            }
        }
        abort(404);
    }

    public function filePreview($id, $width, $height, $filename)
    {
        $file = Media::find($id);
        if ($file && $file->type == 'file' && $file->extension == 'pdf') {
            $file_path = config('paths.medias').$file->name;
            if (file_exists($file_path)) {
                $filename .= '.pdf';
                if ($filename != $file->formattedOriginalName()) {
                    abort(404);
                }
                $this->checkRobots($file);
                if (!$this->checkPrivacy($file)) {
                    abort(404);
                }
                $im = new \Imagick();
                $im->setResolution($width, $height);
                $im->readImage($file_path.'[0]');
                $im->setImageFormat('png');
                header('Content-Type: image/png');
                echo $im;
                die();
            }
        }
        abort(404);
    }

    public function userListing($crypted_key)
    {
        $user = $this->selectUser($crypted_key);
        if ($user) {
            $title = 'Medias listing';
            $description = 'A list of specific medias';
            $entries = ['' => 'All', '10' => '10', '25' => '25', '50' => '50', '100' => '100'];
            $medias = $user->allMedias(request('entries'), request('search'));
            return view('user-medias-listing', compact('user', 'title', 'description', 'entries', 'medias'));
        }
        abort(404);
    }

    private function selectModel($model, $crypted_key)
    {
        try {
            return $model::where(DB::raw('CONCAT(id,created_at)'), Crypt::decrypt($crypted_key))->first();
        } catch (\Exception $e) {
            return null;
        }
    }

    private function selectUser($crypted_key)
    {
        return $this->selectModel('App\Models\ExternalUser', $crypted_key);
    }

    private function selectMedia($crypted_key)
    {
        return $this->selectModel('App\Models\Media', $crypted_key);
    }

    private function checkPrivacy($media)
    {
        if (
            !$media->public
            &&
            !auth()->guard('admin')->check()
            &&
            (
                (!request('crypted_key') && !request('media_crypted_key'))
                ||
                (
                    request('crypted_key')
                    &&
                    (
                        !$this->selectUser(request('crypted_key'))
                        ||
                        !in_array($media->id, $this->selectUser(request('crypted_key'))->allMediasIds())
                    )
                )
                ||
                (
                    request('media_crypted_key')
                    &&
                    !$this->selectMedia(request('media_crypted_key'))
                )
            )
        ) {
            return false;
        }
        return true;
    }
}
