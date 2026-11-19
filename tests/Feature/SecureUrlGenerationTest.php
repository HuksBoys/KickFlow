<?php

namespace Tests\Feature;

use App\Providers\AppServiceProvider;
use Illuminate\Support\Facades\URL;
use Tests\TestCase;

class SecureUrlGenerationTest extends TestCase
{
    public function test_production_generates_https_urls_when_proxy_reports_http(): void
    {
        config()->set('app.env', 'production');
        config()->set('app.url', 'http://localhost');

        (new AppServiceProvider(app()))->boot();

        $this->assertStringStartsWith('https://', URL::route('login'));
    }
}
