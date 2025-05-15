<?php
class JenisKegiatan
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    public function getAll()
    {
        return $this->db->query("
            SELECT * 
            FROM jenis_kegiatan
            ORDER BY nama ASC
        ")->fetchAll();
    }

    public function getById($id)
    {
        return $this->db->query("
            SELECT * 
            FROM jenis_kegiatan 
            WHERE id = ?
        ", [$id])->fetchOne();
    }

    public function create($data)
    {
        // Ensure only valid fields are inserted
        $validData = [
            'nama' => $data['nama']
        ];

        return $this->db->insert('jenis_kegiatan', $validData);
    }

    public function update($id, $data)
    {
        // Ensure only valid fields are updated
        $validData = [
            'nama' => $data['nama']
        ];

        return $this->db->update('jenis_kegiatan', $validData, 'id = ?', [$id]);
    }

    public function delete($id)
    {
        // Check if this jenis_kegiatan is being used by any kegiatan
        $kegiatanCount = $this->db->query("
            SELECT COUNT(*) as count 
            FROM kegiatan 
            WHERE jenis_kegiatan_id = ?
        ", [$id])->fetchOne()['count'];

        if ($kegiatanCount > 0) {
            // Cannot delete as it's being referenced
            return false;
        }

        return $this->db->delete('jenis_kegiatan', 'id = ?', [$id]);
    }

    public function count()
    {
        return $this->db->count('jenis_kegiatan');
    }

    public function getKegiatanCount($jenisKegiatanId)
    {
        return $this->db->query("
            SELECT COUNT(*) as count
            FROM kegiatan
            WHERE jenis_kegiatan_id = ?
        ", [$jenisKegiatanId])->fetchOne()['count'];
    }

    public function getUsageStatistics()
    {
        return $this->db->query("
            SELECT 
                jk.id,
                jk.nama,
                COUNT(k.id) as kegiatan_count
            FROM jenis_kegiatan jk
            LEFT JOIN kegiatan k ON jk.id = k.jenis_kegiatan_id
            GROUP BY jk.id
            ORDER BY kegiatan_count DESC, jk.nama ASC
        ")->fetchAll();
    }
}
