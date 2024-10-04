<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CategoryResouce extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        // return parent::toArray($request);

        return [
            'title' => $this->title,
            'slug' => $this->url,
            'description' => $this->description,
            'created_at' => Carbon::make($this->created_at)->format('d/m/Y')
        ];
    }
}
