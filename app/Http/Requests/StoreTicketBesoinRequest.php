<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreTicketBesoinRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // Autoriser uniquement les utilisateurs authentifiés
        return auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'titre' => 'required|string|max:255',
            'description' => 'required|string',
            'id_categorie' => 'required|exists:ticket_categories,id',
            'priorite' => 'required|in:basse,normal,haute,urgente',
            'piece_jointe' => 'nullable|file|mimes:jpg,jpeg,png,pdf,doc,docx,zip|max:5120', // 5 Mo max
        ];
    }
}
