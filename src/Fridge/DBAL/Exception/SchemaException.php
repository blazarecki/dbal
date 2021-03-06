<?php

/*
 * This file is part of the Fridge DBAL package.
 *
 * (c) Eric GELOEN <geloen.eric@gmail.com>
 *
 * For the full copyright and license information, please read the LICENSE
 * file that was distributed with this source code.
 */

namespace Fridge\DBAL\Exception;

/**
 * Schema exception.
 *
 * @author GeLo <geloen.eric@gmail.com>
 */
class SchemaException extends Exception
{
    /**
     * Gets the "INVALID ASSET NAME" exception.
     *
     * @param string $asset The asset concerned by the exception.
     *
     * @return \Fridge\DBAL\Exception\SchemaException The "INVALID ASSET NAME" exception.
     */
    public static function invalidAssetName($asset)
    {
        return new self(sprintf('The %s name must be a string.', $asset));
    }

    /**
     * Gets the "INVALID COLUMN AUTO INCREMENT FLAG" exception.
     *
     * @param string $column The column concerned by the exception.
     *
     * @return \Fridge\DBAL\Exception\SchemaException The "INVALID COLUMN AUTO INCREMENT FLAG" exception.
     */
    public static function invalidColumnAutoIncrementFlag($column)
    {
        return new self(sprintf('The auto increment flag of the column "%s" must be a boolean.', $column));
    }

    /**
     * Gets the "INVALID COLUMN COMMENT" exception.
     *
     * @param string $column The column concerned by the exception.
     *
     * @return \Fridge\DBAL\Exception\SchemaException The "INVALID COLUMN COMMENT" exception.
     */
    public static function invalidColumnComment($column)
    {
        return new self(sprintf('The comment of the column "%s" must be a string.', $column));
    }

    /**
     * Gets the "INVALID COLUMN FIXED FLAG" exception.
     *
     * @param string $column The column concerned by the exception.
     *
     * @return \Fridge\DBAL\Exception\SchemaException The "INVALID COLUMN FIXED FLAG" exception.
     */
    public static function invalidColumnFixedFlag($column)
    {
        return new self(sprintf('The fixed flag of the column "%s" must be a boolean.', $column));
    }

    /**
     * Gets the "INVALID COLUMN LENGTH" exception.
     *
     * @param string $column The column concerned by the exception.
     *
     * @return \Fridge\DBAL\Exception\SchemaException The "INVALID COLUMN LENGTH" exception.
     */
    public static function invalidColumnLength($column)
    {
        return new self(sprintf('The length of the column "%s" must be a positive integer.', $column));
    }

    /**
     * Gets the "INVALID COLUMN NOT NULL FLAG" exception.
     *
     * @param string $column The column concerned by the exception.
     *
     * @return \Fridge\DBAL\Exception\SchemaException The "INVALID COLUMN NOT NULL FLAG" exception.
     */
    public static function invalidColumnNotNullFlag($column)
    {
        return new self(sprintf('The not null flag of the column "%s" must be a boolean.', $column));
    }

    /**
     * Gets the "INVALID COLUMN PROPERTY" exception.
     *
     * @param string $column   The column concerned by the exception.
     * @param string $property The column property.
     *
     * @return \Fridge\DBAL\Exception\SchemaException The "INVALID COLUMN PROPERTY" exception.
     */
    public static function invalidColumnProperty($column, $property)
    {
        return new self(sprintf('The property "%s" of the column "%s" does not exist.', $property, $column));
    }

    /**
     * Gets the "INVALID COLUMN PRECISION" exception.
     *
     * @param string $column The column concerned by the exception.
     *
     * @return \Fridge\DBAL\Exception\SchemaException The "INVALID COLUMN PRECISION" exception.
     */
    public static function invalidColumnPrecision($column)
    {
        return new self(sprintf('The precision of the column "%s" must be a positive integer.', $column));
    }

    /**
     * Gets the "INVALID COLUMN SCLAE" exception.
     *
     * @param string $column The column concerned by the exception.
     *
     * @return \Fridge\DBAL\Exception\SchemaException The "INVALID COLUMN SCALE" exception.
     */
    public static function invalidColumnScale($column)
    {
        return new self(sprintf('The scale of the column "%s" must be a positive integer.', $column));
    }

