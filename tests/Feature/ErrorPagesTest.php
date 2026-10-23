<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\Route;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class ErrorPagesTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->app['config']->set('app.debug', false);

        Route::get('/_test/abort/{code}', fn (int $code) => abort($code))->middleware('web');
    }

    public static function statusCodes(): array
    {
        return [
            'unauthorized' => [401, 'Unauthorized'],
            'forbidden' => [403, 'Forbidden'],
            'not found' => [404, 'Not Found'],
            'page expired' => [419, 'Page Expired'],
            'too many requests' => [429, 'Too Many Requests'],
            'server error' => [500, 'Server Error'],
            'service unavailable' => [503, 'Service Unavailable'],
            'bad request' => [400, 'Bad Request'],
            'method not allowed' => [405, 'Method Not Allowed'],
            'teapot' => [418, 'I\'m a teapot'],
            'bad gateway' => [502, 'Bad Gateway'],
        ];
    }

    #[DataProvider('statusCodes')]
    public function test_error_page_links_back_to_the_application(int $code, string $message)
    {
        $response = $this->get("/_test/abort/{$code}");

        $response->assertStatus($code);
        $response->assertSee($message);
        $response->assertSee('Back to the application');
        $response->assertSee('href="'.url('/').'"', false);
        $response->assertDontSee('Oops! An Error Occurred');
    }

    public function test_wrong_http_method_renders_the_error_page()
    {
        $this->call('DELETE', route('login'))
            ->assertStatus(405)
            ->assertSee('Method Not Allowed')
            ->assertSee('Back to the application')
            ->assertDontSee('Oops! An Error Occurred');
    }
}
