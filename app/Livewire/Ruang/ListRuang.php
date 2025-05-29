<?php
 
namespace App\Livewire\Ruang;
 
use App\Models\Ruang;
use Livewire\Component;
 
class ListRuang extends Component
{
    /**
     * Menampilkan daftar ruang yang ada di database
     * dan mengirimkan data tersebut ke view.
     * Menggunakan model Ruang untuk mengambil data ruang dari database dengan variabel $ruangs
     */
    public function render()
    {
        return view('livewire.ruang.list-ruang', [
            'ruangs' => Ruang::all(),
        ]);
    }
 
    /**
     * Membuat method untuk menghapus data ruang
     */
    public function delete($id)
    {
        // Menggunakan model Ruang untuk mencari data ruang berdasarkan id yang diberikan
        $ruang = Ruang::find($id);
 
        // Jika data ruang ditemukan, maka hapus data tersebut dari database dan tampilkan pesan sukses
        // Menggunakan session untuk menyimpan pesan sukses setelah data ruang berhasil dihapus
        if ($ruang) {
            $ruang->delete();
            session()->flash('message', 'Ruang berhasil dihapus.');
        }
    }
}
