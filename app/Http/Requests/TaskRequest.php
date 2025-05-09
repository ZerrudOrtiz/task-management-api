<?php

namespace App\Http\Requests;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class TaskRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $task_id = $this->route('task')?->task_id; // or just $this->task->id if using route model binding

        return [
            'title' => [
                'required',
                'string',
                'max:100',
                Rule::unique('tasks', 'title')->ignore($task_id, 'task_id'), // ignore current task
            ],
            'description' => 'nullable|string',
            'due_date' => 'nullable|date',
            'status' => 'required|in:todo,in_progress,done',
            'priority' => 'required|in:low,medium,high',
            'order' => 'required|integer',
            'user_id' => 'exists:users,user_id',
            'attachments' => 'nullable|file|max:4096',
        ];
    }
}
