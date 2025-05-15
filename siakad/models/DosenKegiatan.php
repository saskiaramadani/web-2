<?php
class DosenKegiatan {
    private $db;
    
    public function __construct() {
        $this->db = new Database();
    }
    
    public function getAll() {
        return $this->db->query("
            SELECT dk.*, d.nama as nama_dosen, k.nama as nama_kegiatan
            FROM dosen_kegiatan dk
            JOIN dosen d ON dk.dosen_id = d.id
            JOIN kegiatan k ON dk.kegiatan_id = k.id
            ORDER BY k.tanggal DESC
        ")->fetchAll();
    }
    
    public function getById($id) {
        return $this->db->query("
            SELECT dk.*, d.nama as nama_dosen, k.nama as nama_kegiatan
            FROM dosen_kegiatan dk
            JOIN dosen d ON dk.dosen_id = d.id
            JOIN kegiatan k ON dk.kegiatan_id = k.id
            WHERE dk.id = ?
        ", [$id])->fetchOne();
    }
    
    public function create($data) {
        return $this->db->insert('dosen_kegiatan', $data);
    }
    
    public function update($id, $data) {
        return $this->db->update('dosen_kegiatan', $data, 'id = ?', [$id]);
    }
    
    public function delete($id) {
        return $this->db->delete('dosen_kegiatan', 'id = ?', [$id]);
    }
    
    public function count() {
        return $this->db->count('dosen_kegiatan');
    }
    
    public function getByDosenId($dosenId) {
        return $this->db->query("
            SELECT dk.*, k.nama as nama_kegiatan, k.tanggal
            FROM dosen_kegiatan dk
            JOIN kegiatan k ON dk.kegiatan_id = k.id
            WHERE dk.dosen_id = ?
            ORDER BY k.tanggal DESC
        ", [$dosenId])->fetchAll();
    }
    
    public function getByKegiatanId($kegiatanId) {
        return $this->db->query("
            SELECT dk.*, d.nama as nama_dosen, d.nidn
            FROM dosen_kegiatan dk
            JOIN dosen d ON dk.dosen_id = d.id
            WHERE dk.kegiatan_id = ?
            ORDER BY d.nama ASC
        ", [$kegiatanId])->fetchAll();
    }
}
