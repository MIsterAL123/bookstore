<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCategoryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->role === 'admin';
    }

    /**
     * Aturan validasi saat mengupdate kategori
     */
    public function rules(): array
    {
        $id = $this->route('category') ? $this->route('category')->id : $this->id;
        return [
            'name' => 'required|string|max:100|unique:categories,name,' . $id,
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Nama kategori wajib diisi.',
            'name.unique' => 'Nama kategori sudah digunakan.',
        ];
    }
}
