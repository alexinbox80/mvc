<?php
// 2. Стратегия: есть интернет-магазин по продаже носков. Необходимо реализовать возможность оплаты различными
// способами (Qiwi, Яндекс, WebMoney). Разница лишь в обработке запроса на оплату и получение ответа от платёжной
// системы. В интерфейсе функции оплаты достаточно общей суммы товара и номера телефона.


/**
 * Контекст определяет интерфейс, представляющий интерес для клиентов.
 */
class Context
{
    /**
     * @var Strategy Контекст хранит ссылку на один из объектов Стратегии.
     * Контекст не знает конкретного класса стратегии. Он должен работать со
     * всеми стратегиями через интерфейс Стратегии.
     */
    private $strategy;

    /**
     * Обычно Контекст принимает стратегию через конструктор, а также
     * предоставляет сеттер для её изменения во время выполнения.
     */
    public function __construct(Strategy $strategy)
    {

        $this->strategy = $strategy;
    }

    /**
     * Обычно Контекст позволяет заменить объект Стратегии во время выполнения.
     */
    public function setStrategy(Strategy $strategy)
    {

        $this->strategy = $strategy;
    }

    /**
     * Вместо того, чтобы самостоятельно реализовывать множественные версии
     * алгоритма, Контекст делегирует некоторую работу объекту Стратегии.
     */
    public function doSomeBusinessLogic($totalCost, $phoneNumber) : void
    {
        // ...

        echo "Context: Make Payment ... \n";
        $result = $this->strategy->payment($totalCost, $phoneNumber);
        if ($result) {
            echo "Context: Success \n";
        } else {
            echo "Context: Something went wrong :-( \n";
        }

        // ...
    }
}

/**
 * Интерфейс Стратегии объявляет операции, общие для всех поддерживаемых версий
 * некоторого алгоритма.
 *
 * Контекст использует этот интерфейс для вызова алгоритма, определённого
 * Конкретными Стратегиями.
 */
interface Strategy
{
    public function payment($totalCost, $phoneNumber) : bool;
}

/**
 * Конкретные Стратегии реализуют алгоритм, следуя базовому интерфейсу
 * Стратегии. Этот интерфейс делает их взаимозаменяемыми в Контексте.
 */
class QiwiPay implements Strategy
{
    public function payment($totalCost, $phoneNumber) : bool
    {
        echo "QiwiPay: User: $phoneNumber, paid: $totalCost$\n";
        return true;
    }
}

class YandexPay implements Strategy
{
    public function payment($totalCost, $phoneNumber) : bool
    {
        echo "YandexPay: User: $phoneNumber, paid: $totalCost$\n";
        return true;
    }
}

class WebMoneyPay implements Strategy
{
    public function payment($totalCost, $phoneNumber) : bool
    {
        echo "WebMoneyPay: User: $phoneNumber, paid: $totalCost$\n";
        return true;
    }
}

/**
 * Клиентский код выбирает конкретную стратегию и передаёт её в контекст. Клиент
 * должен знать о различиях между стратегиями, чтобы сделать правильный выбор.
 */
$context = new Context(new QiwiPay());
echo "Client Strategy: Payment went through Qiwi.\n";
$context->doSomeBusinessLogic(10.99, '+7 935 456 77 83');

echo "\n";

$context = new Context(new YandexPay());
echo "Client Strategy: Payment went through Yandex.\n";
$context->doSomeBusinessLogic(5.99, '+7 912 446 22 45');

echo "\n";

$context = new Context(new WebMoneyPay());
echo "Client Strategy: Payment went through WebMoney.\n";
$context->doSomeBusinessLogic(2.99, '+7 945 432 23 56');

echo "\n";
