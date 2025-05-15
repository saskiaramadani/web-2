<?php
class Prodi
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    public function getAll()
    {
        return $this->db->query("SELECT * FROM prodi ORDER BY nama")->fetchAll();
    }

    public function getById($id)
    {
        return $this->db->query("SELECT * FROM prodi WHERE id = ?", [$id])->fetchOne();
    }

    public function create($data)
    {
        return $this->db->insert('prodi', $data);
    }

    public function update($id, $data)
    {
        return $this->db->update('prodi', $data, 'id = ?', [$id]);
    }

    public function delete($id)
    {
        return $this->db->delete('prodi', 'id = ?', [$id]);
    }
}
?>