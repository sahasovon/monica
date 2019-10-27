<?php

namespace Tests\Browser\Pages;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Laravel\Dusk\Browser;

class DashboardValidate2fa extends Page
{
    use DatabaseTransactions;

    /**
     * Get the URL for the page.
     *
     * @return string
     */
    public function url()
    {
        return '/dashboard';
    }

    /**
     * Assert that the browser is on the page.
     *
     * @param  Browser  $browser
     * @return void
     */
    public function assert(Browser $browser)
    {
        $browser->assertPathIs($this->url());
    }

    /**
     * Get the element shortcuts for the page.
     *
     * @return array
     */
    public function elements()
    {
        return [
            'verify' => "button[name='verify']",
            'otp' => '#one_time_password',
        ];
    }
}
