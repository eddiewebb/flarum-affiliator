<?php

namespace Webbinaro\Affiliator;

use Flarum\Api\Serializer\PostSerializer;
use Flarum\Settings\SettingsRepositoryInterface;

class PostAffiliatorSerializer extends PostSerializer
{
    /**
    * @var SettingsRepositoryInterface
    */
    protected $settings;


    /**
     * Attaches affiliate tracking into to content payload
     *
     * @param \Flarum\Post\Post $post
     * @throws InvalidArgumentException
     */
    protected function getDefaultAttributes($post)
    {
        $attributes = parent::getDefaultAttributes($post);
        if( $this->settings->get('webbinaro-affiliator.settings.aff.list')) {
            $afflist=$this->settings->get('webbinaro-affiliator.settings.aff.list');
            $partners = $this->parseAffiliateList($afflist);
            //return array('content'=>print_r($partners,true));
            $content = $attributes['content'];
            $all_urls = array();
            preg_match_all('#\bhttps?://[^\s()<>]+(?:\([\w\d]+\)|([^[:punct:]\s]|/))#', $content, $all_matches,PREG_SET_ORDER);
            foreach( $all_matches as $match){
                $each = parse_url($match[0]);
                if(isset($each['host']) && array_key_exists( $each['host'], $partners)){
                    $info = $partners[$each['host']];
                    if(array_key_exists('query',$each) && ! is_null($each['query'])){
                        parse_str($each['query'],$existing_query);
                        $info = array_merge($existing_query,$info);
                    }
                    $each['query'] = http_build_query($info);
                    $content = str_replace($match[0],$this->build_url($each),$content);
                }
             }
            $attributes['content'] = $content;
        }
        return $attributes;
    }

    function setSettings(SettingsRepositoryInterface $settings){
        $this->settings = $settings;
    }

    function build_url(array $parts) {
        return (isset($parts['scheme']) ? "{$parts['scheme']}:" : '') . 
            ((isset($parts['user']) || isset($parts['host'])) ? '//' : '') . 
            (isset($parts['user']) ? "{$parts['user']}" : '') . 
            (isset($parts['pass']) ? ":{$parts['pass']}" : '') . 
            (isset($parts['user']) ? '@' : '') . 
            (isset($parts['host']) ? "{$parts['host']}" : '') . 
            (isset($parts['port']) ? ":{$parts['port']}" : '') . 
            (isset($parts['path']) ? "{$parts['path']}" : '') . 
            (isset($parts['query']) ? "?{$parts['query']}" : '') . 
            (isset($parts['fragment']) ? "#{$parts['fragment']}" : '');
    }

    private function parseAffiliateList(string $list){
        $items = explode(',',$list);
        $partners = array();
        foreach ($items as $item) {
            $partner=parse_url($item);
            $query_array = array();
            parse_str($partner['query'],$query_array);
            $partners[$partner['host']] = $query_array;
        }
        return $partners;
    }
}