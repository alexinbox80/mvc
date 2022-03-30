<?php
// 3. Команда: вы — разработчик продукта Macrosoft Word. Это текстовый редактор с возможностями копирования,
// вырезания и вставки текста (пока только это). Необходимо реализовать механизм по логированию этих операций и
// возможностью отмены и возврата действий. Т.е., в ходе работы программы вы открываете текстовый файл .txt,
// выделяете участок кода (два значения: начало и конец) и выбираете, что с этим кодом делать.

/**
 * Интерфейс Команды объявляет метод для выполнения команд.
 */
interface Command
{
    public function execute() : void;
    public function unDo() : void;
}

/**
 * Некоторые команды способны выполнять простые операции самостоятельно.
 */
class SimpleCommand implements Command
{
    private $payload;

    public function __construct(string $payload)
    {
        $this->payload = $payload;
    }

    public function execute() : void
    {
        echo "SimpleCommand: See, I can do this command ({$this->payload})\n";
    }

    public function unDo() : void
    {
        echo "SimpleCommand: See, I can do unDo command ({$this->payload})\n";
    }
}

/**
 * Но есть и команды, которые делегируют более сложные операции другим объектам,
 * называемым «получателями».
 */
class ComplexCommand implements Command
{
    /**
     * @var Receiver
     */
    private $receiver;

    /**
     * Данные о контексте, необходимые для запуска методов получателя.
     */
    private $a;

    /**
     * Сложные команды могут принимать один или несколько объектов-получателей
     * вместе с любыми данными о контексте через конструктор.
     */
    public function __construct(Receiver $receiver, string $a)
    {
        $this->receiver = $receiver;
        $this->a = $a;
    }

    /**
     * Команды могут делегировать выполнение любым методам получателя.
     */
    public function execute() : void
    {
        echo "ComplexCommand: Complex stuff should be done by a receiver object.\n";
        $this->receiver->doCommand($this->a);
        $this->receiver->doWriteLogs($this->a);
    }

    public function unDo() : void
    {
        echo "ComplexCommand: I do undo command here.\n";
        $this->receiver->doUndoCommand($this->a);
        $this->receiver->doWriteLogs($this->a);
    }
}

/**
 * Классы Получателей содержат некую важную бизнес-логику. Они умеют выполнять
 * все виды операций, связанных с выполнением запроса. Фактически, любой класс
 * может выступать Получателем.
 */
class Receiver
{
    public function doCommand(string $a) : void
    {
        echo "Receiver: Working on ({$a}).\n";
    }

    public function doUndoCommand(string $a) : void
    {
        echo "Receiver: Undo working on ({$a}).\n";
    }

    public function doWriteLogs(string $a) : void
    {
        echo "Receiver: Also write Log ({$a}).\n";
    }
}

/**
 * Отправитель связан с одной или несколькими командами. Он отправляет запрос
 * команде.
 */
class Invoker
{
    /**
     * @var Command
     */
    private $onStart;

    /**
     * @var Command
     */
    private $onFinish;

    /**
     * @var Command
     */
    private $onUnDo;

    /**
     * Инициализация команд.
     */
    public function setOnStart(Command $command) : void
    {
        $this->onStart = $command;
    }

    public function setOnFinish(Command $command) : void
    {
        $this->onFinish = $command;
    }

    public function setUnDo(Command $command) : void
    {
        $this->onUnDo = $command;
    }

    /**
     * Отправитель не зависит от классов конкретных команд и получателей.
     * Отправитель передаёт запрос получателю косвенно, выполняя команду.
     */
    public function doSomethingImportant() : void
    {
        echo "Invoker: Does anybody want something done before I begin?\n";
        if ($this->onStart instanceof Command) {
            $this->onStart->execute();
        }

        echo "Invoker: ...doing something really important...\n";

        echo "Invoker: Does anybody want something done after I finish?\n";
        if ($this->onFinish instanceof Command) {
            $this->onFinish->execute();
        }

        echo "Invoker: Does anybody want undo command?\n";
        if ($this->onUnDo instanceof Command) {
            $this->onUnDo->unDo();
        }
    }
}

/**
 * Клиентский код может параметризовать отправителя любыми командами.
 */
$invoker = new Invoker();

$open = new Receiver();
$invoker->setOnFinish(new ComplexCommand($open, 'open'));
$invoker->setUnDo(new ComplexCommand($open, 'open'));
$invoker->doSomethingImportant();

echo "\n\n";

$copy = new Receiver();
$invoker->setOnFinish(new ComplexCommand($copy, 'copy'));
$invoker->setUnDo(new ComplexCommand($copy, 'copy'));
$invoker->doSomethingImportant();

echo "\n\n";

$cut = new Receiver();
$invoker->setOnFinish(new ComplexCommand($cut, 'cut'));
$invoker->setUnDo(new ComplexCommand($cut, 'cut'));
$invoker->doSomethingImportant();
