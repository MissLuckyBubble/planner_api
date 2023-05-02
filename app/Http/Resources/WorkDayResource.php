<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class WorkDayResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $start_time = new \DateTime($this->start_time);
        $end_time = new \DateTime($this->end_time);
        $pause_start = new \DateTime($this->pause_start);
        $pause_end = new \DateTime($this->pause_end);
        $pauseh = $pause_start->diff($pause_end)->h;
        $totalh = $start_time->diff($end_time)->h;
        $workh = $totalh - $pauseh;
        return [
            'id' => (string)$this->id,
            'day_name' => $this->weekday->name,
            'name_eng' => $this->weekday->name_eng,
            'is_off' => (bool)$this->is_off,
            'times' => [
                'start' => $this->start_time,
                'end' => $this->end_time,
                'pause' => [
                    'start' => $this->pause_start,
                    'end' => $this->pause_end,
                ],
                'work_hours' => $workh,
                'pause_hours' => $pauseh,
                'total_hours' => $totalh
            ]

        ];
    }
}
