<div class="container mx-auto">
    <h1 class="text-2xl font-bold mb-4">Edit Pegawai {{ $pegawai->id }}</h1>
    <form wire:submit.prevent="save" class="space-y-4">
        <!-- ID -->
        <flux:input type="text" id="id" wire:model.defer="id" label="ID"
            placeholder="Masukkan ID Pegawai" required />

        <!-- NIP -->
        <flux:input type="text" id="nip" wire:model.defer="nip" label="NIP"
            placeholder="Masukkan NIP" required />

        <!-- Nama -->
        <flux:input type="text" id="nama" wire:model.defer="nama" label="Nama"
            placeholder="Masukkan Nama Pegawai" required />

        <!-- Unit Kerja ID -->
        <flux:input type="text" id="unit_kerja_id" wire:model.defer="unit_kerja_id" label="Unit Kerja ID"
            placeholder="Masukkan ID Unit Kerja" required />

        <!-- Tombol Simpan -->
        <flux:button type="submit" variant="primary">
            Save
        </flux:button>
    </form>
</div>
