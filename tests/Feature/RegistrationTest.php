<?php

namespace Tests\Feature;

use App\Mail\PleaseConfirmYourEmail;
use App\User;
use Illuminate\Auth\Events\Registered;
use Mail;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class RegistrationTest extends TestCase
{
    use DatabaseMigrations;
    
    /** @test */
    public function a_confirmation_is_sent_upon_registration()
    {
        Mail::fake();
    
        $this->post(route('register'), [
            'name' => 'John',
            'email' => 'john@example.com',
            'password' => 'localpassword!88',
            'password_confirmation' => 'localpassword!88'
        ]);
        
        Mail::assertQueued(PleaseConfirmYourEmail::class);
    }
    
    /** @test */
    public function users_can_confirm_their_email_addresses()
    {
        Mail::fake();
        
        $this->post(route('register'), [
            'name' => 'John',
            'email' => 'john@example.com',
            'password' => 'localpassword!88',
            'password_confirmation' => 'localpassword!88'
        ]);
        
        $user = User::whereName('John')->first();
    
        $this->assertFalse($user->confirmed);
        $this->assertNotNull($user->confirmation_token);
    
        $this->get(route('register.confirm', ['token' => $user->confirmation_token]))
            ->assertRedirect(route('threads'));
    
        tap($user->fresh(), function ($user) {
            $this->assertTrue($user->confirmed);
            $this->assertNull($user->confirmation_token);
        });
    }
    
    /** @test */
    public function confirming_an_invalid_token()
    {
        $this->get(route('register.confirm', ['token' => 'invalid']))
            ->assertRedirect(route('threads'))
            ->assertSessionHas('flash', 'Unknown token.');
    }
}
