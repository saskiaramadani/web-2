<?php

namespace App\Livewire\Peminjaman;

use App\Models\Peminjaman;
use Livewire\Component;

class ListPeminjaman extends Component
{
    /**
     * Menampilkan daftar peminjaman dari database
     */
    public function render()
    {
        return view('livewire.peminjaman.list-peminjaman', [
            'peminjamans' => Peminjaman::all(),
        ]);
    }

    /**
     * Menghapus data peminjaman berdasarkan ID
     */
    public function delete($id)
    {
        $peminjaman = Peminjaman::find($id);

        if ($peminjaman) {
            $peminjaman->delete();
            session()->flash('message', 'Peminjaman berhasil dihapus.');
        }
    }
}
