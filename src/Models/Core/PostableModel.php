<?php

namespace LiveCMS\Models\Core;

use Carbon\Carbon;
use LiveCMS\Models\Users\User as UserModel;
use Illuminate\Support\Str;
use Mrofi\VideoInfo\VideoInfo;
use Mrofi\VideoInfo\Youtube;
use Symfony\Component\DomCrawler\Crawler;

class PostableModel extends BaseModel
{
    const STATUS_DRAFT = 'draft';
    const STATUS_PUBLISHED = 'published';
    const STATUS_REMOVED = 'removed';

    protected $fillable = ['title', 'site_id', 'slug', 'content', 'author_id', 'picture', 'published_at', 'status'];

    protected $appends = ['url', 'highlight'];

    protected $dependencies = ['permalink', 'author'];

    protected $dates = ['published_at'];

    protected $prefixSlug = '';

    protected $aliases = ['author_id' => 'Author'];

    protected static $picturePath = 'files';

    protected $postableforms = [
        'text' => ['title', 'slug'],
        'readonly_url' => ['url'],
        'textarea' => ['content'],
        'image' => ['picture'],
        'select' => ['status'],
    ];

    protected $files = ['picture'];

    protected $images = ['picture'];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        $this->forms = array_merge($this->postableforms, $this->forms);
    }

    public function rules()
    {
        $this->slugify('title');

        $published_at = $this->published_at ?: Carbon::now();

        $author_id = $this->author_id ?: auth()->user()->id;

        request()->merge(compact('published_at', 'author_id'));

        return [
            'title' => $this->uniqify('title'),
            'slug' => $this->uniqify('slug'),
            'permalink' => 'unique:permalinks,permalink,'.($this->permalink ? $this->permalink->id : 'NULL').',id,site_id,'.(site()->id == null ? 'NULL' : site()->id),
            'content' => 'required',
            'picture' => 'image|max:5120',
            'published_at' => 'required',
        ];
    }

    public function author()
    {
        return $this->belongsTo(UserModel::class, 'author_id');
    }

    public function permalink()
    {
        return $this->morphOne(Permalink::class, 'postable');
    }

    public function children()
    {
        //
    }

    public function statuses()
    {
        $allStatuses = [
            static::STATUS_DRAFT,
            static::STATUS_PUBLISHED,
            static::STATUS_REMOVED,
        ];

        $captions = array_map(function ($item) {
            return Str::title($item);
        }, $allStatuses);

        return array_combine($allStatuses, $captions);
    }

    public function getUrlAttribute()
    {
        if ($this->permalink && $this->permalink->permalink) {

            return url($this->permalink->permalink);
        }

        if ($this->slug != null) {
            
            return url($this->prefixSlug.'/'.$this->slug);
        }

        return url($this->prefixSlug ?: '/');
    }

    public function getPicturePath()
    {
        return static::$picturePath;
    }

    public function getPictureAttribute($picture)
    {
        return $picture ? asset($this->getPicturePath().'/'.$picture) : null;
    }

    public function getHighlightAttribute()
    {
        return str_limit(strip_tags($this->content), 300);
    }

    public function getContent()
    {
        return $this->content;
    }

    public function getVideoContent()
    {
        if (! $this->content) {
            return null;
        }

        $crawler = new Crawler($this->content);
        // scan embeded
        Youtube::setApi(env('YOUTUBE_API'));
        $videos = $crawler->filter('iframe')->each(function (Crawler $node, $i) {
            $src = $node->attr('src');
            $video = new VideoInfo($src);
            if ($video && $video->id) {
                return $video->getVideo();
            }
        });

        return collect($videos);
    }
}
