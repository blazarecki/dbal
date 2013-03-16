<?php

/*
 * This file is part of the Fridge DBAL package.
 *
 * (c) Eric GELOEN <geloen.eric@gmail.com>
 *
 * For the full copyright and license information, please read the LICENSE
 * file that was distributed with this source code.
 */

namespace Fridge\DBAL\Adapter\Mysqli;

use \ArrayIterator,
    \IteratorAggregate,
    \PDO;

use Fridge\DBAL\Adapter\StatementInterface,
    Fridge\DBAL\Adapter\StatementRewriter,
    Fridge\DBAL\Exception\Adapter\MysqliException;

/**
 * {@inheritdoc}
 *
 * @author GeLo <geloen.eric@gmail.com>
 */
class MysqliStatement implements StatementInterface, IteratorAggregate
{
    /** @var array */
    static protected $mappedTypes = array(
        PDO::PARAM_NULL => 's',
        PDO::PARAM_INT  => 'i',
        PDO::PARAM_STR  => 's',
        PDO::PARAM_LOB  => 'b',
        PDO::PARAM_BOOL => 'i',
    );

    /** @var \Fridge\DBAL\Adapter\Mysqli\MysqliConnection */
    protected $connection;

    /** @var \mysqli_stmt */
    protected $mysqliStatement;

    /** @var \Fridge\DBAL\Adapter\Mysqli\StatementRewriter */
    protected $statementRewriter;

    /** @var integer */
    protected $defaultFetchMode;

    /** @var array */
    protected $bindedParameters;

    /** @var array */
    protected $bindedTypes;

    /** @var array */
    protected $bindedValues;

    /** @var array */
    protected $resultFields;

    /** @var array */
    protected $result;

    /**
     * Mysqli statement constructor.
     *
     * @param string                                       $statement  The SQL statement.
     * @param \Fridge\DBAL\Adapter\Mysqli\MysqliConnection $connection The mysqli connection.
     *
     * @throws \Fridge\DBAL\Exception\Adapter\MysqliException If the statement can not be prepared.
     */
    public function __construct($statement, MysqliConnection $connection)
    {
        $this->statementRewriter = new StatementRewriter($statement);

        $this->connection = $connection;
        $this->mysqliStatement = $connection->getMysqli()->prepare($this->statementRewriter->getRewritedStatement());

        if ($this->mysqliStatement === false) {
            throw new MysqliException($connection->getMysqli()->error, $connection->getMysqli()->errno);
        }

        $this->defaultFetchMode = PDO::FETCH_BOTH;

        $this->bindedParameters = array();
        $this->bindedTypes = array();
        $this->bindedValues = array();
    }

    /**
     * Gets the mysqli low-level statement.
     *
     * @return \mysqli_stmt The mysqli low-level statement.
     */
    public function getMysqliStatement()
    {
        return $this->mysqliStatement;
    }

    /**
     * {@inheritdoc}
     */
    public function getIterator()
    {
        $datas = $this->fetchAll();

        return new ArrayIterator($datas);
    }

    /**
     * {@inheritdoc}
     */
    public function bindParam($parameter, &$variable, $type = PDO::PARAM_STR)
    {
        $mappedType = self::getMappedType($type);

        if (is_string($parameter) && ($parameter[0] !== ':')) {
            $parameter = ':'.$parameter;
        }

        $parameters = $this->statementRewriter->getRewritedParameters($parameter);

        foreach ($parameters as $parameter) {
            $this->bindedParameters[$parameter] = &$variable;
            $this->bindedTypes[$parameter - 1] = $mappedType;
        }

        return true;
    }

    /**
     * {@inheritdoc}
     *
     * To bind a value (by copy), the wrapper will copy the value in the bindedValues property and then, bind the
     * copied value as parameter (by reference).
     */
    public function bindValue($parameter, $value, $type = PDO::PARAM_STR)
    {
        $this->bindedValues[$parameter] = $value;

        return $this->bindParam($parameter, $this->bindedValues[$parameter], $type);
    }

