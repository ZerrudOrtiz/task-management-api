<?php

namespace App\Console\Commands;

use App\Models\Task;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class DeleteOldTasks extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:delete-old-tasks';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete tasks older than 30 days';

    /**
     * Execute the console command.
     */
     public function handle()
     {
      $this->info('Command is running');

      $threshold = Carbon::now()->subDays(30);
      
      $tasksToDelete = Task::where('created_at', '<', $threshold)->get();
      
      foreach ($tasksToDelete as $task) {
          Log::info("Deleting Task", [
              'id' => $task->task_id,
              'title' => $task->title, 
              'created_at' => $task->created_at,
          ]);
      }
      
      $deletedCount = Task::where('created_at', '<', $threshold)->delete();
      
      $this->info("Deleted {$deletedCount} tasks older than 30 days.");
     }
}
