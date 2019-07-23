<?php 
namespace Zakhayko\Banners;
class Widget {
    public static function render(&$params, &$banners, $thisKey, $thisCount, $key, $label=null){
        $data = explode('.', $key);
        $dataCount = count($data);
        if ($dataCount==1) {
            $key=$thisKey;
            $count=$thisCount;
            $as = $data[0];
        }
        else if ($dataCount==2) {
            $key = $data[0];
            $as = $data[1];
        }
        else {
            $key = $data[0];
            $i = $data[1];
            $as = $data[2];
        }
        if (empty($count)) $count = $i??0;
        $settings = $params[$key]['params'][$as];
        if (is_array($settings)) {
            $type = $settings['type'];
        }
        else {
            $type = $settings;
            $settings = [];
        }
        $data = [
            'key'=>$key,
            'i'=>$count,
            'as'=>$as,
            'label'=>$label,
            'value'=>$banners[$key][$count]['data'][$as]??null,
            'name' => $key.'['.$count.']['.$as.']',
            'id' => "$key-$count-$as",
            'settings'=>$settings
        ];

        return view('banners::widgets.'.$type, $data);
    }
}