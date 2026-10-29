<?php
declare(strict_types=1);

namespace App\Core;

use PDO;

abstract class Model
{

    protected PDO $db;


    protected string $table = '';



    public function __construct()
    {

        $this->db =
        Database::connection();

    }





    /**
     * Get All Records
     */
    public function all(): array
    {

        $query =
        "SELECT * FROM {$this->table}";


        return $this->db
        ->query($query)
        ->fetchAll(PDO::FETCH_ASSOC);

    }





    /**
     * Find Record
     */
    public function find(
        int $id
    )
    {

        $stmt =
        $this->db->prepare(
            "SELECT * FROM {$this->table}
             WHERE id=:id LIMIT 1"
        );


        $stmt->execute([
            ':id'=>$id
        ]);


        return $stmt
        ->fetch(PDO::FETCH_ASSOC);

    }





    /**
     * Insert Data
     */
    public function create(
        array $data
    ): bool
    {


        $columns =
        implode(
            ',',
            array_keys($data)
        );


        $values =
        ':'.implode(
            ',:',
            array_keys($data)
        );



        $sql =
        "INSERT INTO {$this->table}
        ($columns)
        VALUES
        ($values)";



        $stmt =
        $this->db->prepare($sql);



        return $stmt->execute($data);

    }





    /**
     * Update Record
     */
    public function update(
        int $id,
        array $data
    ): bool
    {

        $fields=[];


        foreach($data as $key=>$value)
        {

            $fields[] =
            "$key=:$key";

        }



        $sql =
        "UPDATE {$this->table}
        SET "
        .implode(',',$fields).
        " WHERE id=:id";



        $data['id']=$id;



        $stmt =
        $this->db->prepare($sql);



        return $stmt->execute($data);

    }





    /**
     * Delete Record
     */
    public function delete(
        int $id
    ): bool
    {


        $stmt =
        $this->db->prepare(
        "DELETE FROM {$this->table}
        WHERE id=:id"
        );


        return $stmt->execute([
            ':id'=>$id
        ]);

    }



}
