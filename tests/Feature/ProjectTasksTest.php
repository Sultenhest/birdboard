<?php

namespace Tests\Feature;

use App\Project;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ProjectTasksTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function guests_cannot_add_tasks_to_projects()
    {
        $project = factory('App\Project')->create();

        $this->post($project->path() . '/tasks')->assertRedirect('login');
    }

    /** @test */
    public function only_the_owner_of_a_project_may_add_tasks()
    {
        $this->signIn();

        $project = factory('App\Project')->create();

        $this->post($project->path() . '/tasks', ['body' => 'test task'])->assertForbidden();

        $this->assertDatabaseMissing('tasks', ['body' => 'test task']);
    }

    /** @test */
    public function only_the_owner_of_a_project_may_update_a_task()
    {
        $this->signIn();

        $project = factory('App\Project')->create();

        $task = $project->addTask('Test Task');

        $this->patch($task->path(), ['body' => 'changed'])->assertForbidden();

        $this->assertDatabaseMissing('tasks', ['body' => 'changed']);
    }

    /** @test */
    public function a_project_can_have_tasks()
    {
        $this->signIn();

        $project = factory('App\Project')->create(['owner_id' => auth()->id()]);

        $this->post($project->path() . '/tasks', ['body' => 'Test Task']);

        $this->get($project->path())->assertSee('Test Task');
    }

    /** @test */
    public function a_task_can_be_updated()
    {
        $this->withoutExceptionHandling();
        $this->signIn();

        $project = auth()->user()->projects()->create(
            factory(Project::class)->raw()
        );

        $task = $project->addTask('Test Task');

        $this->patch($task->path(), [
            'body' => 'Changed',
            'completed' => true
        ]);

        $this->assertDatabaseHas('tasks', [
            'body' => 'Changed',
            'completed' => true
        ]);
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
