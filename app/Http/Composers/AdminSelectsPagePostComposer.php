<?php

namespace App\Http\Composers;

use Illuminate\Contracts\View\View;
use App\Models\PageLang;
use App\Models\BlogPostLang;
use Illuminate\Support\Facades\Route;

class AdminSelectsPagePostComposer
{
	/**
	 * Bind data to the view.
	 *
	 * @param View $view
	 */
	public function compose(View $view)
	{
		$pages[null] = 'Selectionner un page';
		foreach (PageLang::where('language_id', 1)->orderBy('name', 'ASC')->get() as $lang) {
			$pages[$lang->page_id] = 'Page #'.$lang->page_id.' - '.$lang->name.' ('.$lang->url.')';
		}
		
		$posts[null] = 'Selectionner un article';
		foreach (BlogPostLang::where('language_id', 1)->orderBy('name', 'ASC')->get() as $lang) {
			$posts[$lang->blog_post_id] = 'Article #'.$lang->blog_post_id.' - '.$lang->name.' ('.$lang->url.')';;
		}

		$view->with('pages', $pages);
		$view->with('posts', $posts);
	}
}
