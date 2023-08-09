<?php

namespace Tests\Unit;

// use PHPUnit\Framework\TestCase;

use App\Models\Roles;
use Tests\TestCase;
// use App\Console\Commands\checkUserAutorenewSubscription;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use App\Models\User;
use App\Models\Subscription;

class userAutorenewSubscription extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic unit test example.
     */

    public function test_it_cancels_subscriptions_with_expired_auto_renew(): void
    {
        // Create test data: User with expired subscription
        $roles        = Roles::factory()->create();
        $user         = User::factory()->create();
        $subscription = Subscription::factory()->expired()->create(['user_id' => $user->id]);

        // Run the console command
        Artisan::call('app:check-user-autorenew-subscription');

        $ipn = $user->ipn()->first();

        // Assert that the user's subscription is canceled
        $hasSubscriptions = $user->latestSubsription->isActive();
        $this->assertFalse($hasSubscriptions);
        // Assert that related IPN entries are updated correctly
    }
}
