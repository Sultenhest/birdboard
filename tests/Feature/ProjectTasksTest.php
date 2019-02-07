<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ProjectTasksTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_project_can_have_tasks()
    {
        $this->signIn();

        $project = factory('App\Project')->create(['owner_id' => auth()->id()]);

        $this->post($project->path() . '/tasks', ['body' => 'test task']);

        $this->get($project->path())->assertSee('test task');
    }

    /** @test */
    public function a_task_requires_a_body()
    {
        $this->signIn();

        // $project = auth()->user()->projects()->create(
        //     factory('App\Project')->raw()
        // );

        $project = factory('App\Project')->create(['owner_id' => auth()->id()]);

        $task = factory('App\Task')->raw(['body' => '']);

        $this->post($project->path() . '/tasks', $task)->assertSessionHasErrors('body');
    }
}
