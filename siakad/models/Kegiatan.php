<?php
class Kegiatan
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    public function getAll()
    {
        return $this->db->query("
            SELECT k.*, jk.nama as jenis_kegiatan_nama,
                   (SELECT COUNT(*) FROM dosen_kegiatan WHERE kegiatan_id = k.id) as jumlah_anggota
            FROM kegiatan k
            LEFT JOIN jenis_kegiatan jk ON k.jenis_kegiatan_id = jk.id
            ORDER BY k.tanggal_mulai DESC
        ")->fetchAll();
    }

    public function getById($id)
    {
        return $this->db->query("
            SELECT k.*, jk.nama as jenis_kegiatan_nama
            FROM kegiatan k
            LEFT JOIN jenis_kegiatan jk ON k.jenis_kegiatan_id = jk.id
            WHERE k.id = ?
        ", [$id])->fetchOne();
    }

    public function create($data)
    {
        // Ensure data matches the schema and properly handle empty values
        $kegiatanData = [
            'nama' => $data['nama'],
            'deskripsi' => $data['deskripsi'] ?? null,
            'tanggal_mulai' => $data['tanggal_mulai'] ?? null,
            'tanggal_selesai' => $data['tanggal_selesai'] ?? null,
            'jenis_kegiatan_id' => !empty($data['jenis_kegiatan_id']) ? (int) $data['jenis_kegiatan_id'] : null,
            'tempat' => $data['tempat'] ?? null,
        ];

        $kegiatanId = $this->db->insert('kegiatan', $kegiatanData);

        // Add dosen associations if provided
        if ($kegiatanId && isset($data['dosen']) && is_array($data['dosen'])) {
            foreach ($data['dosen'] as $dosenId) {
                $peran = isset($data['peran'][$dosenId]) ? $data['peran'][$dosenId] : 'Anggota';

                $this->db->insert('dosen_kegiatan', [
                    'kegiatan_id' => $kegiatanId,
                    'dosen_id' => (int) $dosenId,
                    'peran' => $peran
                ]);
            }
        }

        return $kegiatanId;
    }

    public function update($id, $data)
    {
        $kegiatanData = [
            'nama' => $data['nama'],
            'deskripsi' => $data['deskripsi'] ?? null,
            'tanggal_mulai' => $data['tanggal_mulai'] ?? null,
            'tanggal_selesai' => $data['tanggal_selesai'] ?? null,
            'jenis_kegiatan_id' => !empty($data['jenis_kegiatan_id']) ? (int) $data['jenis_kegiatan_id'] : null,
            'tempat' => $data['tempat'] ?? null,
        ];

        $result = $this->db->update('kegiatan', $kegiatanData, 'id = ?', [$id]);

        // Update dosen associations if provided
        if ($result && isset($data['dosen']) && is_array($data['dosen'])) {
            // Remove existing associations
            $this->db->delete('dosen_kegiatan', 'kegiatan_id = ?', [$id]);

            // Add new associations
            foreach ($data['dosen'] as $dosenId) {
                $peran = isset($data['peran'][$dosenId]) ? $data['peran'][$dosenId] : 'Anggota';

                $this->db->insert('dosen_kegiatan', [
                    'kegiatan_id' => $id,
                    'dosen_id' => (int) $dosenId,
                    'peran' => $peran
                ]);
            }
        }

        return $result;
    }

    public function delete($id)
    {
        // The dosen_kegiatan entries will be deleted due to CASCADE constraint
        return $this->db->delete('kegiatan', 'id = ?', [$id]);
    }

    public function count()
    {
        return $this->db->count('kegiatan');
    }

    public function getRecent($limit = 5)
    {
        return $this->db->query("
            SELECT k.*, jk.nama as jenis_kegiatan_nama
            FROM kegiatan k
            LEFT JOIN jenis_kegiatan jk ON k.jenis_kegiatan_id = jk.id
            ORDER BY k.tanggal_mulai DESC
            LIMIT ?
        ", [$limit])->fetchAll();
    }

    public function getParticipants($kegiatanId)
    {
        return $this->db->query("
            SELECT d.*, dk.peran
            FROM dosen d
            JOIN dosen_kegiatan dk ON d.id = dk.dosen_id
            WHERE dk.kegiatan_id = ?
            ORDER BY dk.peran = 'Ketua' DESC, d.nama ASC
        ", [$kegiatanId])->fetchAll();
    }

    public function getKegiatanStats()
    {
        return $this->db->query("
            SELECT jk.nama, COUNT(k.id) as jumlah
            FROM kegiatan k
            JOIN jenis_kegiatan jk ON k.jenis_kegiatan_id = jk.id
            GROUP BY jk.id
            ORDER BY jumlah DESC
        ")->fetchAll();
    }

    public function getByDosenId($dosenId)
    {
        return $this->db->query("
            SELECT k.*, jk.nama as jenis_kegiatan_nama,
                   dk.peran
            FROM kegiatan k
            JOIN dosen_kegiatan dk ON k.id = dk.kegiatan_id
            LEFT JOIN jenis_kegiatan jk ON k.jenis_kegiatan_id = jk.id
            WHERE dk.dosen_id = ?
            ORDER BY k.tanggal_mulai DESC
        ", [$dosenId])->fetchAll();
    }

    public function getJenisKegiatanOptions()
    {
        return $this->db->query("
            SELECT id, nama
            FROM jenis_kegiatan
            ORDER BY nama ASC
        ")->fetchAll();
    }

    public function countKegiatanByJenis()
    {
        return $this->db->query("
            SELECT jk.id, jk.nama, COUNT(k.id) as jumlah
            FROM jenis_kegiatan jk
            LEFT JOIN kegiatan k ON jk.id = k.jenis_kegiatan_id
            GROUP BY jk.id
            ORDER BY jumlah DESC
        ")->fetchAll();
    }

    public function countKegiatanByMonth($year = null)
    {
        $year = $year ?? date('Y');

        return $this->db->query("
            SELECT 
                MONTH(tanggal_mulai) as bulan,
                COUNT(*) as jumlah
            FROM kegiatan
            WHERE YEAR(tanggal_mulai) = ?
            GROUP BY MONTH(tanggal_mulai)
            ORDER BY bulan ASC
        ", [$year])->fetchAll();
    }

    public function getDosenWithMostKegiatan($limit = 5)
    {
        return $this->db->query("
            SELECT 
                d.id, d.nama, d.nidn, d.gelar_depan, d.gelar_belakang,
                COUNT(dk.id) as jumlah_kegiatan
            FROM dosen d
            JOIN dosen_kegiatan dk ON d.id = dk.dosen_id
            GROUP BY d.id
            ORDER BY jumlah_kegiatan DESC
            LIMIT ?
        ", [$limit])->fetchAll();
    }
}