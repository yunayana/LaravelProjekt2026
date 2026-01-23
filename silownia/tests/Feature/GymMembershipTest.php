<?php

namespace Tests\Feature;

use App\Models\GymMembership;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GymMembershipTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function membership_is_active_when_status_active_and_dates_in_range()
    {
        $membership = GymMembership::factory()->create([
            'status' => 'active',
            'start_date' => now()->subDay(),
            'end_date' => now()->addDay(),
        ]);

        $this->assertTrue($membership->isActive());
    }
}
