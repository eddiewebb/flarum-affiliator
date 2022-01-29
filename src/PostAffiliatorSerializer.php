<?php

namespace Webbinaro\Affiliator;

use Flarum\Api\Serializer\PostSerializer;

class PostAffiliatorSerializer extends PostSerializer
{
    private $partners = array();

    /**
     * Attaches affiliate tracking into to content payload
     *
     * @param \Flarum\Post\Post $post
     * @throws InvalidArgumentException
     */
    protected function getDefaultAttributes($post)
    {
        $this->partners["example.com"] = array("aff","123");
        $attributes = parent::getDefaultAttributes($post);
        $content = $attributes['content'];
        $all_urls = array();
        preg_match_all('#\bhttps?://[^\s()<>]+(?:\([\w\d]+\)|([^[:punct:]\s]|/))#', $content, $all_matches);
        foreach( $all_matches as $match){
            $each = parse_url($match[0]);
            if(isset($each['host']) && array_key_exists( $each['host'], $this->partners)){
                if(array_key_exists('query',$each) && ! is_null($each['query'])){
                    parse_str($each['query'],$query);
                }
                $tag = $this->partners[$each['host']];
                $query[$tag[0]] = $tag[1];
                $each['query'] = http_build_query($query);
                $content = str_replace($match[0],$this->build_url($each),$content);
            }
            

        }
        $attributes['content'] = $content;
        return $attributes;
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
}