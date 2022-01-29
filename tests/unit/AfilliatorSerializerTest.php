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
    public function testSuffixAdded()
    {
        $this->post->shouldReceive('formatContent')->andReturn( "Checkout http://site.com?aff=123, it's awesome.");
        $expected = "Checkout http://site.com?aff=123, it's awesome.";
        $output = $this->serializer->getAttributes($this->post);
        $this->assertEquals($expected,$output,"The affiliate link was not inserted");
    }

    // ...
}