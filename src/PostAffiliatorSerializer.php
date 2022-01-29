<?php

namespace Webbinaro\Affiliator;

use Flarum\Settings\SettingsRepositoryInterface;

use Flarum\Post\Event\Saving;

class PostAffiliatorSerializer
{
    /**
    * @var SettingsRepositoryInterface
    */
    protected $settings;

    private $partners = array();

    public function __construct(SettingsRepositoryInterface $settings)
    {
        $this->settings = $settings;
        $afflist=$this->settings->get('webbinaro-affiliator.settings.aff.list');
        $this->partners = $this->parseAffiliateList($afflist);
    }

    /**
     * Attaches affiliate tracking into to content payload
     *
     * @param \Flarum\Post\Post $post
     * @throws InvalidArgumentException
     */
    public function affiliate($content)
    {
        if( sizeof($this->partners) > 0 ) {
            return preg_replace_callback('#\bhttps?://[^\s()<>]+(?:\([\w\d]+\)|([^[:punct:]\s]|/))#',
            function($each_match){
                $newlink="";
                //return print_r($matches);
                //foreach( $matches as $match){
                    $each = parse_url($each_match[0]);
                    if(isset($each['host']) && array_key_exists( $each['host'], $this->partners)){
                        $info = $this->partners[$each['host']];
                        if(array_key_exists('query',$each) && ! is_null($each['query'])){
                            parse_str($each['query'],$existing_query);
                            $info = array_merge($existing_query,$info);
                        }
                        $each['query'] = http_build_query($info);
                        $newlink = $this->build_url($each);
                    }else{
                        return $each_match[0]; //no replace
                    }
                 //}
                 return $newlink;
            }
            , $content);
            
        }
        return $content;
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