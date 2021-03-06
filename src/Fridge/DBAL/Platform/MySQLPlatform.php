<?php

/*
 * This file is part of the Fridge DBAL package.
 *
 * (c) Eric GELOEN <geloen.eric@gmail.com>
 *
 * For the full copyright and license information, please read the LICENSE
 * file that was distributed with this source code.
 */

namespace Fridge\DBAL\Platform;

use Fridge\DBAL\Exception\PlatformException;
use Fridge\DBAL\Schema\Diff\ColumnDiff;
use Fridge\DBAL\Schema\Diff\SchemaDiff;
use Fridge\DBAL\Schema\ForeignKey;
use Fridge\DBAL\Schema\Index;
use Fridge\DBAL\Schema\PrimaryKey;
use Fridge\DBAL\Schema\Table;
use Fridge\DBAL\Type\Type;

/**
 * MySQL Platform.
 *
 * @author GeLo <geloen.eric@gmail.com>
 */
class MySQLPlatform extends AbstractPlatform
{
    /**
     * {@inheritdoc}
     */
    public function getBigIntegerSQLDeclaration(array $options = array())
    {
        return parent::getBigIntegerSQLDeclaration($options).$this->getIntegerSQLDeclarationSnippet($options);
    }

    /**
     * {@inheritdoc}
     */
    public function getBooleanSQLDeclaration(array $options = array())
    {
        return 'TINYINT(1)';
    }

    /**
     * {@inheritdoc}
     */
    public function getBlobSQLDeclaration(array $options = array())
    {
        $length = isset($options['length']) ? $options['length'] : null;

        return $this->getStringTypePrefix($length).parent::getBlobSQLDeclaration($options);
    }

    /**
     * {@inheritdoc}
     */
    public function getClobSQLDeclaration(array $options = array())
    {
        $length = isset($options['length']) ? $options['length'] : null;

        return $this->getStringTypePrefix($length).parent::getClobSQLDeclaration($options);
    }

    /**
     * {@inheritdoc}
     */
    public function getDateTimeSQLDeclaration(array $options = array())
    {
        if (isset($options['version']) && $options['version']) {
            return 'TIMESTAMP';
        }

        return parent::getDateTimeSQLDeclaration($options);
    }

    /**
     * {@inheritdoc}
     */
    public function getIntegerSQLDeclaration(array $options = array())
    {
        return parent::getIntegerSQLDeclaration($options).$this->getIntegerSQLDeclarationSnippet($options);
    }

    /**
     * {@inheritdoc}
     */
    public function getSmallIntegerSQLDeclaration(array $options = array())
    {
        return parent::getSmallIntegerSQLDeclaration($options).$this->getIntegerSQLDeclarationSnippet($options);
    }

    /**
     * {@inheritdoc}
     */
    public function supportSequences()
    {
        return false;
    }

    /**
     * {@inheritdoc}
     */
    public function supportChecks()
    {
        return false;
    }

    /**
     * {@inheritdoc}
     */
    public function getSetTransactionIsolationSQLQuery($isolation)
    {
        return 'SET SESSION TRANSACTION ISOLATION LEVEL '.$this->getTransactionIsolationSQLDeclaration($isolation);
    }

    /**
     * {@inheritdoc}
     */
    public function getSelectDatabaseSQLQuery()
    {
        return 'SELECT DATABASE()';
    }

    /**
     * {@inheritdoc}
     */
    public function getSelectDatabasesSQLQuery()
    {
        return 'SELECT schema_name AS '.$this->quoteIdentifier('database').
               ' FROM information_schema.schemata'.
               ' ORDER BY `database` ASC';
    }

    /**
     * {@inheritdoc}
     */
    public function getSelectViewsSQLQuery($database)
    {
        return 'SELECT'.
               '  table_name AS name,'.
               '  view_definition AS `sql`'.
               ' FROM information_schema.views'.
               ' WHERE table_schema = '.$this->quote($database).
               ' ORDER BY name ASC';
    }

    /**
     * {@inheritdoc}
     */
    public function getSelectTableNamesSQLQuery($database)
    {
        return 'SELECT'.
               '  table_name AS name'.
               ' FROM information_schema.tables'.
               ' WHERE table_schema = '.$this->quote($database).
               ' AND table_type = '.$this->quote('BASE TABLE').
               ' ORDER BY name ASC';
    }

