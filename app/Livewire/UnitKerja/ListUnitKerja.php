<?php

namespace App\Livewire\UnitKerja;

use App\Models\UnitKerja;
use Livewire\Component;

class ListUnitKerja extends Component
{
    /**
     * Menampilkan daftar unit kerja dari database.
     */
    public function render()
    {
        return view('livewire.unit-kerja.list-unit-kerja', [
            'unitKerjas' => UnitKerja::all(),
        ]);
    }

    /**
     * Menghapus data unit kerja berdasarkan ID.
     */
    public function delete($id)
    {
        $unitKerja = UnitKerja::find($id);

        if ($unitKerja) {
            $unitKerja->delete();
            session()->flash('message', 'Unit kerja berhasil dihapus.');
        }
    }
}
