<?php
class BidangIlmu
{
    private $db;

    public function __construct()
    {
        $this->db = new Database();
    }

    public function getAll()
    {
        return $this->db->query("SELECT * FROM bidang_ilmu ORDER BY nama ASC")->fetchAll();
    }

    public function getById($id)
    {
        return $this->db->query("SELECT * FROM bidang_ilmu WHERE id = ?", [$id])->fetchOne();
    }

    public function create($data)
    {
        return $this->db->insert('bidang_ilmu', $data);
    }

    public function update($id, $data)
    {
        return $this->db->update('bidang_ilmu', $data, 'id = ?', [$id]);
    }

    public function delete($id)
    {
        return $this->db->delete('bidang_ilmu', 'id = ?', [$id]);
    }

    public function count()
    {
        return $this->db->count('bidang_ilmu');
    }
}
