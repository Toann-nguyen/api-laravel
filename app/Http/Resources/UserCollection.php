<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class UserCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'data' =>UserResource::collection($this->collection),
            'meta' =>[
                'total' => $this->collection->count(),
                'per_page' => $request->get('per_page', 10),
                'curr_page' => $request->get('page', 1)
            ]
        ];
    }
}
