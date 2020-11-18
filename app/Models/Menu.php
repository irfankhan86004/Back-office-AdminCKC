<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\MorphManyMedias;

use Form;

class Menu extends LangModel
{
    use MorphManyMedias;

    protected $table = 'menu';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['parent_id', 'position', 'page_id', 'blog_post_id', 'link', 'anchor', 'target'];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [];

    public function lang()
    {
        return $this->hasMany('App\Models\MenuLang');
    }

    public function getAttr($attribut)
    {
        return $this->getLangAttr($attribut);
    }

    public function children()
    {
        return $this->hasMany('App\Models\Menu', 'parent_id')->orderBy('position', 'ASC');
    }

    public function parent()
    {
        return $this->belongsTo('App\Models\Menu', 'parent_id');
    }

    public function page()
    {
        return $this->belongsTo('App\Models\Page', 'page_id');
    }

    public function post()
    {
        return $this->belongsTo('App\Models\BlogPost', 'blog_post_id');
    }

    public function getPicture($width = null, $height = null)
    {
        if (!empty($this->getMedias()[0])) {
            return $this->getMedias()[0]->route($width, $height);
        } else {
            return 'https://via.placeholder.com/' . $width . 'x' . $height . '?text=' . config('app.name');
        }
    }

    public function lien_url()
    {
        if ($this->link != '' && $this->link != null) {
            return $this->link;
        } elseif ($this->post) {
            return 'Article #'.$this->post->id.' - '.$this->post->getAttr('name');
        } elseif ($this->page) {
            return 'Page #'.$this->page->id.' - '.$this->page->getAttr('name').' ('.$this->page->getAttr('url').')';
        } else {
            return;
        }
    }

    public function url()
    {
        if ($this->link!='' && $this->link!=null) {
            $link = $this->link;
        } elseif ($this->post) {
            $link = $this->post->URL();
        } elseif ($this->page) {
            $link = $this->page->URL();
        } else {
            $link = '#';
        }
        if ($link != '#' && $this->anchor) {
            $link .= '#'.$this->anchor;
        }
        return $link;
    }

    public function arbo($menu, $padding_left = 8)
    {
        foreach ($menu->children as $m) {
            echo 	'<tr>
			            <td class="text-center">'. $m->id .'</td>
			            <td style="padding-left:'.$padding_left.'px"><a href="'. route('menu.edit', array($m->id)) .'">'. ucfirst($m->getAttr('name')) .'</a></td>
                        <td class="text-center">'.(!empty($m->getMedias()[0]) ? '<img src="'. $m->getMedias()[0]->route(150, 150) .'" class="img-rounded"/>' : '-').'</td>
			            <td style="padding-left:'.$padding_left.'px"><span class="label label-primary">'. $m->position .'</span></td>
			            <td>'. (($m->lien_url()) ? $m->lien_url() : '-') .'</td>
			            <td><span class="hide">'.strtotime($m->created_at).'</span>'.format_date($m->created_at).'</td>
			            <td class="text-right">
			                '. Form::open(['method' => 'DELETE', 'route' => ['menu.destroy', $m->id], 'id'=>'delete-'.$m->id]) .'
			                <div class="btn-group btn-group-sm">
			                    <a href="'. route('menu.edit', array($m->id)) .'" data-toggle="tooltip" title="" class="btn btn-default" data-original-title="Details"><i class="hi hi-search"></i></a>
								<a href="#" type="submit" class="btn btn-danger delete" data-entry="'. $m->id .'" data-toggle="tooltip" data-original-title="Supprimer"><i class="gi gi-remove_2"></i></a>
			                </div>
			                '. Form::close() .'
			            </td>
			        </tr>';
            if (count($m->children)>=1) {
                $this->arbo($m, $padding_left+22);
            }
        }
    }

    public static function arbo_front($menu, $drop=false, $i=0)
    {
        $total 			= count($menu->children);
        $totalChild 	= count($menu->children);
        $j 				= 0;
        $init_counter 	= $i;

        foreach ($menu->children as $m) {
            $i++;
            echo '<li class="'.($drop ? 'dropdown-item' : 'nav-item'.(count($m->children) ? ' dropdown' : '')).(!$drop && $i == $totalChild ? ' m-0' : '').'">';

            echo '<a href="'.$m->url().'"'.(!$drop ? ' class="'.($i == $totalChild ? 'navbar-brand' : 'nav-link').'"' : '').'>'.$m->getAttr('name').'</a>';

            if (count($m->children)>=1) {
                echo '<ul id="navbarNavAltMarkup" class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">';
                self::arbo_front($m, true, $i);
                echo '</ul>';
            }
            echo '</li>';
        }
    }
}
