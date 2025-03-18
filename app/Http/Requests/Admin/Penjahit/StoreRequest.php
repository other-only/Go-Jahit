<?php

namespace App\Http\Requests\Admin\Penjahit;

use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends FormRequest
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
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'foto_toko' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'nama_toko' => 'required|string|max:255',
            'alamat_toko' => 'required|string|max:500',
            'deskripsi_toko' => 'required|string|max:1000',
            'no_wa' => 'required|string|max:15',
            'bank' => 'required|string',
            'no_rekening' => 'required|string|max:20',
            'atas_nama' => 'required|string|max:255',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Nama penjahit wajib diisi',
            'name.max' => 'Nama penjahit maksimal 255 karakter',

            'email.required' => 'Email wajib diisi',
            'email.email' => 'Format email tidak valid',
            'email.unique' => 'Email sudah terdaftar',

            'password.required' => 'Password wajib diisi',
            'password.min' => 'Password minimal 8 karakter',
            'password.confirmed' => 'Konfirmasi password tidak cocok',

            'foto_toko.required' => 'Logo toko wajib diupload',
            'foto_toko.image' => 'File harus berupa gambar',
            'foto_toko.mimes' => 'Format gambar harus jpeg, png, atau jpg',
            'foto_toko.max' => 'Ukuran gambar maksimal 2MB',

            'nama_toko.required' => 'Nama toko wajib diisi',
            'nama_toko.max' => 'Nama toko maksimal 255 karakter',

            'alamat_toko.required' => 'Alamat toko wajib diisi',
            'alamat_toko.max' => 'Alamat toko maksimal 500 karakter',

            'deskripsi_toko.required' => 'Deskripsi toko wajib diisi',
            'deskripsi_toko.max' => 'Deskripsi toko maksimal 1000 karakter',
        ];
    }
}