    /**
     * {@inheritdoc}
     *
     * To retrieve the field name fetched, the wrapper will bind the result fields on the resultFields property and
     * then, bind the result on the result property according to the binded result fields.
     *
     * @throws \Fridge\DBAL\Exception\Adapter\MysqliException If the statement can not be executed.
     */
    public function execute($parameters = array())
    {
        if (!empty($parameters)) {
            $this->bindValues($parameters);
        }

        if (!empty($this->bindedParameters)) {
            $this->bindParameters();
        }

        if ($this->mysqliStatement->execute() === false) {
            throw new MysqliException($this->mysqliStatement->error, $this->mysqliStatement->errno);
        }

        $this->mysqliStatement->store_result();

        if (empty($this->resultFields)) {
            $this->bindResultFields();
        }

        if (!empty($this->resultFields)) {
            $this->bindResult();
        }

        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function rowCount()
    {
        if (!empty($this->resultFields)) {
            return $this->mysqliStatement->num_rows;
        }

        return $this->mysqliStatement->affected_rows;
    }

    /**
     * {@inheritdoc}
     */
    public function fetchAll($fetchMode = PDO::FETCH_BOTH)
    {
        $results = array();

        while (($result = $this->fetch($fetchMode)) !== null) {
            $results[] = $result;
        }

        return $results;
    }

    /**
     * {@inheritdoc}
     *
     * @throws \Fridge\DBAL\Exception\Adapter\MysqliException If the statement can not be fetched or if the fetch mode
     *                                                        is not supported.
     */
    public function fetch($fetchMode = PDO::FETCH_BOTH)
    {
        $fetchResult = $this->mysqliStatement->fetch();

        if ($fetchResult === false) {
            throw new MysqliException($this->mysqliStatement->error, $this->mysqliStatement->errno);
        }

        if ($fetchResult === null) {
            return null;
        }

        $values = array();
        foreach ($this->result as $value) {
            $values[] = $value;
        }

        if ($fetchMode === null) {
            $fetchMode = $this->defaultFetchMode;
        }

        switch ($fetchMode) {
            case PDO::FETCH_NUM:
                return $values;
                break;

            case PDO::FETCH_ASSOC:
                return array_combine($this->resultFields, $values);
                break;

            case PDO::FETCH_BOTH:
                $result = array_combine($this->resultFields, $values);
                $result += $values;

                return $result;
                break;

            default:
                throw MysqliException::fetchModeNotSupported($fetchMode);
                break;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function fetchColumn($columnIndex = 0)
    {
        $result = $this->fetch(PDO::FETCH_NUM);

        if ($result === null) {
            return false;
        }

        return $result[$columnIndex];
    }

    /**
     * {@inheritdoc}
     */
    public function setFetchMode($fetchMode)
    {
        $this->defaultFetchMode = $fetchMode;

        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function columnCount()
    {
        return $this->mysqliStatement->field_count;
    }

    /**
     * {@inheritdoc}
     */
    public function closeCursor()
    {
        $this->mysqliStatement->free_result();

        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function errorCode()
    {
        return $this->mysqliStatement->errno;
    }

    /**
     * {@inheritdoc}
     */
    public function errorInfo()
    {
        return array($this->mysqliStatement->errno, $this->mysqliStatement->errno, $this->mysqliStatement->error);
    }

    /**
     * Gets the mapped type.
     *
     * @param integer $type The type (PDO::PARAM_*).
     *
     * @throws \Fridge\DBAL\Exception\Adapter\MysqliException If the mapped type does not exist.
     *
     * @return string The mapped type.
     */
    static protected function getMappedType($type)
    {
        if (!isset(self::$mappedTypes[$type])) {
            throw MysqliException::mappedTypeDoesNotExist($type);
        }

        return self::$mappedTypes[$type];
    }

    /**
     * Binds values on the statement.
     *
     * @param array $values Associative array describing parameter => value pairs.
     */
    protected function bindValues(array $values)
    {
        $this->bindedParameters = array();
        $this->bindedTypes = array();
        $this->bindedValues = array();

        foreach ($values as $parameter => $value) {
            if (is_int($parameter)) {
                $parameter++;
            }

            $this->bindValue($parameter, $value);
        }
    }

    /**
     * Binds the parameters on the low-level statement.
     */
    protected function bindParameters()
    {
        $bindedParameterReferences = array(implode('', $this->bindedTypes));
        $lobParameters = array();
        $null = null;

        foreach ($this->bindedParameters as $key => &$parameter) {
            if (isset($this->bindedTypes[$key - 1]) && ($this->bindedTypes[$key - 1] === self::$mappedTypes[PDO::PARAM_LOB])) {
                $lobParameters[$key - 1] = $parameter;
                $bindedParameterReferences[$key] = &$null;
            } else {
                $bindedParameterReferences[$key] = &$parameter;
            }
        }

        call_user_func_array(array($this->mysqliStatement, 'bind_param'), $bindedParameterReferences);

        foreach ($lobParameters as $key => $lobParameter) {
            rewind($lobParameter);

            while (!feof($lobParameter)) {
                $this->mysqliStatement->send_long_data(
                    $key,
                    fread($lobParameter, $this->connection->getMaxAllowedPacket())
                );
            }
        }
    }

    /**
     * Binds the low-level result fields.
     */
    protected function bindResultFields()
    {
        $resultMetadata = $this->mysqliStatement->result_metadata();

        if ($resultMetadata !== false) {
            $this->resultFields = array();

            foreach ($resultMetadata->fetch_fields() as $field) {
                $this->resultFields[] = $field->name;
            }

            $resultMetadata->free();
        }
    }

    /**
     * Binds the low-level statement result.
     */
    protected function bindResult()
    {
        $this->result = array_fill(0, count($this->resultFields), null);

        $resultReferences = array();
        foreach ($this->result as $key => &$result) {
            $resultReferences[$key] = &$result;
        }

        call_user_func_array(array($this->mysqliStatement, 'bind_result'), $resultReferences);

        $this->result = $resultReferences;
    }
}