    /**
     * {@inheritdoc}
     */
    public function getSelectColumnsSQLQuery($table, $database)
    {
        return 'SELECT'.
               '  column_name AS name,'.
               '  column_type AS type,'.
               '  IF (column_type REGEXP '.$this->quote('.*unsigned.*').', true, NULL) AS '.
               $this->quoteIdentifier('unsigned').','.
               '  IF (is_nullable = '.$this->quote('NO').', TRUE, FALSE) AS not_null,'.
               '  column_default AS '.$this->quoteIdentifier('default').','.
               '  IF (extra = '.$this->quote('auto_increment').', TRUE, NULL) AS auto_increment,'.
               '  column_comment AS comment'.
               ' FROM information_schema.columns'.
               ' WHERE table_schema = '.$this->quote($database).
               ' AND table_name = '.$this->quote($table).
               ' ORDER BY ordinal_position ASC';
    }

    /**
     * {@inheritdoc}
     */
    public function getSelectPrimaryKeySQLQuery($table, $database)
    {
        return 'SELECT'.
               '  c.constraint_name AS name,'.
               '  k.column_name'.
               ' FROM information_schema.table_constraints c'.
               ' INNER JOIN information_schema.key_column_usage k'.
               ' ON'.
               ' ('.
               '  c.table_name = k.table_name'.
               '  AND c.table_schema = k.table_schema'.
               '  AND c.constraint_name = k.constraint_name'.
               ' )'.
               ' WHERE c.constraint_type = '.$this->quote('PRIMARY KEY').
               ' AND c.table_schema = '.$this->quote($database).
               ' AND c.table_name = '.$this->quote($table).
               ' ORDER BY k.ordinal_position ASC';
    }

    /**
     * {@inheritdoc}
     */
    public function getSelectForeignKeysSQLQuery($table, $database)
    {
        return 'SELECT'.
               '  c.constraint_name AS name,'.
               '  k.column_name AS local_column_name,'.
               '  k.referenced_table_name AS foreign_table_name,'.
               '  k.referenced_column_name AS foreign_column_name,'.
               '  rc.delete_rule AS on_delete,'.
               '  rc.update_rule AS on_update'.
               ' FROM information_schema.table_constraints c'.
               ' INNER JOIN information_schema.key_column_usage k'.
               ' ON'.
               ' ('.
               '  c.table_name = k.table_name'.
               '  AND c.table_schema = k.table_schema'.
               '  AND c.constraint_name = k.constraint_name'.
               ' )'.
               ' INNER JOIN information_schema.referential_constraints rc'.
               ' ON'.
               ' ('.
               '  rc.table_name = c.table_name'.
               '  AND rc.constraint_name = c.constraint_name'.
               ' )'.
               ' WHERE c.constraint_type = '.$this->quote('FOREIGN KEY').
               ' AND c.table_schema = '.$this->quote($database).
               ' AND c.table_name = '.$this->quote($table).
               ' ORDER BY c.constraint_name ASC, k.ordinal_position ASC';
    }

    /**
     * {@inheritdoc}
     */
    public function getSelectIndexesSQLQuery($table, $database)
    {
        return 'SELECT'.
               '  s.index_name AS name,'.
               '  s.column_name,'.
               '  !s.non_unique AS '.$this->quoteIdentifier('unique').
               ' FROM information_schema.statistics s'.
               ' WHERE s.table_schema = '.$this->quote($database).
               ' AND s.table_name = '.$this->quote($table).
               ' ORDER BY s.index_name ASC, s.seq_in_index ASC';
    }

    /**
     * {@inheritdoc}
     */
    public function getCreateTableSQLQueries(Table $table, array $flags = array())
    {
        $queries = parent::getCreateTableSQLQueries($table, $flags);

        $queries[0] .= ' ENGINE = InnoDB';

        return $queries;
    }

    /**
     * {@inheritdoc}
     */
    public function getRenameDatabaseSQLQueries(SchemaDiff $schemaDiff)
    {
        $queries = $this->getCreateDatabaseSQLQueries($schemaDiff->getNewAsset()->getName());

        foreach ($schemaDiff->getNewAsset()->getTables() as $table) {
            $queries[] = 'RENAME TABLE '.$schemaDiff->getOldAsset()->getName().'.'.$table->getName().
                         ' TO '.$schemaDiff->getNewAsset()->getName().'.'.$table->getName();
        }

        return array_merge($queries, $this->getDropDatabaseSQLQueries($schemaDiff->getOldAsset()->getName()));
    }

