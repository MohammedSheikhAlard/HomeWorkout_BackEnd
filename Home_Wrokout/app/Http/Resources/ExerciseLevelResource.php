<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ExerciseLevelResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'level_id' => $this->level_id,
            'exercise_id' => $this->exercise_id,
            'calories' => $this->calories,
            'number_of_rips' => $this->number_of_rips,
            'exercise' => new ExerciseResource($this->whenLoaded('exercise'))
        ];
    }
}
