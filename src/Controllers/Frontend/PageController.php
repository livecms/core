<?php

namespace LiveCMS\Controllers\Frontend;

use Carbon\Carbon;
use Illuminate\Http\Request;
use LiveCMS\Models\Article;
use LiveCMS\Models\Category;
use LiveCMS\Models\Gallery;
use LiveCMS\Models\Tag;
use LiveCMS\Models\StaticPage;
use LiveCMS\Models\Core\Permalink;
use LiveCMS\Models\Core\PostableModel;
use LiveCMS\Controllers\FrontendController;
use ReflectionClass;

class PageController extends FrontendController
{
    public function home(Request $request)
    {
        // if set launching time
        $launchingDateTime = globalParams('launching_datetime') ?
        new Carbon(globalParams('launching_datetime')) : Carbon::now();

        // check if has home permalink
        $permalink = Permalink::withDependencies()->whereIn('permalink', ['/', ''])->first();

        $post = $permalink ? $permalink->postable : null;

        // if home exist or not yet launch
        if (!$post || !$post->isPublished() || $permalink == null || $launchingDateTime->isFuture()) {
            return redirect('coming-soon');
        }

        $title = globalParams('home_title', config('livecms.home_title', 'Home'));
        return view(theme('front', 'home'), compact('post', 'title'));
    }

    public function getArticle(Request $request, $redirect = true, $slug = null, $with = [])
    {
        $article = new Article;
        view()->share($with);

        foreach ($with as $key => $value) {
            $article = $article->whereHas($key, function ($query) use ($value) {
                $query->where($query->getModel()->getKeyName(), $value);
            });
        }

        if ($slug == null) {
            $articles = $article->published()->orderBy('published_at', 'DESC')->simplePaginate(12);
            return view(theme('front', ($request->ajax() ? 'partials.articles' : 'articles')), compact('articles'));
        }

        if ($loggedAndPreview = (($user = auth()->user()) && $request->get('preview') == 'true')) {
            if (!$user->is_administer) {
                $article = $article->where('author_id', $user->id);
            }
        } else {
            $article = $article->published();
        }

        $post = $article = $article->where('slug', $slug)->firstOrFail();
        if (!$loggedAndPreview || (auth()->user() && auth()->user()->id != $article->author_id)) {
            if ($article->view > 0) {
                $article->increment('view');
            } else {
                $article->update(['view' => 1]);
            }
        }
        $title = $post->title;

        if ($redirect && $post->permalink) {
            return redirect($post->url);
        }

        return view(theme('front', 'article'), compact('post', 'article', 'title'));
    }

    public function getGallery(Request $request, $redirect = true, $slug = null, $with = [])
    {
        $gallery = new Gallery;
        view()->share($with);

        foreach ($with as $key => $value) {
            $gallery = $gallery->whereHas($key, function ($query) use ($value) {
                $query->where($query->getModel()->getKeyName(), $value);
            });
        }

        if ($slug == null) {
            $galleries = $gallery->published()->orderBy('published_at', 'DESC')->simplePaginate(12);
            return view(theme('front', ($request->ajax() ? 'partials.galleries' : 'galleries')), compact('galleries'));
        }

        if (($user = auth()->user()) && $request->get('preview') == 'true') {
            if (!$user->is_administer) {
                $gallery = $gallery->where('author_id', $user->id);
            }
        } else {
            $gallery = $gallery->published();
        }

        $post = $gallery = $gallery->where('slug', $slug)->firstOrFail();
        $title = $post->title;

        if ($redirect && $post->permalink) {
            return redirect($post->url);
        }

        return view(theme('front', 'gallery'), compact('post', 'gallery', 'title'));
    }

    public function getStaticPage(Request $request, $redirect = true, $slug = null)
    {
        $static = new StaticPage;

        if (($user = auth()->user()) && $request->get('preview') == 'true') {
            if (!$user->is_administer) {
                $static = $static->where('author_id', $user->id);
            }
        } else {
            $static = $static->published();
        }

        $post = $static = $static->where('slug', $slug)->firstOrFail();
        $title = $post->title;

        if ($redirect && $post->permalink) {
            return redirect($post->url);
        }

        return view(theme('front', 'staticpage'), compact('post', 'static', 'title'));
    }

    public function getByPermalink(Request $request, $permalink)
    {
        $page = Permalink::where('permalink', $permalink)->firstOrFail();
        $type = (new ReflectionClass($post = $page->postable))->getShortName();
        return view()->exists(theme('front', $permalink)) ? view(theme('front', $permalink), compact('post')) : $this->{'get'.$type}($request, false, $post->slug);
    }

    public function postSearch(Request $request)
    {
        $keyword = $request->get('s');
        $articles = Article::search($keyword)->where('status', PostableModel::STATUS_PUBLISHED)->paginate(10);
        $staticpages = StaticPage::search($keyword)->where('status', PostableModel::STATUS_PUBLISHED)->paginate(10);
        $categories = Category::search($keyword)->paginate(10);
        $tags = Tag::search($keyword)->paginate(10);

        $result = compact('keyword', 'articles', 'staticpages', 'categories', 'tags');
        if ($request->isJson() || $request->wantsJson()) {
            return $result;
        }
        return view(theme('front', 'search'), $result);
    }

    public function routes(Request $request)
    {
        $parameters = func_get_args();
        array_shift($parameters);

        // get static
        $statisSlug = getSlug('staticpage');
        $param = isset($parameters[1]) ? $parameters[1] : null;

        if ($parameters[0] == $statisSlug) {
            view()->share('routeBy', 'static');
            return $this->getStaticPage($request, true, $parameters[1]);
        }

        // get article category
        $categorySlug = getSlug('category');
        $category = Category::where('slug', $param)->first();
        if ($category && $parameters[0] == $categorySlug) {
            view()->share('routeBy', 'category');
            view()->share('category', $category);
            view()->share('title', $category->category);
            return $this->getArticle($request, true, null, $category ? ['categories' => $category->id] : []);
        }

        // get article tag
        $tagSlug = getSlug('tag');
        $tag = Tag::where('slug', $param)->first();
        if ($tag && $parameters[0] == $tagSlug) {
            view()->share('routeBy', 'tag');
            view()->share('tag', $tag);
            view()->share('title', $tag->tag);
            return $this->getArticle($request, true, null, $tag ? ['tags' => $tag->id] : []);
        }

        // get article
        $articleSlug = getSlug('article');
        if ($parameters[0] == $articleSlug) {
            view()->share('routeBy', 'article');
            return $this->getArticle($request, true, $param);
        }

        // get gallery
        $gallerySlug = getSlug('gallery');
        if ($parameters[0] == $gallerySlug) {
            view()->share('routeBy', 'gallery');
            return $this->getGallery($request, true, $param);
        }

        $permalink = implode('/', $parameters);
        return $this->getByPermalink($request, $permalink);
    }
}