    /**
     * Gets the "INVALID COLUMN UNSIGNED FLAG" exception.
     *
     * @param string $column The column concerned by the exception.
     *
     * @return \Fridge\DBAL\Exception\SchemaException The "INVALID COLUMN UNSIGNED FLAG" exception.
     */
    public static function invalidColumnUnsignedFlag($column)
    {
        return new self(sprintf('The unsigned flag of the column "%s" must be a boolean.', $column));
    }

    /**
     * Gets the "INVALID FOREIGN KEY FOREIGN COLUMN NAME" exception.
     *
     * @param string $foreignKey The foreign key concerned by the exception.
     *
     * @return \Fridge\DBAL\Exception\SchemaException The "INVALID FOREIGN KEY FOREIGN COLUMN NAME" exception.
     */
    public static function invalidForeignKeyForeignColumnName($foreignKey)
    {
        return new self(sprintf('The foreign column name of the foreign key "%s" must be a string.', $foreignKey));
    }

    /**
     * Gets the "INVALID FOREIGN KEY FOREIGN TABLE NAME" exception.
     *
     * @param string $foreignKey The foreign key concerned by the exception.
     *
     * @return \Fridge\DBAL\Exception\SchemaException The "INVALID FOREIGN KEY FOREIGN TABLE NAME" exception.
     */
    public static function invalidForeignKeyForeignTableName($foreignKey)
    {
        return new self(sprintf('The foreign table name of the foreign key "%s" must be a string.', $foreignKey));
    }

    /**
     * Gets the "INVALID FOREIGN KEY LOCAL COLUMN NAME" exception.
     *
     * @param string $foreignKey The foreign key concerned by the exception.
     *
     * @return \Fridge\DBAL\Exception\SchemaException The "INVALID FOREIGN KEY LOCAL COLUMN NAME" exception.
     */
    public static function invalidForeignKeyLocalColumnName($foreignKey)
    {
        return new self(sprintf('The local column name of the foreign key "%s" must be a string.', $foreignKey));
    }

    /**
     * Gets the "INVALID INDEX COLUMN NAME" exception.
     *
     * @param string $index The index concerned by the exception.
     *
     * @return \Fridge\DBAL\Exception\SchemaException The "INVALID INDEX COLUMN NAME" exception.
     */
    public static function invalidIndexColumnName($index)
    {
        return new self(sprintf('The column name of the index "%s" must be a string.', $index));
    }

    /**
     * Gets the "INVALID INDEX UNIQUE FLAG" exception.
     *
     * @param string $index The index concerned by the exception.
     *
     * @return \Fridge\DBAL\Exception\SchemaException The "INVALID INDEX UNIQUE FLAG" exception.
     */
    public static function invalidIndexUniqueFlag($index)
    {
        return new self(sprintf('The unique flag of the index "%s" must be a boolean.', $index));
    }

    /**
     * Gets the "INVALID PRIMARY KEY COLUMN NAME" exception.
     *
     * @param string $primaryKey The primary key concerned by the exception.
     *
     * @return \Fridge\DBAL\Exception\SchemaException The "INVALID PRIMARY KEY COLUMN NAME" exception.
     */
    public static function invalidPrimaryKeyColumnName($primaryKey)
    {
        return new self(sprintf('The column name of the primary key "%s" must be a string.', $primaryKey));
    }

    /**
     * Gets the "INVALID SEQUENCE INITIAL VALUE" exception.
     *
     * @param string $sequence The sequence concerned by the exception.
     *
     * @return \Fridge\DBAL\Exception\SchemaException The "INVALID SEQUENCE INITIAL VALUE" exception.
     */
    public static function invalidSequenceInitialValue($sequence)
    {
        return new self(sprintf('The initial value of the sequence "%s" must be a positive integer.', $sequence));
    }

