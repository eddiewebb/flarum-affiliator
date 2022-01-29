<?php

/*
 * This file is part of Flarum.
 *
 * For detailed copyright and license information, please view the
 * LICENSE file that was distributed with this source code.
 */

namespace Webbinaro\Affiliator\Tests\unit;


use Flarum\Testing\unit\TestCase;
use Mockery as m;
use Flarum\Foundation\ErrorHandling\LogReporter;
use Flarum\Post\Post;
use Flarum\User\User;
use Psr\Http\Message\ServerRequestInterface as Request;
use Symfony\Contracts\Translation\TranslatorInterface;
use Webbinaro\Affiliator\PostAffiliatorSerializer;

class AffiliatorSerializerTests extends TestCase
{
    private $logReporter;
    private $translator;
    private $serializer;
    private $actor;



    public function setUp(): void
    {
        parent::setUp();
        $this->actor = m::mock(User::class);
        $this->actor->shouldReceive('can')->andReturn(true);
        $request = m::mock(Request::class)->makePartial();
        $request->shouldReceive('getAttribute->getActor')->andReturn($this->actor);
        $this->post = m::mock(Post::class)->makePartial();
        $this->logReporter = m::mock(LogReporter::class);
        $this->translator = m::mock(TranslatorInterface::class);

        $this->serializer = new PostAffiliatorSerializer($this->logReporter,$this->translator);
        $this->serializer->setRequest($request);

    }

    /**
     * @test
     */
    public function testSuffixAddedToNonQueryHttp()
    {
        $this->post->shouldReceive('getAttribute')->with('content')->andReturn("Checkout https://example2.com, it's awesome.");
        $expected = "Checkout https://example2.com?daffy=123, it's awesome.";
        $output = $this->serializer->getAttributes($this->post);
        $this->assertEquals($expected,$output['content'],"The affiliate link was not inserted");
    }

    public function testSuffixAddedToQueryHttps()
    {
        $this->post->shouldReceive('getAttribute')->with('content')->andReturn("Checkout https://example.com?animal=dog, it's awesome.");
        $expected = "Checkout https://example.com?animal=dog&aff=123, it's awesome.";
        $output = $this->serializer->getAttributes($this->post);
        $this->assertEquals($expected,$output['content'],"The affiliate link was not inserted");
    }

    public function testSuffixAddedToMultipleUrls()
    {
        $this->post->shouldReceive('getAttribute')->with('content')->andReturn("Checkout https://example.com?animal=dog, it's awesome, better than https://example2.com, though admittedly not quite like http://example3.com");
        $expected = "Checkout https://example.com?animal=dog&aff=123, it's awesome, better than https://example2.com?daffy=123, though admittedly not quite like http://example3.com";
        $output = $this->serializer->getAttributes($this->post);
        $this->assertEquals($expected,$output['content'],"The affiliate link was not inserted");
    }

    // ...
}