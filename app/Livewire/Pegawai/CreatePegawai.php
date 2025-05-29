<?php

namespace App\Livewire\Pegawai;

use App\Models\Pegawai;
use Livewire\Attributes\Validate;
use Livewire\Component;

class CreatePegawai extends Component
{
    #[Validate('required|integer')]
    public $id;

    #[Validate('required|string|max:50')]
    public $nip;

    #[Validate('required|string|max:100')]
    public $nama;

    #[Validate('required|integer')]
    public $unit_kerja_id;

    public function save()
    {
        $this->validate();

        Pegawai::create([
            'id'             => $this->id,
            'nip'            => $this->nip,
            'nama'           => $this->nama,
            'unit_kerja_id'  => $this->unit_kerja_id,
        ]);

        session()->flash('message', 'Pegawai berhasil ditambahkan.');

        $this->redirectRoute('pegawai.index');
    }

    public function render()
    {
        return view('livewire.pegawai.create-pegawai');
    }
}
