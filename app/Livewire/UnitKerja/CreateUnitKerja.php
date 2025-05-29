<?php

namespace App\Livewire\UnitKerja;

use App\Models\UnitKerja;
use Livewire\Attributes\Validate;
use Livewire\Component;

class CreateUnitKerja extends Component
{
    #[Validate('required|string|max:10')]
    public string $kode = '';

    #[Validate('required|string|max:100')]
    public $nama = '';

   
    public function save()
    {
        $this->validate();

        UnitKerja::create([
            'kode' => $this->kode,
            'nama' => $this->nama,
          
        ]);

        session()->flash('message', 'Unit Kerja berhasil ditambahkan.');

        $this->redirectRoute('unit-kerja.index');
    }

    public function render()
    {
        return view('livewire.unit-kerja.create-unit-kerja');
    }
}
