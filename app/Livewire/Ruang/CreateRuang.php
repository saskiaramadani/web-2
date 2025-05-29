<?php
 
namespace App\Livewire\Ruang;
 
use App\Models\Ruang;
use Livewire\Attributes\Validate;
use Livewire\Component;
 
class CreateRuang extends Component
{
    // Menambahkan atribut untuk validasi data yang diinputkan yaitu kode
    #[Validate('required|string|max:10')]
    // Membuat property kode untuk menyimpan data ruang yang akan ditambahkan
    public string $kode = '';
 
    // Menambahkan atribut untuk validasi data yang diinputkan yaitu nama
    #[Validate('required|string|max:100')]
    // Membuat property nama untuk menyimpan data ruang yang akan ditambahkan
    public $nama = '';
 
    // Menambahkan atribut untuk validasi data yang diinputkan yaitu status
    #[Validate('required|string|max:50')]
    // Membuat property status untuk menyimpan data ruang yang akan ditambahkan
    public $status = '';
 
    // Mendefinisikan method save untuk menyimpan data ruang yang diinputkan oleh user ke dalam database
    public function save()
    {
        // Melakukan validasi data yang diinputkan oleh user
        $this->validate();
 
        // Menggunakan model Ruang untuk menyimpan data ruang yang diinputkan oleh user ke dalam database
        Ruang::create([
            'kode' => $this->kode,
            'nama' => $this->nama,
            'status' => $this->status,
        ]);
 
        // Menggunakan session untuk menyimpan pesan sukses setelah data ruang berhasil ditambahkan
        session()->flash('message', 'Ruang berhasil ditambahkan.');
 
        // Melakukan redirect ke halaman list ruang setelah data ruang berhasil ditambahkan
        $this->redirectRoute('ruang.index');
    }
 
    public function render()
    {
        return view('livewire.ruang.create-ruang');
    }
}