<?php

namespace App\Livewire\Pegawai;

use App\Models\Pegawai;
use Livewire\Component;

class ListPegawai extends Component
{
    /**
     * Menampilkan daftar pegawai dari database
     */
    public function render()
    {
        return view('livewire.pegawai.list-pegawai', [
            'pegawais' => Pegawai::all(),
        ]);
    }

    /**
     * Menghapus data pegawai berdasarkan ID
     */
    public function delete($id)
    {
        $pegawai = Pegawai::find($id);

        if ($pegawai) {
            $pegawai->delete();
            session()->flash('message', 'Pegawai berhasil dihapus.');
        }
    }
}
