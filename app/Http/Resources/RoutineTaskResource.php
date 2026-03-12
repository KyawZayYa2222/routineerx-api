<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RoutineTaskResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */

    protected $dayNames = [
        0 => 'Sunday',
        1 => 'Monday',
        2 => 'Tuesday',
        3 => 'Wednesday',
        4 => 'Thursday',
        5 => 'Friday',
        6 => 'Saturday',
    ];

    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            // 'parent_id' => $this->parent_id,
            'title' => $this->title,
            'description' => $this->description,
            'repeat_days' => collect($this->repeat_days)->map(function ($day) {
                return [
                    'day' => (int) $day,
                    'name' => $this->dayNames[(int) $day] ?? 'Unknown',
                ];
            }),
            'start_at' => $this->start_at ?? null,
            'end_at' => $this->end_at ?? null,
            'deactivated_at' => $this->deactivated_at ? $this->deactivated_at->format('Y-m-d H:i:s') : null,
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
            // 'updated_at' => $this->updated_at->format('Y-m-d H:i:s'),
            'sub_tasks' => RoutineTaskResource::collection($this->whenLoaded('subTasks')),
        ];
    }
}
