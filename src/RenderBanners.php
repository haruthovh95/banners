<?php 
namespace Zakhayko\Banners;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Request;
use Zakhayko\Banners\Models\Banner;

trait RenderBanners {

    private $data = [];
    private $page;

    protected function with($data){
        $this->data = array_merge($data, $this->data);
    }

    public function render($page) {
        if (!array_key_exists($page, $this->settings)) abort(404);
        $this->page = $page;
        $this->data['params'] = $this->settings[$page];
        $this->data['banners'] = Banner::getBanners($this->page);
        if (Request::getMethod()=='POST') {
            return $this->post();
        }
        if (method_exists($this, 'beforeView')) $this->beforeView($this->page);
        return view('banners::pages.'.$this->page, $this->data);
    }

    private function post() {
        $request = Request::all();
        foreach ($this->data['params'] as $key=>$params) {
            if (array_key_exists($key, $request)) {
                $inputs = $request[$key];
                $count = $params['count']??1;
                $thisBanner = $this->data['banners'][$key];
                if (count($thisBanner)>$count) {
                    $rows = $thisBanner->pluck('id')->toArray();
                    array_splice($rows, 0, $count);
                    Banner::whereIn('id', $rows)->delete();
                }
                for($i=0;$i<$count;$i++) {
                    $this->updateData($params['params'], $inputs[$i]??null, $key, $i);
                }
            }
        }
        Cache::forget(Banner::cacheKey($this->page));
        if (method_exists($this, 'beforeSave')) $this->beforeSave($this->page);
        return redirect()->back();
    }

    private function updateData($params, $inputs, $key, $i){
        if (is_array($inputs) && count($inputs)) {
            $data = [];
            foreach($params as $index=>$param) {
                if (is_array($param)) {
                    $type = $param['type'];
                    unset($param['type']);
                    $settings = $param;
                }
                else {
                    $type = $param;
                    $settings = [];
                }
                $banner = $this->data['banners'][$key][$i]['data'][$index]??null;
                $data[$index] = $this->updateParam($type, $settings, $inputs[$index]??null, $banner);
            }
            if(count($data)) {
                $id = $this->data['banners'][$key][$i]['id']??false;
                Banner::updateBanner($this->page, $key, $data, $id);
            }
        }
        return true;
    }

    private function updateParam($type, $settings, $input, $banner) {
        return (method_exists($this, ($method = 'set_'.$type)))?$this->{$method}($settings, $input, $banner):$input;
    }

    public function getSettings() {
        return $this->settings;
    }
}