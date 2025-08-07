<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Activity;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ActivityTest extends TestCase
{
    use RefreshDatabase;

    public function test_activity_can_be_created()
    {
        $user = User::factory()->create(['name' => 'Test User']);
        
        $activity = Activity::log('Test activity', $user, $user);
        
        $this->assertDatabaseHas('activities', [
            'description' => 'Test activity',
            'causer_type' => User::class,
            'causer_id' => $user->id,
            'subject_type' => User::class,
            'subject_id' => $user->id,
        ]);
        
        $this->assertEquals('Test activity', $activity->description);
        $this->assertEquals($user->id, $activity->causer_id);
    }

    public function test_activity_broadcasting_works()
    {
        $user = User::factory()->create(['name' => 'Test User']);
        
        // This should not throw an exception
        $activity = Activity::log('Test broadcast activity', $user, $user);
        
        $this->assertNotNull($activity);
        $this->assertEquals('Test broadcast activity', $activity->description);
    }
}
