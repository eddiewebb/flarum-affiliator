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
use Flarum\Settings\SettingsRepositoryInterface;

class AffiliatorSerializerTests extends TestCase
{
    private $logReporter;
    private $translator;
    private $serializer;
    private $actor;
    private $afflist="https://example.com?aff=123,https://example2.com?daffy=123";



    public function setUp(): void
    {
        parent::setUp();
        $settingsRepo = m::mock(SettingsRepositoryInterface::class);
        $settingsRepo->shouldReceive('get')->with('webbinaro-affiliator.settings.aff.list')->andReturn($this->afflist);
       
        $this->serializer = new PostAffiliatorSerializer($settingsRepo);

    }

    /**
     * @test
     */
    public function testSuffixAddedToNonQueryHttp()
    {
        $input = "Checkout https://example2.com, it's awesome.";
        $expected = "Checkout https://example2.com?daffy=123, it's awesome.";
        $output = $this->serializer->affiliate($input);
        $this->assertEquals($expected,$output,"The affiliate link was not inserted");
    }

    public function testSuffixAddedToQueryHttps()
    {
        $input = "Checkout https://example.com?animal=dog, it's awesome.";
        $expected = "Checkout https://example.com?animal=dog&aff=123, it's awesome.";
        $output = $this->serializer->affiliate($input);
        $this->assertEquals($expected,$output,"The affiliate link was not inserted");
    }
    public function testUnkownHostsAreIgnored()
    {
        $input = "Checkout https://crocs.com?, it's awesome";
        $expected = "Checkout https://crocs.com?, it's awesome";
        $output = $this->serializer->affiliate($input);
        $this->assertEquals($expected,$output,"The affiliate link was not inserted");
    }

    public function testSuffixAddedToMultipleUrls()
    {
        $input = "Checkout https://example.com?animal=dog, it's awesome, better than https://example2.com, though admittedly not quite like http://example3.com";
        $expected = "Checkout https://example.com?animal=dog&aff=123, it's awesome, better than https://example2.com?daffy=123, though admittedly not quite like http://example3.com";
        $output = $this->serializer->affiliate($input);
        $this->assertEquals($expected,$output,"The affiliate link was not inserted");
    }

    public function testSuffixIsNotRepetative()
    {
        $input = "Checkout https://example.com, seriously love https://example.com";
        $expected = "Checkout https://example.com?aff=123, seriously love https://example.com?aff=123";
        $output = $this->serializer->affiliate($input);
        $this->assertEquals($expected,$output,"The affiliate link was not inserted");
    }

    // ...
}