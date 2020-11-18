<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MediaCategory extends Model
{
    protected $table = 'medias_categories';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'parent_id', 'admin_id', 'position'];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [];

    public static function slugs()
    {
        return [
            '' => 'Sélectionnez une fonction',
            'backgrounds' => 'Médias d\'arrière plan',
        ];
    }

    public function parent()
    {
        return $this->belongsTo('App\Models\MediaCategory', 'parent_id');
    }

    public function children()
    {
        return $this->hasMany('App\Models\MediaCategory', 'parent_id')->orderBy('position', 'ASC');
    }

    public function admin()
    {
        return $this->belongsTo('App\Models\Admin', 'admin_id');
    }

    public function medias()
    {
        return $this->belongsToMany('App\Models\Media', 'medias_medias_categories', 'media_category_id', 'media_id');
    }

    public static function adminArbo()
    {
        $categories = [];
        foreach (self::whereNull('parent_id')->orderBy('position', 'ASC')->get() as $c) {
            $categories = $categories + self::adminCategoryArbo($c);
        }
        return $categories;
    }

    private static function adminCategoryArbo(MediaCategory $category, $prefix = ' ')
    {
        $categories[$category->id] = [
            'label' => $prefix.$category->name,
            'category' => $category,
        ];
        foreach ($category->children as $c) {
            if ($c == $category->children->first()) {
                $prefix .= ' - ';
            }
            $categories[$c->id] = [
                'label' => $prefix.$c->name,
                'category' => $c,
            ];
            if ($c->children->count()) {
                $categories = $categories + self::adminCategoryArbo($c, $prefix);
            }
        }
        return $categories;
    }

    public static function create_select($default = true)
    {
        $select = [];
        if ($default) {
            $select[''] = 'Pas de catégorie parente';
        }
        foreach (self::whereNull('parent_id')->orderBy('position', 'ASC')->get() as $c) {
            $select = $select + self::create_select_categories($c);
        }
        return $select;
    }

    private static function create_select_categories(MediaCategory $category, $prefix = '-')
    {
        $select[$category->id] = $prefix.' '.$category->name;
        foreach ($category->children as $c) {
            if ($c == $category->children->first()) {
                $prefix .= ' - ';
            }
            if ($c->medias->count()) {
            }
            $select[$c->id] = $prefix.$c->name;
            $select = $select + self::create_select_categories($c, $prefix);
        }
        return $select;
    }

    public static function categoriesWithMedia()
    {
        $categories[''] = '-';
        foreach (self::has('medias', '>', 0)->orderBy('name', 'ASC')->get() as $c) {
            $categories[$c->id] = $c->name;
        }
        return $categories;
    }
}
