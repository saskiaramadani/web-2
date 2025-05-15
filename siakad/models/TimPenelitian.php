<?php
class TimPenelitian
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    /**
     * Get all penelitian with member counts
     */
    public function getAll()
    {
        $penelitian = $this->db->query("
            SELECT p.id, p.judul, p.mulai, p.akhir, p.tahun_ajaran, 
                   bi.nama AS bidang_ilmu_nama,
                   (SELECT COUNT(*) FROM tim_penelitian WHERE penelitian_id = p.id) AS jumlah_anggota
            FROM penelitian p
            LEFT JOIN bidang_ilmu bi ON p.bidang_ilmu_id = bi.id
            ORDER BY p.tahun_ajaran DESC, p.judul ASC
        ")->fetchAll();

        // Load team members for each penelitian
        foreach ($penelitian as &$p) {
            $p['tim_members'] = $this->getAnggotaTim($p['id']);
        }

        return $penelitian;
    }

    /**
     * Get penelitian by ID with detailed info
     */
    public function getPenelitianById($id)
    {
        // First, fix the SQL syntax error (removed trailing comma)
        $penelitian = $this->db->query("
        SELECT p.*, bi.nama AS bidang_ilmu_nama
        FROM penelitian p
        LEFT JOIN bidang_ilmu bi ON p.bidang_ilmu_id = bi.id
        WHERE p.id = ?
    ", [$id])->fetchOne();

        // Then, add team members data if penelitian exists
        if ($penelitian) {
            $penelitian['tim_members'] = $this->getAnggotaTim($id);
        }

        return $penelitian;
    }

    /**
     * Get all members of a research team
     */
    public function getAnggotaTim($penelitianId)
    {
        return $this->db->query("
            SELECT tp.*, d.nama, d.nidn, d.email, d.gelar_depan, d.gelar_belakang
            FROM tim_penelitian tp
            JOIN dosen d ON tp.dosen_id = d.id
            WHERE tp.penelitian_id = ?
            ORDER BY 
                CASE 
                    WHEN tp.peran = 'Ketua' THEN 1
                    WHEN tp.peran = 'Wakil Ketua' THEN 2
                    ELSE 3 
                END,
                d.nama ASC
        ", [$penelitianId])->fetchAll();
    }

    /**
     * Check if a lecturer is already part of a research team
     */
    public function isDosenInTim($penelitianId, $dosenId)
    {
        $result = $this->db->query("
            SELECT COUNT(*) as count
            FROM tim_penelitian
            WHERE penelitian_id = ? AND dosen_id = ?
        ", [$penelitianId, $dosenId])->fetchOne();

        return $result['count'] > 0;
    }

    /**
     * Get all lecturers
     */
    public function getDosenOptions()
    {
        return $this->db->query("
            SELECT id, nama, nidn, gelar_depan, gelar_belakang
            FROM dosen
            ORDER BY nama ASC
        ")->fetchAll();
    }

    /**
     * Get lecturers not in the team
     */
    public function getAvailableDosen($penelitianId)
    {
        return $this->db->query("
            SELECT d.id, d.nama, d.nidn, d.gelar_depan, d.gelar_belakang
            FROM dosen d
            WHERE d.id NOT IN (
                SELECT dosen_id 
                FROM tim_penelitian 
                WHERE penelitian_id = ?
            )
            ORDER BY d.nama ASC
        ", [$penelitianId])->fetchAll();
    }

    /**
     * Add a member to the research team
     */
    public function addAnggota($penelitianId, $dosenId, $peran = 'Anggota')
    {
        // Check if already exists
        if ($this->isDosenInTim($penelitianId, $dosenId)) {
            // Update role instead
            return $this->updatePeranAnggota($penelitianId, $dosenId, $peran);
        }

        return $this->db->insert('tim_penelitian', [
            'penelitian_id' => $penelitianId,
            'dosen_id' => $dosenId,
            'peran' => $peran
        ]);
    }

    /**
     * Update a team member's role
     */
    public function updatePeranAnggota($penelitianId, $dosenId, $peran)
    {
        return $this->db->query("
            UPDATE tim_penelitian 
            SET peran = ? 
            WHERE penelitian_id = ? AND dosen_id = ?
        ", [$peran, $penelitianId, $dosenId]);
    }

    /**
     * Remove a member from the research team
     */
    public function removeAnggota($penelitianId, $dosenId)
    {
        return $this->db->delete('tim_penelitian', 'penelitian_id = ? AND dosen_id = ?', [$penelitianId, $dosenId]);
    }

    /**
     * Update multiple team members at once
     */
    public function updateTimAnggota($penelitianId, $anggotaData)
    {
        $this->db->beginTransaction();

        try {
            // Remove all existing members
            $this->db->delete('tim_penelitian', 'penelitian_id = ?', [$penelitianId]);

            // Add selected members with their roles
            foreach ($anggotaData as $dosenId => $peran) {
                $this->db->insert('tim_penelitian', [
                    'penelitian_id' => $penelitianId,
                    'dosen_id' => $dosenId,
                    'peran' => $peran
                ]);
            }

            $this->db->commit();
            return true;
        } catch (Exception $e) {
            $this->db->rollback();
            return false;
        }
    }

    /**
     * Count total research projects
     */
    public function count()
    {
        return $this->db->count('penelitian');
    }
}