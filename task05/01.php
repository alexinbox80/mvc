<?php
// 1. Реализовать на PHP пример Декоратора, позволяющий отправлять уведомления несколькими различными способами
// (описан в этой методичке).

/**
 * Базовый интерфейс Компонента определяет поведение, которое изменяется
 * декораторами.
 */
interface ISendMessage
{
    public function send() : string;
}

/**
 * Конкретные Компоненты предоставляют реализации поведения по умолчанию. Может
 * быть несколько вариаций этих классов.
 */
class SendMessage implements ISendMessage
{
    public function send() : string
    {
        return "Send Message";
    }
}

/**
 * Базовый класс Декоратора следует тому же интерфейсу, что и другие компоненты.
 * Основная цель этого класса - определить интерфейс обёртки для всех конкретных
 * декораторов. Реализация кода обёртки по умолчанию может включать в себя поле
 * для хранения завёрнутого компонента и средства его инициализации.
 */
class Decorator implements ISendMessage
{
    /**
     * @var ISendMessage()
     */
    protected $text;

    public function __construct(ISendMessage $text)
    {

        $this->text = $text;
    }

    /**
     * Декоратор делегирует всю работу обёрнутому компоненту.
     */
    public function send() : string
    {

        return $this->text->send();
    }
}

/**
 * Конкретные Декораторы вызывают обёрнутый объект и изменяют его результат
 * некоторым образом.
 */
class SendEmail extends Decorator
{
    /**
     * Декораторы могут вызывать родительскую реализацию операции, вместо того,
     * чтобы вызвать обёрнутый объект напрямую. Такой подход упрощает расширение
     * классов декораторов.
     */
    public function send() : string
    {

        return "Send message via email ( " . parent::send() . " )";
    }
}

/**
 * Декораторы могут выполнять своё поведение до или после вызова обёрнутого
 * объекта.
 */
class SendTelegram extends Decorator
{
    public function send() : string
    {

        return "Send message via telegram ( " . parent::send() . " )";
    }
}

/**
 * Клиентский код работает со всеми объектами, используя интерфейс Компонента.
 * Таким образом, он остаётся независимым от конкретных классов компонентов, с
 * которыми работает.
 */
function clientCode(ISendMessage $text)
{

    // ...

    echo "RESULT: " . $text->send();

    // ...
}

/**
 * Таким образом, клиентский код может поддерживать как простые компоненты...
 */
$simple = new SendMessage();
echo "Client: I've got a simple component:\n";
clientCode($simple);
echo "\n\n";

$decorator1 = new SendEmail($simple);
echo "Client: Now I've got a decorated component:\n";
clientCode($decorator1);
echo "\n\n";

$decorator1 = new SendTelegram($simple);
echo "Client: Now I've got a decorated component:\n";
clientCode($decorator1);
echo "\n\n";

/**
 * ...так и декорированные.
 *
 * Обратите внимание, что декораторы могут обёртывать не только простые
 * компоненты, но и другие декораторы.
 */
$decorator1 = new SendEmail($simple);
$decorator2 = new SendTelegram($decorator1);
echo "Client: Now I've got a decorated component:\n";
clientCode($decorator2);
echo "\n\n";
