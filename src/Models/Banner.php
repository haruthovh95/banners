<?php

namespace Zakhayko\Banners\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Zakhayko\Banners\Object\BannerElement;
use Zakhayko\Banners\Object\BannerObject;
use Zakhayko\Banners\Object\FakeObject;

class Banner extends Model
{
    public $timestamps = false;
    protected $table;
    public function __construct(array $attributes = [])
    {
        $this->table = config('banners.table_name');
        parent::__construct($attributes);
    }

    public static function cacheKey($page) {
        return config('banners.cache_prefix').'_'.$page;
    }
    public static function get($page) {
        $cacheKey = self::cacheKey($page);
        if (Cache::has($cacheKey)) return Cache::get($cacheKey);
        $items = self::select('key', 'data')->where('page', $page)->sort()->get()->mapToGroups(function($item){
            return [$item['key']=> new BannerElement($item['data'])];
        })->toArray();
        if (!empty($items)) {
            $result = [];
            foreach ($items as $key => $item) {
                $result[$key] = new BannerObject($item);
            }
            $result = (object) $result;
            Cache::forever($cacheKey, $result);
            return $result;
        }
        return config('banners.ignore_exceptions_if_empty', false)?(new FakeObject()):null;
    }
    public static function getBanners($page){
        return self::select('id', 'key', 'data')->where('page', $page)->sort()->get()->mapToGroups(function($item){
            return [
                $item['key']=>[
                    'id'=>$item['id'],
                    'data'=>json_decode($item['data'], true),
                ]
            ];
        });
    }

    public static function fixBanners($settings) {
        $pages = [];
        $allIds = [];
        foreach($settings as $page=>$keys) {
            $pages[] = $page;
            Cache::forget(self::cacheKey($page));
            $allKeys = [];
            foreach($keys as $key=>$data) {
                $allKeys[]=$key;
                $count = $data['count']??1;
                $ids = self::select('id')->where(['page'=>$pages, 'key'=>$key])->sort()->pluck('id')->toArray();
                $allIds = array_merge($allIds, array_slice($ids, $count));
            }
            self::where('page', $page)->whereNotIn('key', $allKeys)->delete();
        }
        self::whereNotIn('page', $pages)->orWhere(function($q) use ($allIds){
            $q->whereIn('id', $allIds);
        })->delete();
        return true;
    }

    public static function updateBanner($page, $key, $data, $id){
        if($id) return self::where(['id'=>$id])->update(['data'=>json_encode($data, JSON_UNESCAPED_UNICODE)]);
        else return self::insert([
            'page'=>$page,
            'key'=>$key,
            'data'=>json_encode($data, JSON_UNESCAPED_UNICODE),
        ]);
    }

    public function scopeSort($q) {
        return $q->orderBy('id', 'asc');
    }
}
