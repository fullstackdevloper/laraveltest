<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use DateTime;
use Carbon\Carbon;
use Illuminate\Support\Facades\Crypt;
use App\Models\Ipn;
use App\Models\Roles;

class checkUserAutorenewSubscription extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:check-user-autorenew-subscription';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command for cancel subscription those agent auto_renew subscription off';

    /**
     * Execute the console command.
     */

    public function handle()
    {
        // Set the default timezone to America/Phoenix
        date_default_timezone_set("America/Phoenix");

        // Get the current date and time
        $today = now();

        // Get users with expired subscriptions of type 'user'
        $users = User::subscriptionExpired($today)
            ->with('ipn')
            ->ofType('subscriber')
            ->get();

        // Loop through each user with expired subscription
        foreach ($users as $user) {

            // Display information about the canceled subscription
            $this->info($user->id . ' subscription is cancel');

            // Get the name of the current plan for the user
            $current_plan_user = $user->latestSubsription->plan->name;

            // Prepare data for email notification
            $data = [
                'data' => [
                    'email'               => $user->email,
                    'last_name'           => $user->last_name,
                    'first_name'          => $user->first_name,
                    'old_subscription'    => $current_plan_user,
                    'current_subcription' => 'Free'
                ]
            ];


            // Remove user details
            $user->removeUserData($user);

            // Update membership details
            $this->updateMembership($user);


            // Update IPN entries for the user
            $this->updateIpnEntries($user);


            // Create a new IPN entry for the user
            $ipnData = [
                'customer_id'        => "ckd_" . $user->id,
                'status'             => 'done',
                'type'               => 'authorized',
                'current_membership' => 'active'
            ];
            $user->ipn()->create($ipnData);
            $this->mailSendCommonV2($data, 'auto_cancel_subscription');
        }
    }

    /**
     * Update IPN entries for the user.
     */
    private function updateIpnEntries($user)
    {
        // Get IPN entries for the user
        $ipn = $user->ipn->pluck('id');

        // Check if there are IPN entries for the user
        if ($ipn->isNotEmpty()) {
            // Deactivate IPN entries for the user
            Ipn::whereIn('id', $ipn)->update(['current_membership' => 'deactive']);
        }
    }

    /**
     * Update membership of the user.
     */
    private function updateMembership($user)
    {
        // Get the 'user' role from the roles table
        $newRole = Roles::where('name', 'user')->first();

        // If the 'user' role is found, update the user's roles
        if ($newRole) {
            // Sync the user's roles with the 'user' role
            $user->roles()->sync([$newRole->id]);
            $user->save();
        }
    }
}
