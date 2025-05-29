<div class="container mx-auto">
    <h1 class="text-2xl font-bold mb-4">Create Unit Kerja</h1>
    <form wire:submit.prevent="save" class="space-y-4">
        <flux:input type="text" id="kode" wire:model.defer="kode" label="Kode Unit Kerja"
            placeholder="Masukkan Kode Unit Kerja" required />
            
        <flux:input type="text" id="nama" wire:model.defer="nama" label="Nama Unit Kerja"
            placeholder="Masukkan Nama Unit Kerja" required />
        
       
        <flux:button type="submit" variant="primary">
            Simpan
        </flux:button>
    </form>
</div>