    /**
     * {@inheritdoc}
     */
    public function getAlterColumnSQLQueries(ColumnDiff $columnDiff, $table)
    {
        return array(
            $this->getAlterTableSQLQuery(
                $table,
                'CHANGE COLUMN',
                $columnDiff->getOldAsset()->getName().' '.$this->getColumnSQLDeclaration($columnDiff->getNewAsset())
            ),
        );
    }

    /**
     * {@inheritdoc}
     */
    public function getDropPrimaryKeySQLQueries(PrimaryKey $primaryKey, $table)
    {
        return array($this->getAlterTableSQLQuery($table, 'DROP PRIMARY KEY'));
    }

    /**
     * {@inheritdoc}
     */
    public function getDropForeignKeySQLQueries(ForeignKey $foreignKey, $table)
    {
        return array($this->getAlterTableSQLQuery($table, 'DROP FOREIGN KEY', $foreignKey->getName()));
    }

    /**
     * {@inheritdoc}
     */
    public function getDropIndexSQLQueries(Index $index, $table)
    {
        return array($this->getAlterTableSQLQuery($table, 'DROP INDEX', $index->getName()));
    }

    /**
     * {@inheritdoc}
     */
    public function getQuoteIdentifier()
    {
        return '`';
    }

    /**
     * {@inheritdoc}
     */
    protected function initializeMappedTypes()
    {
        return array(
            'bigint'     => Type::BIGINTEGER,
            'blob'       => Type::BLOB,
            'char'       => Type::STRING,
            'date'       => Type::DATE,
            'datetime'   => Type::DATETIME,
            'decimal'    => Type::DECIMAL,
            'double'     => Type::FLOAT,
            'float'      => Type::FLOAT,
            'int'        => Type::INTEGER,
            'integer'    => Type::INTEGER,
            'longblob'   => Type::BLOB,
            'longtext'   => Type::TEXT,
            'mediumblob' => Type::BLOB,
            'mediumint'  => Type::INTEGER,
            'mediumtext' => Type::TEXT,
            'numeric'    => Type::DECIMAL,
            'real'       => Type::FLOAT,
            'smallint'   => Type::SMALLINTEGER,
            'string'     => Type::STRING,
            'text'       => Type::TEXT,
            'time'       => Type::TIME,
            'timestamp'  => Type::DATETIME,
            'tinyblob'   => Type::BLOB,
            'tinyint'    => Type::BOOLEAN,
            'tinytext'   => Type::TEXT,
            'varchar'    => Type::STRING,
            'year'       => Type::DATE,
        );
    }

    /**
     * {@inheritdoc}
     */
    protected function getPrimaryKeySQLDeclaration(PrimaryKey $primaryKey)
    {
        return 'CONSTRAINT PRIMARY KEY ('.implode(', ', $primaryKey->getColumnNames()).')';
    }

    /**
     * Gets the integer SQL declaration snippet.
     *
     * @param array $options The integer options.
     *
     * @return string The integer SQL declaration snippet.
     */
    private function getIntegerSQLDeclarationSnippet(array $options = array())
    {
        $length = isset($options['length']) ? (int) $options['length'] : null;
        $unsigned = isset($options['unsigned']) && $options['unsigned'] ? ' UNSIGNED' : null;
        $autoIncrement = isset($options['auto_increment']) && $options['auto_increment'] ? ' AUTO_INCREMENT' : null;

        $sql = $unsigned.$autoIncrement;

        if ($length !== null) {
            $sql = '('.$length.')'.$sql;
        }

        return $sql;
    }

    /**
     * Gets the string type prefix for the given length.
     *
     * @link http://dev.mysql.com/doc/refman/5.5/en/string-type-overview.html String types length.
     *
     * @param null|integer $length The length of the type.
     *
     * @throws \Fridge\DBAL\Exception\PlatformException If the length is not a strict positive integer.
     *
     * @return string The prefix.
     */
    private function getStringTypePrefix($length = null)
    {
        if ($length === null) {
            return 'LONG';
        }

        if (!is_int($length) || ($length <= 0)) {
            throw PlatformException::invalidStringTypePrefixLength();
        }

        $prefixLimits = array(
            'TINY'   => 255,
            ''       => 65535,
            'MEDIUM' => 16777215,
        );

        $stringTypePrefix = 'LONG';
        foreach ($prefixLimits as $prefix => $limit) {
            if ($length <= $limit) {
                $stringTypePrefix = $prefix;

                break;
            }
        }

        return $stringTypePrefix;
    }
}
