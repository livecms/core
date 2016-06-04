<?php

namespace LiveCMS\Models;

use Schema;
use LiveCMS\Models\Users\User;
use LiveCMS\Models\GenericSetting as Setting;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Site extends Model
{
    protected $allSites = true;
    
    protected static $current;
    
    protected static $host;
    
    protected static $domain;

    protected $fillable = ['site', 'subdomain', 'subfolder'];


    public static function init()
    {
        try {
            
            if (!Schema::hasTable('sites')) {
                return static::setCurrent(new Site);
            };

        } catch (\Exception $e) {
            return static::setCurrent(new Site);
        }
        
        static::$domain = $domain = rtrim(config('livecms.domain'), '/');

        static::$host = $host = request()->server('HTTP_HOST');

        if (false === strpos($host, $domain) && !request()) {
            
            throw new \Exception('Anda harus men-set konfigurasi domain '.$domain);
        }

        $subdomain = rtrim(substr($host, 0, (strlen($host) - strlen($domain))), '.');

        if ($subdomain) {
            
            return static::initSubdomain($subdomain);
        }

        if ($site = static::getBySubfolder(new static)) {
                
            return static::setCurrent($site);
        }

        return static::setCurrent(new Site);
    }

    protected static function initSubdomain($subdomain)
    {
        $findSites = static::where('subdomain', $subdomain)->get();

        if (($siteCount = count($findSites)) > 1) {

            if ($site = static::getBySubfolder($findSites)) {
                
                return static::setCurrent($site);
            }
        }

        if ($siteCount) {
            
            $site = $findSites->first();

            return static::setCurrent($site);
        }

        return static::setCurrent(new Site);
    }

    protected static function getBySubfolder($collection)
    {
        $subfolder = request()->segment(1);

        if ($site = $collection->where('subfolder', $subfolder)->first()) {
            
            return $site;
        }

        return null;
    }

    public static function setCurrent($current)
    {
        return static::$current = $current;
    }

    public function getCurrent()
    {
        return static::$current ?: static::init();
    }

    public function getHost()
    {
        return static::$host;
    }

    public function getRootUrl()
    {
        $start = Str::startsWith(request()->root(), 'http://') ? 'http://' : 'https://';
        $subDomain = $this->subdomain ? $this->subdomain.'.' : '';
        $subFolder = $this->subfolder ? '/'.$this->subfolder : '';
        return $start.$subDomain.$this->getDomain().$subFolder;
    }

    public function getPath()
    {
        return request()->path();
    }

    public function getDomain()
    {
        return static::$domain;
    }

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function settings()
    {
        if ($this->id != null) {
            return $this->hasMany(Setting::class);
        }
        
        return Setting::where('site_id', null);
    }
}
