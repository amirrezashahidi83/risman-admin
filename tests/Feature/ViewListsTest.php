<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Admin;
use App\Filament\Resources\AdminResource;

class ViewListsTest extends TestCase
{

    /**
     * A basic feature test example.
     */
    /** @test */
    public function view_dashboard(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    /** @test */
    public function view_admins()
    {
        $this->get('/admins')->assertSuccessful();
    }

    /** @test */
    public function view_counselors()
    {
        $this->get('/counselors')->assertSuccessful();
    }

    /** @test */
    public function view_students()
    {
        $this->get('/students')->assertSuccessful();
    }
    
    /** @test */
    public function view_lessons()
    {
        $this->get('/lessons')->assertSuccessful();
    }

    /** @test */
    public function view_counselor_plans()
    {
        $this->get('/counselor-plans')->assertSuccessful();
    }

    /** @test */
    public function view_study_plans()
    {
        $this->get('/study-plans')->assertSuccessful();
    }

    /** @test */
    public function view_options()
    {
        $this->get('/options')->assertSuccessful();
    }

    /** @test */
    public function view_transactions()
    {
        $this->get('/transactions')->assertSuccessful();
    }

    /** @test */
    public function view_dailies()
    {
        $this->get('/dailies')->assertSuccessful();
    }
}
