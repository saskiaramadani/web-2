<?php
class Dosen
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    public function getAll()
    {
        return $this->db->query("SELECT * FROM dosen ORDER BY nama")->fetchAll();
    }

    public function getAllWithProdi()
    {
        $sql = "SELECT d.*, p.nama as prodi_nama
                FROM dosen d
                LEFT JOIN prodi p ON d.prodi_id = p.id
                ORDER BY d.nama";
        return $this->db->query($sql)->fetchAll();
    }

    public function getById($id)
    {
        $sql = "SELECT d.*, p.nama as prodi_nama
                FROM dosen d
                LEFT JOIN prodi p ON d.prodi_id = p.id
                WHERE d.id = ?";
        return $this->db->query($sql, [$id])->fetchOne();
    }

    public function create($data)
    {
        return $this->db->insert('dosen', $data);
    }

    public function update($id, $data)
    {
        return $this->db->update('dosen', $data, 'id = ?', [$id]);
    }

    public function delete($id)
    {
        return $this->db->delete('dosen', 'id = ?', [$id]);
    }

    public function getWithProfil()
    {
        return $this->getAllWithProdi(); // Since profil info is now in dosen table
    }

    public function getByProdiId($prodiId)
    {
        $sql = "SELECT * FROM dosen WHERE prodi_id = ? ORDER BY nama";
        return $this->db->query($sql, [$prodiId])->fetchAll();
    }

    public function search($keyword)
    {
        $searchTerm = "%{$keyword}%";
        $sql = "SELECT d.*, p.nama as prodi_nama
                FROM dosen d
                LEFT JOIN prodi p ON d.prodi_id = p.id
                WHERE d.nama LIKE ? 
                   OR d.nidn LIKE ?
                   OR d.email LIKE ?
                   OR p.nama LIKE ?
                ORDER BY d.nama";
        return $this->db->query($sql, [
            $searchTerm,
            $searchTerm,
            $searchTerm,
            $searchTerm
        ])->fetchAll();
    }

    public function count()
    {
        return $this->db->count('dosen');
    }
}
?>