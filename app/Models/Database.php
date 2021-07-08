<?php


namespace App\Models;


use Aura\SqlQuery\QueryFactory;
use PDO;

class Database
{
    private $pdo;
    private $queryFactory;

    public function __construct(PDO $pdo, QueryFactory $queryFactory)
    {
        $this->pdo = $pdo;
        $this->queryFactory = $queryFactory;
    }

    public function all($table, $limit = null)
    {
        $select = $this->queryFactory->newSelect();
        $select
            ->cols(['*'])
            ->from($table)
            ->limit($limit);

        $statement = $this->pdo->prepare($select->getStatement());
        $statement->execute($select->getBindValues());

        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }

    public function find($table, $id)
    {
        $select = $this->queryFactory->newSelect();
        $select
            ->cols(['*'])
            ->from($table)
            ->where('id = :id')
            ->bindValue('id', $id);

        $statement = $this->pdo->prepare($select->getStatement());
        $statement->execute($select->getBindValues());
        return $statement->fetch(PDO::FETCH_ASSOC);
    }

    public function create($table, $data)
    {
        $insert = $this->queryFactory->newInsert();
        $insert
            ->into($table)
            ->cols($data);

        $statement = $this->pdo->prepare($insert->getStatement());
        $statement->execute($insert->getBindValues());

        $name = $insert->getLastInsertIdName('id');
        return $this->pdo->lastInsertId($name);
    }

    public function update($table, $id, $data)
    {
        $update = $this->queryFactory->newUpdate();
        $update
            ->table($table)
            ->cols($data)
            ->where('id = :id')
            ->bindValue('id', $id);

        $statement = $this->pdo->prepare($update->getStatement());
        $statement->execute($update->getBindValues());
    }

    public function delete($table, $id)
    {
        $delete = $this->queryFactory->newDelete();
        $delete
            ->from($table)
            ->where('id = :id')
            ->bindValue('id', $id);

        $statement = $this->pdo->prepare($delete->getStatement());
        $statement->execute($delete->getBindValues());
    }

    public function whereAll($table, $row, $id, $limit = 4)
    {
        $select = $this->queryFactory->newSelect();
        $select
            ->cols(['*'])
            ->from($table)
            ->where("$row = :id")
            ->bindValue(':id', $id)
            ->limit($limit);

        $statement = $this->pdo->prepare($select->getStatement());
        $statement->execute($select->getBindValues());

        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getCount($table, $row, $value)
    {
        $select = $this->queryFactory->newSelect();
        $select
            ->cols(['*'])
            ->from($table)
            ->where("$row = :$row")
            ->bindValue($row, $value);

        $statement = $this->pdo->prepare($select->getStatement());
        $statement->execute($select->getBindValues());
        return count($statement->fetchAll(PDO::FETCH_ASSOC));
    }

    public function getPaginatedFrom($table, $row, $id, $page = 1, $rows = 1)
    {
        $select = $this->queryFactory->newSelect();
        $select
            ->cols(['*'])
            ->from($table)
            ->where("$row = :row")
            ->bindValue('row', $id)
            ->page($page)
            ->setPaging($rows);

        $statement = $this->pdo->prepare($select->getStatement());
        $statement->execute($select->getBindValues());
        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }
}