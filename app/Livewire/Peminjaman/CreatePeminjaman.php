<?php

namespace App\Livewire\Peminjaman;

use App\Models\Peminjaman;
use Livewire\Attributes\Validate;
use Livewire\Component;

class CreatePeminjaman extends Component
{
    #[Validate('required|integer')]
    public $ruang_id;

    #[Validate('required|integer')]
    public $pegawai_id;

    #[Validate('required|date')]
    public $tanggal;

    #[Validate('required')]
    public $jam_mulai;

    #[Validate('required')]
    public $jam_akhir;

    #[Validate('nullable|string|max:255')]
    public $keterangan = '';

    public function save()
    {
        $this->validate();

        Peminjaman::create([
            'ruang_id'    => $this->ruang_id,
            'pegawai_id'  => $this->pegawai_id,
            'tanggal'     => $this->tanggal,
            'jam_mulai'   => $this->jam_mulai,
            'jam_akhir'   => $this->jam_akhir,
            'keterangan'  => $this->keterangan,
        ]);

        session()->flash('message', 'Peminjaman berhasil ditambahkan.');

        $this->redirectRoute('peminjaman.index');
    }

    public function render()
    {
        return view('livewire.peminjaman.create-peminjaman');
    }
}
