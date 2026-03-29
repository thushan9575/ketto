<?php
namespace App\Bookings\Usecases\Request;

use Illuminate\Foundation\Http\FormRequest;

class CreateBookingRequest extends FormRequest
{
    public function rules()
    {
        return [
            'name' => 'required',
            'email' => 'required|email',
            'arrival' => 'required|date',
            'departure' => 'required|date|after:arrival',
            'room_id' => 'required|exists:rooms,id',
        ];
    }
}