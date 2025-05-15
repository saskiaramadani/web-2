<?php
/**
 * Penelitian Model
 * Handles all database operations related to research data
 */
class Penelitian
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    /**
     * Get all research data
     *
     * @return array Array of research records
     */
    public function getAll()
    {
        return $this->db->query("SELECT * FROM penelitian ORDER BY mulai DESC")->fetchAll();
    }

    /**
     * Get all research data with joined bidang_ilmu information
     *
     * @return array Array of research records with bidang_ilmu names
     */
    public function getAllWithBidangIlmu()
    {
        $sql = "SELECT p.*, bi.nama AS bidang_ilmu_nama 
                FROM penelitian p
                LEFT JOIN bidang_ilmu bi ON p.bidang_ilmu_id = bi.id
                ORDER BY p.mulai DESC";
        return $this->db->query($sql)->fetchAll();
    }

    /**
     * Get a specific research by ID
     *
     * @param int $id Research ID
     * @return array|false Research record or false if not found
     */
    public function getById($id)
    {
        $sql = "SELECT p.*, bi.nama AS bidang_ilmu_nama 
                FROM penelitian p
                LEFT JOIN bidang_ilmu bi ON p.bidang_ilmu_id = bi.id
                WHERE p.id = ?";
        return $this->db->query($sql, [$id])->fetchOne();
    }

    /**
     * Get research team members
     * 
     * @param int $penelitianId Research ID
     * @return array Array of dosen involved in the research
     */
    public function getTeamMembers($penelitianId)
    {
        $sql = "SELECT d.*, tp.peran 
                FROM tim_penelitian tp
                JOIN dosen d ON tp.dosen_id = d.id
                WHERE tp.penelitian_id = ?
                ORDER BY FIELD(tp.peran, 'ketua', 'anggota')";
        return $this->db->query($sql, [$penelitianId])->fetchAll();
    }

    /**
     * Create a new research record
     *
     * @param array $data Research data
     * @param array $teamMembers Array of dosen IDs and their roles
     * @return string|false The new research ID or false on failure
     */
    public function create($data)
    {
        $penelitianData = [
            'judul' => $data['judul'],
            'mulai' => $data['mulai'],
            'akhir' => $data['akhir'],
            'tahun_ajaran' => $data['tahun_ajaran'] ?? null,
            'bidang_ilmu_id' => $data['bidang_ilmu_id'] ?? null
        ];

        $penelitianId = $this->db->insert('penelitian', $penelitianData);

        // Add team members if provided
        if ($penelitianId && isset($data['team_members']) && is_array($data['team_members'])) {
            $this->updateTeamMembers($penelitianId, $data['team_members']);
        }

        return $penelitianId;
    }

    /**
     * Update an existing research record
     *
     * @param int $id Research ID to update
     * @param array $data Updated research data
     * @param array $teamMembers Array of dosen IDs and their roles
     * @return bool Success or failure
     */
    public function update($id, $data)
    {
        $penelitianData = [
            'judul' => $data['judul'],
            'mulai' => $data['mulai'],
            'akhir' => $data['akhir'],
            'tahun_ajaran' => $data['tahun_ajaran'] ?? null,
            'bidang_ilmu_id' => $data['bidang_ilmu_id'] ?? null
        ];

        $result = $this->db->update('penelitian', $penelitianData, 'id = ?', [$id]);

        // Update team members if provided
        if ($result && isset($data['team_members']) && is_array($data['team_members'])) {
            $this->updateTeamMembers($id, $data['team_members']);
        }

        return $result;
    }

    /**
     * Update team members for a research
     * 
     * @param int $penelitianId Research ID
     * @param array $teamMembers Array of dosen IDs and their roles
     * @return bool Success or failure
     */
    private function updateTeamMembers($penelitianId, $teamMembers)
    {
        // First delete existing team members
        $this->db->delete('tim_penelitian', 'penelitian_id = ?', [$penelitianId]);

        // Then insert new team members
        foreach ($teamMembers as $member) {
            $this->db->insert('tim_penelitian', [
                'penelitian_id' => $penelitianId,
                'dosen_id' => $member['dosen_id'],
                'peran' => $member['peran'] ?? 'anggota'
            ]);
        }

        return true;
    }

    /**
     * Delete a research record
     *
     * @param int $id Research ID to delete
     * @return bool Success or failure
     */
    public function delete($id)
    {
        // Delete will cascade to tim_penelitian due to foreign key constraint
        return $this->db->delete('penelitian', 'id = ?', [$id]);
    }

    /**
     * Get research statistics
     *
     * @return array Statistics data
     */
    public function getStatistics()
    {
        // Total count
        $total = $this->db->count('penelitian');

        // By bidang ilmu
        $byBidangIlmuQuery = "SELECT bi.nama, COUNT(*) as count 
                             FROM penelitian p
                             JOIN bidang_ilmu bi ON p.bidang_ilmu_id = bi.id
                             GROUP BY p.bidang_ilmu_id
                             ORDER BY count DESC";
        $byBidangIlmu = $this->db->query($byBidangIlmuQuery)->fetchAll();

        // By year
        $byYearQuery = "SELECT YEAR(mulai) as year, COUNT(*) as count 
                       FROM penelitian 
                       GROUP BY YEAR(mulai)
                       ORDER BY year DESC";
        $byYear = $this->db->query($byYearQuery)->fetchAll();

        // By tahun ajaran
        $byTahunAjaranQuery = "SELECT tahun_ajaran, COUNT(*) as count 
                              FROM penelitian 
                              WHERE tahun_ajaran IS NOT NULL
                              GROUP BY tahun_ajaran
                              ORDER BY tahun_ajaran DESC";
        $byTahunAjaran = $this->db->query($byTahunAjaranQuery)->fetchAll();

        return [
            'total' => $total,
            'byBidangIlmu' => $byBidangIlmu,
            'byYear' => $byYear,
            'byTahunAjaran' => $byTahunAjaran
        ];
    }

    /**
     * Get recent research (limit to specified number)
     *
     * @param int $limit Maximum number of records to return
     * @return array Array of recent research records
     */
    public function getRecent($limit = 5)
    {
        $sql = "SELECT p.*, bi.nama AS bidang_ilmu_nama 
                FROM penelitian p
                LEFT JOIN bidang_ilmu bi ON p.bidang_ilmu_id = bi.id
                ORDER BY p.mulai DESC
                LIMIT ?";
        return $this->db->query($sql, [$limit])->fetchAll();
    }

    /**
     * Search research by keyword
     *
     * @param string $keyword Search term
     * @return array Array of matching research records
     */
    public function search($keyword)
    {
        $searchTerm = "%{$keyword}%";
        $sql = "SELECT p.*, bi.nama AS bidang_ilmu_nama 
                FROM penelitian p
                LEFT JOIN bidang_ilmu bi ON p.bidang_ilmu_id = bi.id
                WHERE p.judul LIKE ? 
                   OR bi.nama LIKE ?
                   OR p.tahun_ajaran LIKE ?
                ORDER BY p.mulai DESC";
        return $this->db->query($sql, [
            $searchTerm,
            $searchTerm,
            $searchTerm
        ])->fetchAll();
    }

    /**
     * Get all researches for a specific dosen
     *
     * @param int $dosenId Dosen ID
     * @return array Array of research records
     */
    public function getByDosenId($dosenId)
    {
        $sql = "SELECT p.*, bi.nama AS bidang_ilmu_nama, tp.peran 
                FROM penelitian p
                JOIN tim_penelitian tp ON p.id = tp.penelitian_id
                LEFT JOIN bidang_ilmu bi ON p.bidang_ilmu_id = bi.id
                WHERE tp.dosen_id = ?
                ORDER BY p.mulai DESC";
        return $this->db->query($sql, [$dosenId])->fetchAll();
    }

    /**
     * Get bidang ilmu options
     *
     * @return array Array of bidang ilmu records
     */
    public function getBidangIlmuOptions()
    {
        return $this->db->query("SELECT id, nama FROM bidang_ilmu ORDER BY nama")->fetchAll();
    }

    public function count()
    {
        return $this->db->count('penelitian');
    }
}
?>