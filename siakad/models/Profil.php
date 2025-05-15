<?php
class Profil
{
    private $db;

    public function __construct()
    {
        $this->db = new Database();
    }

    public function getAll()
    {
        return $this->db->query("
            SELECT p.*, d.nama as nama_dosen
            FROM profil p
            JOIN dosen d ON p.dosen_id = d.id
            ORDER BY d.nama ASC
        ")->fetchAll();
    }

    public function getById($id)
    {
        return $this->db->query("SELECT * FROM profil WHERE id = ?", [$id])->fetchOne();
    }

    public function getByDosenId($dosenId)
    {
        return $this->db->query("SELECT * FROM profil WHERE dosen_id = ?", [$dosenId])->fetchOne();
    }

    public function create($data)
    {
        return $this->db->insert('profil', $data);
    }

    public function update($id, $data)
    {
        return $this->db->update('profil', $data, 'id = ?', [$id]);
    }

    public function delete($id)
    {
        return $this->db->delete('profil', 'id = ?', [$id]);
    }

    public function count()
    {
        return $this->db->count('profil');
    }
}
