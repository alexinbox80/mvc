<?php
// Разработать и реализовать на PHP собственную ORM (Object-Relational Mapping — прослойку между базой
// данных и кодом) посредством абстрактной фабрики. Фабрики будут реализовывать интерфейсы СУБД MySQLFactory,
// PostgreSQLFactory, OracleFactory. Каждая фабрика возвращает объекты, характерные для конкретной СУБД.
// Пример компонентов:
//
// DBConnection — соединение с базой,
// DBRecrord — запись таблицы базы данных,
// DBQueryBuiler — конструктор запросов к базе. ### Должна получиться гибкая система, позволяющая динамически
// менять базу данных и инкапсулирующая взаимодействие с ней внутри продуктов конкретных фабрик. Углубляться в
// детали компонента не обязательно — достаточно их наличия.

/**
 * Интерфейс Абстрактной Фабрики объявляет набор методов, которые возвращают
 * различные абстрактные объекты. Эти объекты называются семейством и связаны
 * БД или концепцией высокого уровня. Объекты одного семейства обычно могут
 * взаимодействовать между собой. Семейство объектов может иметь несколько
 * вариаций, но объекты одной вариации несовместимы с объектами другой.
 */
abstract class AbstractFactory
{
    abstract public function DBConnection(): Connection;

    abstract public function DBRecrord(): Record;

    abstract public function DBQueryBuiler(): QueryBuiler;
}

/**
 * Конкретная Фабрика производит семейство объектов одной вариации. Фабрика
 * гарантирует совместимость полученных объектов. Обратите внимание, что
 * сигнатуры методов Конкретной Фабрики возвращают абстрактный объект, в то
 * время как внутри метода создается экземпляр конкретного объекта.
 */
class MySQLFactory extends AbstractFactory
{
    public function DBConnection(): Connection
    {
        return new MySQLDBConnection();
    }

    public function DBRecrord(): Record
    {
        return new MySQLDBRecord();
    }

    public function DBQueryBuiler(): QueryBuiler
    {
        return new MySQLDBQueryBuiler();
    }
}

/**
 * Каждая Конкретная Фабрика имеет соответствующую вариацию объекта.
 */
class PostgreSQLFactory extends AbstractFactory
{
    public function DBConnection(): Connection
    {
        return new PostgreSQLDBConnection();
    }

    public function DBRecrord(): Record
    {
        return new PostgreSQLDBRecord();
    }

    public function DBQueryBuiler(): QueryBuiler
    {
        return new PostgreSQLDBQueryBuiler();
    }
}

class OracleFactory extends AbstractFactory
{
    public function DBConnection(): Connection
    {
        return new OracleDBConnection();
    }

    public function DBRecrord(): Record
    {
        return new OracleDBRecord();
    }

    public function DBQueryBuiler(): QueryBuiler
    {
        return new OracleDBQueryBuiler();
    }
}

/**
 * Каждый отдельный класс семейства объектов должен иметь базовый интерфейс.
 * Все вариации классов должны реализовывать этот интерфейс.
 */
interface Connection
{
    public function getConnection() : string;
}

/**
 * Конкретные объекты создаются соответствующими Конкретными Фабриками.
 */
class MySQLDBConnection implements Connection
{
    public function getConnection() : string
    {
        return "MySQL DB Connection";
    }
}

class PostgreSQLDBConnection implements Connection
{
    public function getConnection() : string
    {
        return "PostgreSQL DB Connection";
    }
}

class OracleDBConnection implements Connection
{
    public function getConnection() : string
    {
        return "Oracle DB Connection";
    }
}

interface Record
{
    public function getRecord() : string;
}

class MySQLDBRecord implements Record
{
    public function getRecord() : string
    {
        return "MySQL DB Record";
    }
}

class PostgreSQLDBRecord implements Record
{
    public function getRecord() : string
    {
        return "PostgreSQL DB Record";
    }
}

class OracleDBRecord implements Record
{
    public function getRecord() : string
    {
        return "Oracle DB Record";
    }
}

interface QueryBuiler
{
    public function getQueryBuiler() : string;
}

class MySQLDBQueryBuiler implements QueryBuiler
{
    public function getQueryBuiler() : string
    {
        return "MySQL DB Query Builer";
    }
}

class PostgreSQLDBQueryBuiler implements QueryBuiler
{
    public function getQueryBuiler() : string
    {
        return "PostgreSQL DB Query Builer";
    }
}

class OracleDBQueryBuiler implements QueryBuiler
{
    public function getQueryBuiler() : string
    {
        return "Oracle DB Query Builer";
    }
}

/**
 * Клиентский код работает с фабриками и классами только через абстрактные
 * типы: Абстрактная Фабрика и Абстрактный класс. Это позволяет передавать
 * любой подкласс фабрики или объекта клиентскому коду, не нарушая его.
 */
function clientCode(AbstractFactory $factory)
{
    $connection = $factory->DBConnection();
    $record = $factory->DBRecrord();
    $queryBuilder = $factory->DBQueryBuiler();

    echo $connection->getConnection() . "\n";
    echo $record->getRecord() . "\n";
    echo $queryBuilder->getQueryBuiler() . "\n";
}

/**
 * Клиентский код может работать с любым конкретным классом фабрики.
 */
echo "Client: Testing client code with MySQL factory type:\n";
clientCode(new MySQLFactory());

echo "\n";
echo "Client: Testing client code with PostgreSQL factory type:\n";
clientCode(new PostgreSQLFactory());

echo "\n";
echo "Client: Testing client code with Oracle factory type:\n";
clientCode(new OracleFactory());
