<?php
declare(strict_types=1);

namespace App\Models;

use App\Core\Model;

class Citizen extends Model
{
    protected string $table = 'citizens';

    public function search(array $filters = []): array
    {
        $sql = 'SELECT * FROM citizens WHERE 1 = 1';
        $params = [];

        foreach ($filters as $key => $value) {
            if ($value === null || $value === '') {
                continue;
            }
            $sql .= ' AND ' . $key . ' LIKE :_' . $key;
            $params[':_' . $key] = '%' . $value . '%';
        }

        $sql .= ' ORDER BY created_at DESC';
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);

        return $stmt->fetchAll();
    }
}