    /**
     * Gets the "INVALID SEQUENCE INCREMENT SIZE" exception.
     *
     * @param string $sequence The sequence concerned by the exception.
     *
     * @return \Fridge\DBAL\Exception\SchemaException The "INVALID SEQUENCE INCREMENT SIZE" exception.
     */
    public static function invalidSequenceIncrementSize($sequence)
    {
        return new self(sprintf('The increment size of the sequence "%s" must be a positive integer.', $sequence));
    }

    /**
     * Gets the "INVALID VIEW SQL" exception.
     *
     * @param string $view The view concerned by the exception.
     *
     * @return \Fridge\DBAL\Exception\SchemaException The "INVALID VIEW SQL" exception.
     */
    public static function invalidViewSQL($view)
    {
        return new self(sprintf('The SQL query of the view "%s" must be a string.', $view));
    }

    /**
     * Gets the "TABLE COLUMN ALREADY EXISTS" exception.
     *
     * @param string $table  The table name.
     * @param string $column The column name.
     *
     * @return \Fridge\DBAL\Exception\SchemaException The "TABLE COLUMN ALREADY EXISTS" exception.
     */
    public static function tableColumnAlreadyExists($table, $column)
    {
        return new self(sprintf('The column "%s" of the table "%s" already exists.', $column, $table));
    }

    /**
     * Gets the "TABLE COLUMN DOES NOT EXIST" exception.
     *
     * @param string $table  The table name.
     * @param string $column The column name.
     *
     * @return \Fridge\DBAL\Exception\SchemaException The "TABLE COLUMN DOES NOT EXIST" exception.
     */
    public static function tableColumnDoesNotExist($table, $column)
    {
        return new self(sprintf('The column "%s" of the table "%s" does not exist.', $column, $table));
    }

    /**
     * Gets the "TABLE PRIMARY KEY ALREADY EXISTS" exception.
     *
     * @param string $table The table name.
     *
     * @return \Fridge\DBAL\Exception\SchemaException The "TABLE PRIMARY KEY ALREADY EXISTS" exception.
     */
    public static function tablePrimaryKeyAlreadyExists($table)
    {
        return new self(sprintf('The table "%s" has already a primary key.', $table));
    }

    /**
     * Gets the "TABLE PRIMARY KEY DOES NOT EXIST" exception.
     *
     * @param string $table The table name.
     *
     * @return \Fridge\DBAL\Exception\SchemaException The "TABLE PRIMARY KEY DOES NOT EXIST" exception.
     */
    public static function tablePrimaryKeyDoesNotExist($table)
    {
        return new self(sprintf('The table "%s" has no primary key.', $table));
    }

    /**
     * Gets the "TABLE FOREIGN KEY ALREADY EXISTS" exception.
     *
     * @param string $table      The table name.
     * @param string $foreignKey The foreign key name.
     *
     * @return \Fridge\DBAL\Exception\SchemaException The "TABLE FOREIGN KEY ALREADY EXISTS" exception.
     */
    public static function tableForeignKeyAlreadyExists($table, $foreignKey)
    {
        return new self(sprintf('The foreign key "%s" of the table "%s" already exists.', $foreignKey, $table));
    }

    /**
     * Gets the "TABLE FOREIGN KEY DOES NOT EXIST" exception.
     *
     * @param string $table      The table name.
     * @param string $foreignKey The foreign key name.
     *
     * @return \Fridge\DBAL\Exception\SchemaException The "TABLE FOREIGN KEY DOES NOT EXIST" exception.
     */
    public static function tableForeignKeyDoesNotExist($table, $foreignKey)
    {
        return new self(sprintf('The foreign key "%s" of the table "%s" does not exist.', $foreignKey, $table));
    }

    /**
     * Gets the "TABLE INDEX ALREADY EXISTS" exception.
     *
     * @param string $table The table name.
     * @param string $index The index name.
     *
     * @return \Fridge\DBAL\Exception\SchemaException The "TABLE INDEX ALREADY EXISTS" exception.
     */
    public static function tableIndexAlreadyExists($table, $index)
    {
        return new self(sprintf('The index "%s" of the table "%s" already exists.', $index, $table));
    }

