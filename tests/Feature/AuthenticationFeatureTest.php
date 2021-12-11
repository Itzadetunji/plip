<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\EmailVerification;
use Illuminate\Support\Facades\Queue;
use App\Notifications\TestNotification;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Notification;
use App\Notifications\EmailTokenVerification;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AuthenticationFeatureTest extends TestCase
{
    use WithFaker;
    use RefreshDatabase;

    const ROUTE_GET_EMAIL_TOKEN = 'api.v1.auth.email.token';
    const ROUTE_VERIFY_EMAIL_TOKEN = 'api.v1.auth.email.verify';
    const ROUTE_USER_LOGIN = 'api.v1.auth.user.login';
    const ROUTE_USER_LOGOUT = 'api.v1.auth.user.logout';
    const ROUTE_USER_REGISTER = 'api.v1.auth.user.register';

    private User $user;
    private EmailVerification $emailVerification;

    protected function setup(): void
    {
        parent::setUp();

        $this->user = User::factory()->emailVerified()->create();
        $this->emailVerification = EmailVerification::factory()->create();
    }

    /**
     * Test user can get email token.
     *
     * @return void
     */
    public function test_user_can_get_email_token()
    {
        Notification::fake();
        Queue::fake();
        $formData = collect([
            'email' => $this->faker->safeEmail()
        ]);

        $response = $this
            ->postJson(route(self::ROUTE_GET_EMAIL_TOKEN, $formData->all()));
        $response->assertSuccessful();
        $emailVerification = EmailVerification::where('email', $formData['email'])->first();

        Notification::assertSentTo(
            $emailVerification,
            EmailTokenVerification::class
        );
    }

    /**
     * Test user does not receive token/error if email already exists and/or is verified.
     *
     * @return void
     */
    public function test_user_does_not_get_email_token()
    {
        Notification::fake();
        $formData = collect([
            'email' => $this->emailVerification->email
        ]);

        $response = $this
            ->postJson(route(self::ROUTE_GET_EMAIL_TOKEN, $formData->all()));
        $response->assertSuccessful();
        Notification::assertNothingSent();
    }

    /**
     * Test user can verify email token.
     *
     * @return void
     */
    public function test_user_can_verify_email_token()
    {
        $formData = collect([
            'email' => $this->emailVerification->email,
            'token' => $this->emailVerification->token
        ]);

        $response = $this
            ->postJson(route(self::ROUTE_VERIFY_EMAIL_TOKEN, $formData->all()));
        $response->assertSuccessful();
        $response->assertJsonStructure([
            'data' => [
                $this->emailVerification->email => 'email'
            ]
        ]);
    }

    /**
     * Test user can register using a verified email.
     *
     * @return void
     */
    public function test_user_can_register_with_verified_email()
    {
        $this->emailVerification->email_verified_at = now();
        $this->emailVerification->save();

        $formData = collect([
            'first_name' => $this->faker->firstName(),
            'last_name' => $this->faker->lastName(),
            'middle_name' => "A",
            'nick_name' => $this->faker->word(),
            'email' => $this->emailVerification->email,
            'password' => "Password22",
        ]);

        $response = $this
            ->postJson(route(self::ROUTE_USER_REGISTER, $formData->all()));
        $response->assertSuccessful();
    }

    /**
     * Test user cannot register using an unverified email.
     *
     * @return void
     */
    public function test_user_cannot_register_with_unverified_email()
    {
        $formData = collect([
            'first_name' => $this->faker->firstName(),
            'last_name' => $this->faker->lastName(),
            'middle_name' => "A",
            'nick_name' => $this->faker->word(),
            'email' => $this->emailVerification->email,
            'password' => "Password22",
        ]);

        $response = $this
            ->postJson(route(self::ROUTE_USER_REGISTER, $formData->all()));
        $response->assertStatus(422);
    }

    /**
     * Test user cannot register with missing/invalid fields.
     *
     * @return void
     */
    public function test_user_cannot_register_with_invalid_fields()
    {
        $this->emailVerification->email_verified_at = now();
        $this->emailVerification->save();

        $formData = collect([
            'middle_name' => "A",
            'nick_name' => $this->faker->word(),
            'email' => $this->emailVerification->email,
            'password' => "Password22",
        ]);

        $response = $this
            ->postJson(route(self::ROUTE_USER_REGISTER, $formData->all()));
        $response->assertStatus(422);
    }

    /**
     * Test user can login.
     *
     * @return void
     */
    public function test_user_can_login()
    {
        $formData = collect([
            'email' => $this->user->email,
            'password' => "Password22",
            'device_name' => "web",
        ]);

        $response = $this
            ->postJson(route(self::ROUTE_USER_LOGIN, $formData->all()));
        $response->assertSuccessful();
        $response->assertJsonStructure([
            'data' => [
                "user" => [],
                "token"
            ]
        ]);
    }

    /**
     * Test user cannot login with wrong credentials.
     *
     * @return void
     */
    public function test_user_cannot_login_with_wrong_credentials()
    {
        $formData = collect([
            'email' => $this->faker->safeEmail(),
            'password' => "Password22",
            'device_name' => "web",
        ]);

        $response1 = $this
            ->postJson(route(self::ROUTE_USER_LOGIN, $formData->all()));
        $response1->assertStatus(422);


        $formData = collect([
            'email' => $this->user->email,
            'password' => "WrongPassword",
            'device_name' => "web",
        ]);

        $response2 = $this
            ->postJson(route(self::ROUTE_USER_LOGIN, $formData->all()));
        $response2->assertStatus(422);
    }
}
