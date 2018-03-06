<?php

namespace App\EventListener;

use Doctrine\ORM\Tools\Event\GenerateSchemaEventArgs;

/**
 * IgnoreTablesListener class
 */
class IgnoreTablesListener
{
    private $ignoredEntities = [
        'App:VgaBiosIndex',
    ];

    /**
     * Remove ignored tables / entities from Schema
     *
     * @param GenerateSchemaEventArgs $args
     */
    public function postGenerateSchema(GenerateSchemaEventArgs $args): void
    {
        $schema = $args->getSchema();
        $em = $args->getEntityManager();

        $ignoredTables = [];
        foreach ($this->ignoredEntities as $entityName) {
            $ignoredTables[] = $em->getClassMetadata($entityName)->getTableName();
        }

        foreach ($schema->getTables() as $table) {
            if (\in_array($table->getName(), $ignoredTables, true)) {
                $schema->dropTable($table->getName());
            }
        }
    }

}
