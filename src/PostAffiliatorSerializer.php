<?php

namespace Webbinaro\Affiliator;

use Flarum\Api\Serializer\PostSerializer;

class PostAffiliatorSerializer extends PostSerializer
{
    /**
     * Attaches affiliate tracking into to content payload
     *
     * @param \Flarum\Post\Post $post
     * @throws InvalidArgumentException
     */
    protected function getDefaultAttributes($post)
    {
        $attributes = parent::getDefaultAttributes($post);
        $content = $attributes['content'];
        $all_urls = array();
        preg_match_all('#\bhttps?://[^\s()<>]+(?:\([\w\d]+\)|([^[:punct:]\s]|/))#', $content, $all_urls);
        foreach( $all_urls as $url){
            $each = parse_url($url);
            die($url);

        }
    }
}