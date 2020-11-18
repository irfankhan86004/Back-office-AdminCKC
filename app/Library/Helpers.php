<?php

// Database size
function database_size()
{
    $result = DB::select('SHOW TABLE STATUS');
    $tables = [];
    $total_size = 0;
    foreach ($result as $row) {
        $total_size = $total_size + (($row->Data_length + $row->Index_length));
    }
    return octet_convert($total_size).' utilisés';
}

// Conversion auto des octets en Ko, Mo, Go
function octet_convert($value)
{
    $value = $value / 1024;
    if ($value<1024) {
        return number_format($value, 2, ',', '.').' Ko';
    } elseif ($value<=1048576) {
        return number_format($value / 1024, 2, ',', '.').' Mo';
    } else {
        return number_format($value / 1024 / 1024, 2, ',', '.').' Go';
    }
}

function format_date($date, $delimiteur = '/')
{
    if (round(substr($date, 0, 4)) == 0 || trim($date)=='') {
        return '-';
    } else {
        return substr($date, 8, 2).$delimiteur.substr($date, 5, 2).$delimiteur.substr($date, 0, 4);
    }
}

function date_to_mysql($date)
{
    // EX : 06/12/1900 -> 1900-12-06
    return substr($date, 6, 4).'-'.substr($date, 3, 2).'-'.substr($date, 0, 2);
}

function format_datetime($date)
{
    if (round(substr($date, 0, 4)) == 0 || trim($date)=='') {
        return '-';
    } else {
        return substr($date, 8, 2).'/'.substr($date, 5, 2).'/'.substr($date, 0, 4).' '.substr($date, 11, 2).'h'.substr($date, 14, 2);
    }
}

function picturesExtensions()
{
    return ['jpg','jpeg','png','gif'];
}

function getRobots()
{
    return [
        '' => 'index and follow',
        'index, nofollow' => 'index and nofollow',
        'noindex, follow' => 'noindex and follow',
        'noindex, nofollow' => 'noindex and nofollow',
    ];
}

function getModelClass($model)
{
    return str_replace('App\Models\\', '', $model);
}

function getClass($model)
{
    $c = '';
    $m = explode('_', $model);
    if (count($m)>1) {
        foreach ($m as $key=>$val) {
            $c .= ucfirst($val);
        }
    } else {
        $c = ucfirst($model);
    }
    $class = 'App\Models\\'.$c;

    return $class;
}

function statusString()
{
    return json_encode(array_merge(['' => 'Tous les status'], status()));
}

function status()
{
    return [
        'submitted' => 'Validés',
        'checked' => 'Vérifiés',
        'published' => 'Publié',
    ];
}

function return_bytes($val)
{
    $val = trim($val);
    $last = strtolower($val[strlen($val)-1]);
    $val = intval($val);
    switch ($last) {
        case 'g':
            $val *= 1024;
            // no break
        case 'm':
            $val *= 1024;
            // no break
        case 'k':
            $val *= 1024;
    }

    return str_replace(' ', '', str_replace(',00', '', octet_convert($val)));
}

function displayAdminStatus($status)
{
    if ($status == 'submitted') {
        $class = 'warning';
    } elseif ($status == 'checked') {
        $class = 'info';
    } elseif ($status == 'published') {
        $class = 'success';
    }
    return '<span class="label label-'.$class.'">'.ucfirst($status).'</span>';
}

function videosExtensions()
{
    return ['mp4','avi','flv','3gp','mov'];
}

function deleteDir($dirPath)
{
    if (! is_dir($dirPath)) {
        throw new InvalidArgumentException("$dirPath must be a directory");
    }
    if (substr($dirPath, strlen($dirPath) - 1, 1) != '/') {
        $dirPath .= '/';
    }
    $files = glob($dirPath . '*', GLOB_MARK);
    foreach ($files as $file) {
        if (is_dir($file)) {
            deleteDir($file);
        } else {
            unlink($file);
        }
    }
    rmdir($dirPath);
}

	function getMediaType($extension)
	{
	    $type = 'file';
	    $extension = trim(strtolower($extension));
	    if (in_array($extension, picturesExtensions())) {
	        $type = 'picture';
	    } elseif (in_array($extension, videosExtensions())) {
	        $type = 'video';
	    }
	    return $type;
	}
	
	function inputCheckbox($request, $entity, $field)
	{
		if (!isset($request->$field)) {
			$entity->$field = 0;
			$entity->save();
		}
	}
	
	function displayContent($page)
	{
		$includes = [
			'[SITEMAP]' => 'sitemap',
		];
		
		if ($page instanceof \App\Models\Page) {
			$content = $page->getAttr('text');
			
			foreach ($includes as $code => $viewName) {
				if (strstr($content, $code) !== false && view()->exists('shorts.'.$viewName)) {
					$view = view()->make('shorts.'.$viewName, ["page" => $page])->render();
					$content = str_replace($code, $view, $content);
				}
			}
		} else {
			$content = $page;
			
			foreach ($includes as $code => $viewName) {
				if (strstr($content, $code) !== false && view()->exists('shorts.'.$viewName)) {
					$view = view()->make('shorts.'.$viewName)->render();
					$content = str_replace($code, $view, $content);
				}
			}
		}
		
		return $content;
	}
