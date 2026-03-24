<?php

namespace App\RoomTypes\Usecases\Request;
use Illuminate\Foundation\Http\FormRequest;

class RoomTypeRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $roomTypeId = $this->route('room_type')?->id ?? null;

        return [
            'name' => 'required|string|unique:room_types,name,' . $roomTypeId,
        ];
    }
}