    /**
     * Gets the "TABLE INDEX DOES NOT EXIST" exception.
     *
     * @param string $table The table name.
     * @param string $index The index name.
     *
     * @return \Fridge\DBAL\Exception\SchemaException The "TABLE INDEX DOES NOT EXIST" exception.
     */
    public static function tableIndexDoesNotExist($table, $index)
    {
        return new self(sprintf('The index "%s" of the table "%s" does not exist.', $index, $table));
    }

    /**
     * Gets the "SCHEMA TABLE ALREADY EXISTS" exception.
     *
     * @param string $schema The schema name.
     * @param string $table  The table name.
     *
     * @return \Fridge\DBAL\Exception\SchemaException The "SCHEMA TABLE ALREADY EXISTS" exception.
     */
    public static function schemaTableAlreadyExists($schema, $table)
    {
        return new self(sprintf('The table "%s" of the schema "%s" already exists.', $table, $schema));
    }

    /**
     * Gets the "SCHEMA TABLE DOES NOT EXIST" exception.
     *
     * @param string $schema The schema name.
     * @param string $table  The table name.
     *
     * @return \Fridge\DBAL\Exception\SchemaException The "SCHEMA TABLE DOES NOT EXIST" exception.
     */
    public static function schemaTableDoesNotExist($schema, $table)
    {
        return new self(sprintf('The table "%s" of the schema "%s" does not exist.', $table, $schema));
    }

    /**
     * Gets the "SCHEMA SEQUENCE ALREADY EXISTS" exception.
     *
     * @param string $schema   The schema name.
     * @param string $sequence The sequence name.
     *
     * @return \Fridge\DBAL\Exception\SchemaException The "SCHEMA SEQUENCE ALREADY EXISTS" exception.
     */
    public static function schemaSequenceAlreadyExists($schema, $sequence)
    {
        return new self(sprintf('The sequence "%s" of the schema "%s" already exists.', $sequence, $schema));
    }

    /**
     * Gets the "SCHEMA SEQUENCE DOES NOT EXIST" exception.
     *
     * @param string $schema   The schema name.
     * @param string $sequence The sequence name.
     *
     * @return \Fridge\DBAL\Exception\SchemaException The "SCHEMA SEQUENCE DOES NOT EXIST" exception.
     */
    public static function schemaSequenceDoesNotExist($schema, $sequence)
    {
        return new self(sprintf('The sequence "%s" of the schema "%s" does not exist.', $sequence, $schema));
    }

    /**
     * Gets the "SCHEMA VIEW ALREADY EXISTS" exception.
     *
     * @param string $schema The schema name.
     * @param string $view   The view name.
     *
     * @return \Fridge\DBAL\Exception\SchemaException The "SCHEMA VIEW ALREADY EXISTS" exception.
     */
    public static function schemaViewAlreadyExists($schema, $view)
    {
        return new self(sprintf('The view "%s" of the schema "%s" already exists.', $view, $schema));
    }

    /**
     * Gets the "SCHEMA VIEW DOES NOT EXIST" exception.
     *
     * @param string $schema The schema name.
     * @param string $view   The view name.
     *
     * @return \Fridge\DBAL\Exception\SchemaException The "SCHEMA VIEW DOES NOT EXIST" exception.
     */
    public static function schemaViewDoesNotExist($schema, $view)
    {
        return new self(sprintf('The view "%s" of the schema "%s" does not exist.', $view, $schema));
    }

    /**
     * Gets the "TABLE CHECK DOES NOT EXIST" exception.
     *
     * @param string $table The table name.
     * @param string $check The check name.
     *
     * @return \Fridge\DBAL\Exception\SchemaException The "TABLE CHECK DOES NOT EXIST" exception.
     */
    public static function tableCheckDoesNotExist($table, $check)
    {
        return new self(sprintf('The check "%s" of the table "%s" does not exist.', $check, $table));
    }

    /**
     * Gets the "TABLE CHECK ALREADY EXISTS" exception.
     *
     * @param string $table The table name.
     * @param string $check The check name.
     *
     * @return \Fridge\DBAL\Exception\SchemaException The "TABLE CHECK ALREADY EXISTS" exception.
     */
    public static function tableCheckAlreadyExists($table, $check)
    {
        return new self(sprintf('The check "%s" of the table "%s" already exists.', $check, $table));
    }
}
