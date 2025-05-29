<?php

namespace App\Livewire\Peminjaman;

use App\Models\Peminjaman;
use Livewire\Attributes\Validate;
use Livewire\Component;

class EditPeminjaman extends Component
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

    public Peminjaman $peminjaman;

    public function mount(Peminjaman $peminjaman)
    {
        $this->peminjaman = $peminjaman;
        $this->ruang_id = $peminjaman->ruang_id;
        $this->pegawai_id = $peminjaman->pegawai_id;
        $this->tanggal = $peminjaman->tanggal;
        $this->jam_mulai = $peminjaman->jam_mulai;
        $this->jam_akhir = $peminjaman->jam_akhir;
        $this->keterangan = $peminjaman->keterangan;
    }

    public function save()
    {
        $this->validate();

        $this->peminjaman->update([
            'ruang_id'   => $this->ruang_id,
            'pegawai_id' => $this->pegawai_id,
            'tanggal'    => $this->tanggal,
            'jam_mulai'  => $this->jam_mulai,
            'jam_akhir'  => $this->jam_akhir,
            'keterangan' => $this->keterangan,
        ]);

        session()->flash('message', 'Peminjaman berhasil diperbarui.');

        return redirect()->route('peminjaman.index');
    }

    public function render()
    {
        return view('livewire.peminjaman.edit-peminjaman');
    }
}
