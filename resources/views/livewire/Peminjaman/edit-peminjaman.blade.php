<div class="container mx-auto">
    <h1 class="text-2xl font-bold mb-4">Edit Peminjaman {{ $peminjaman->id }}</h1>
    <form wire:submit.prevent="save" class="space-y-4">
        <!-- ID Peminjaman -->
        <flux:input type="text" id="id" wire:model.defer="id" label="ID Peminjaman"
            placeholder="Masukkan ID Peminjaman" required />
        
        <!-- Ruang ID -->
        <flux:input type="text" id="ruang_id" wire:model.defer="ruang_id" label="Ruang ID"
            placeholder="Masukkan Ruang ID" required />

        <!-- Pegawai ID -->
        <flux:input type="text" id="pegawai_id" wire:model.defer="pegawai_id" label="Pegawai ID"
            placeholder="Masukkan Pegawai ID" required />

        <!-- Tanggal Peminjaman -->
        <flux:input type="date" id="tanggal" wire:model.defer="tanggal" label="Tanggal Peminjaman" required />

        <!-- Jam Mulai Peminjaman -->
        <flux:input type="time" id="jam_mulai" wire:model.defer="jam_mulai" label="Jam Mulai Peminjaman" required />

        <!-- Jam Akhir Peminjaman -->
        <flux:input type="time" id="jam_akhir" wire:model.defer="jam_akhir" label="Jam Akhir Peminjaman" required />

        <!-- Keterangan Peminjaman -->
        <flux:input type="text" id="keterangan" wire:model.defer="keterangan" label="Keterangan"
            placeholder="Masukkan Keterangan" required />

        <flux:button type="submit" variant="primary">
            Save
        </flux:button>
    </form>
</div>
