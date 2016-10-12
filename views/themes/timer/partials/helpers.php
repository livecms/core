<?php

if (! function_exists('getArticleCategories')) {
    function getArticleCategories($article)
    {
        return dataImplode($article->categories, 'category', function ($category, $slug) {
            return '<a style="color: black" href="'.url(getSlug('category').'/'.$slug).'">'.$category.'</a>';
        }, 'slug');
    }
}

if (! function_exists('getArticleTags')) {
    function getArticleTags($article)
    {
        return dataImplode($article->tags, 'tag', function ($tag, $slug) {
            return '<a style="color: black" href="'.url(getSlug('tag').'/'.$slug).'">'.$tag.'</a>';
        }, 'slug');
    }
}
