<?php
// 1. Наблюдатель: есть сайт HandHunter.gb. На нем работники могут подыскать себе вакансию РНР-программиста.
// Необходимо реализовать классы искателей с их именем, почтой и стажем работы. Также реализовать возможность в любой
// момент встать на биржу вакансий (подписаться на уведомления), либо же, напротив, выйти из гонки за местом. Таким
// образом, как только появится новая вакансия программиста, все жаждущие автоматически получат уведомления на
// почту (можно реализовать условно).

/**
 * PHP имеет несколько встроенных интерфейсов, связанных с паттерном
 * Наблюдатель.
 *
 * Вот как выглядит интерфейс Издателя:
 *
 * @link http://php.net/manual/ru/class.splsubject.php
 *
 *     interface SplSubject
 *     {
 *         // Присоединяет наблюдателя к издателю.
 *         public function attach(SplObserver $observer);
 *
 *         // Отсоединяет наблюдателя от издателя.
 *         public function detach(SplObserver $observer);
 *
 *         // Уведомляет всех наблюдателей о событии.
 *         public function notify();
 *     }
 *
 * Также имеется встроенный интерфейс для Наблюдателей:
 *
 * @link http://php.net/manual/ru/class.splobserver.php
 *
 *     interface SplObserver
 *     {
 *         public function update(SplSubject $subject);
 *     }
 */

/**
 * Издатель владеет некоторым важным состоянием и оповещает наблюдателей о его
 * изменениях.
 */
class HandHunter implements \SplSubject
{
    /**
     * @var string Для удобства в этой переменной хранится состояние Издателя,
     * необходимое всем подписчикам.
     */
    public $vacancy;
    /**
     * @var \SplObjectStorage Список подписчиков. В реальной жизни список
     * подписчиков может храниться в более подробном виде (классифицируется по
     * типу события и т.д.)
     */
    private $observers;

    public function __construct()
    {
        $this->observers = new \SplObjectStorage();
    }

    /**
     * Методы управления подпиской.
     */
    public function attach(\SplObserver $observer) : void
    {
        echo "HandHunter: Attached an observer: {$observer->getName()}.\n";
        $this->observers->attach($observer);
    }

    public function detach(\SplObserver $observer) : void
    {
        $this->observers->detach($observer);
        echo "HandHunter: Detached an observer: {$observer->getName()}.\n";
    }

    /**
     * Запуск обновления в каждом подписчике.
     */
    public function notify() : void
    {
        echo "HandHunter: Notifying observers...\n";
        foreach ($this->observers as $observer) {
            echo "HandHunter: subscriber {$observer->getName()} via email {$observer->getEmail()}.\n";
            $observer->update($this);
        }
    }

    /**
     * Обычно логика подписки – только часть того, что делает Издатель. Издатели
     * часто содержат некоторую важную бизнес-логику, которая запускает метод
     * уведомления всякий раз, когда должно произойти что-то важное (или после
     * этого).
     */
    public function sendNotification(string $vacancy) : void
    {
        echo "\nHandHunter: I have a new vacancy.\n";
        $this->vacancy = $vacancy;

        echo "HandHunter: My vacancy has just changed to: {$this->vacancy}\n";
        $this->notify();
    }
}

/**
 * Конкретные Наблюдатели реагируют на обновления, выпущенные Издателем, к
 * которому они прикреплены.
 */
abstract class Applicant implements \SplObserver
{
    private $name;
    private $email;
    private $workExperience;

    public function __construct(string $name, string $email, int $workExperience)
    {
        $this->name = $name;
        $this->email = $email;
        $this->workExperience = $workExperience;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function getWorkExperience()
    {
        return $this->workExperience;
    }

    public abstract function update(\SplSubject $subject) : void;
}

class SubscriberA extends Applicant
{
    public function update(\SplSubject $subject) : void
    {
        echo "SubscriberA: Reacted to the event {$subject->vacancy}.\n";
    }
}

class SubscriberB extends Applicant
{
    public function update(\SplSubject $subject) : void
    {
        echo "SubscriberB: Reacted to the event {$subject->vacancy}.\n";
    }
}

/**
 * Клиентский код.
 */
$subject = new HandHunter();

$o1 = new SubscriberA('Ivan', 'ivan@mail.ru', 5);
$subject->attach($o1);

$o2 = new SubscriberB('Petr', 'petr@mail.ru', 3);
$subject->attach($o2);

$subject->sendNotification('Программист С#');
$subject->sendNotification('Программист PHP');

$subject->detach($o2);

$subject->sendNotification('Программист C++');
