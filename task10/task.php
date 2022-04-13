<?php
// 1. Реализовать построение и обход дерева для математического выражения.
//
// 2. * Реализовать решение уравнений и примеров из задания 1.
//
// 3. * Рассмотреть подход прямой и обратной польских нотаций. Чем они лучше деревьев в первой задаче?
// Нужны ли деревья в их реализации?

class Node
{
    public $value;
    public $left;
    public $right;

    public function __construct($value = null, Node $left = null, Node $right = null)
    {
        $this->value = $value;
        $this->right = $right;
        $this->left = $left;
    }
}

class Tree
{
    private $stack;
    private $root;

    public function __construct($arr)
    {
        $this->stack = new SplStack();
        $this->buildTree($arr);
    }

    public function runCalculation()
    {
        return $this->calculate($this->root);
    }

    private function buildTree($arr)
    {
        foreach ($arr as $item) {
            $this->insert($item);
        }

        $this->root = $this->stack->pop();

        return $this->root;
    }

    private function insert($item)
    {
        if (preg_match(Parser::NUMBER_PATTERN, $item)) {
            $this->stack->push(new Node($item));
        } else if (preg_match(Parser::OPERATION_PATTERN, $item)) {
            $leftNode = $this->stack->pop();
            $rightNode = $this->stack->pop();
            $this->stack->push(new Node($item, $leftNode, $rightNode));
        }
    }

    private function calculate(Node &$node)
    {
        if (preg_match(Parser::NUMBER_PATTERN, $node->value)) {
            return $node->value;
        } else if (preg_match(Parser::OPERATION_PATTERN, $node->value)) {
            switch ($node->value) {
                case Parser::PLUS:
                    {
                        return $this->calculate($node->right) + $this->calculate($node->left);
                    }
                case Parser::MINUS:
                    {
                        return $this->calculate($node->right) - $this->calculate($node->left);
                    }
                case Parser::MULTIPLICATION:
                    {
                        return $this->calculate($node->right) * $this->calculate($node->left);
                    }
                case Parser::EXPONENTIATION:
                    {
                        return pow($this->calculate($node->right), (int)$this->calculate($node->left));
                    }
                case Parser::DIVISION:
                    {
                        try {
                            return $this->calculate($node->right) / $this->calculate($node->left);
                        } catch (ArithmeticError $e) {
                            exit('division by zero' . PHP_EOL);
                        }
                    }
            }
        }
    }
}

class Parser
{
    public const NUMBER_PATTERN = '/[0-9a-zA-Z\.]/';
    public const OPERATION_PATTERN = '/[\+\-\*\/\^]/';
    private const OPEN_BRACKET = '(';
    private const CLOSE_BRACKET = ')';
    public const MINUS = '-';
    public const PLUS = '+';
    public const DIVISION = '/';
    public const MULTIPLICATION = '*';
    public const EXPONENTIATION = '^';

    const PRIORITY_OPERATION = [
        self::OPEN_BRACKET => ['priority' => '0', 'association' => 'left'],
        self::CLOSE_BRACKET => ['priority' => null, 'association' => 'left'],
        self::MINUS => ['priority' => '2', 'association' => 'left'],
        self::PLUS => ['priority' => '2', 'association' => 'left'],
        self::MULTIPLICATION => ['priority' => '3', 'association' => 'left'],
        self::DIVISION => ['priority' => '3', 'association' => 'left'],
        self::EXPONENTIATION => ['priority' => '4', 'association' => 'right']
    ];

    private $stack;
    private $buffer;
    private $expression;

    public function __construct(string $value)
    {
        $this->stack = new SplStack();
        $this->buffer = [];
        $this->expression = $this->run($value);
    }

    public function reversePolishNotation()
    {
        return $this->expression;
    }

    private function run($str)
    {
        $arr = $this->prepareString($str);
        return $this->parse($arr);
    }

    private function prepareString($str)
    {
        $str = preg_replace("/\s/", "", $str);
        $str = str_replace(",", ".", $str);
        $str = str_split($str);

        // проверяем на оператор в начале, если первый символ операнд ставим впереди ноль
        if (preg_match(self::OPERATION_PATTERN, $str[0])) {
            array_unshift($str, "0");
        }
        return $str;
    }

    private function pushOperation($value)
    {
        while (true) {
            if ($this->stack->isEmpty()) {
                $this->stack->push($value);
                break;
            } else {
                $lastOperation = $this->stack->pop();

                $prevPriority = self::PRIORITY_OPERATION[$lastOperation]['priority'];
                $currentPriority = self::PRIORITY_OPERATION[$value]['priority'];
                $currentAssociation = self::PRIORITY_OPERATION[$value]['association'];

                if ($currentAssociation === "left") {
                    if ($currentPriority > $prevPriority) {
                        $this->stack->push($lastOperation);
                        $this->stack->push($value);
                        break;
                    } else {
                        $this->buffer[] = $lastOperation;
                    }
                } elseif ($currentAssociation === "right") {
                    if ($currentPriority >= $prevPriority) {
                        $this->stack->push($lastOperation);
                        $this->stack->push($value);
                        break;
                    } elseif ($currentPriority < $prevPriority) {
                        $this->buffer[] = $lastOperation;
                    }
                }
            }
        }
    }

    private function parse($arr)
    {
        $lastSymbolIsNumber = true;
        foreach ($arr as $key => $value) {
            if (preg_match(self::OPERATION_PATTERN, $value)) {
                $this->pushOperation($value);
                $lastSymbolIsNumber = false;
            } elseif (preg_match(self::NUMBER_PATTERN, $value)) {
                if ($lastSymbolIsNumber) {
                    $this->buffer[] = array_pop($this->buffer) . $value;
                } else {
                    $this->buffer[] = $value;
                    $lastSymbolIsNumber = true;
                }
            } elseif ($value == self::OPEN_BRACKET) {
                $this->stack->push($value);
                $lastSymbolIsNumber = false;
            } elseif ($value == self::CLOSE_BRACKET) {
                while (true) {
                    $symbol = $this->stack->pop();
                    if ($symbol != self::OPEN_BRACKET) {
                        $this->buffer[] = $symbol;
                    } else {
                        break;
                    }
                }
                $lastSymbolIsNumber = false;
            }
        }

        $length = $this->stack->count();
        for ($i = 0; $i < $length; $i++) {
            $this->buffer[] = $this->stack->pop();
        }

        return $this->buffer;
    }

    public function showExpression()
    {
        echo 'reverse polish notation: ' . PHP_EOL;

        foreach ($this->expression as $item) {
            echo $item . ' ';
        }

        echo PHP_EOL;
    }
}

//$expression = '(6-5)/1-3*9/3';
$expression = '-5/1-3*9/3';
//$expression = '(3-6)*9/3';

$parser = new Parser($expression);
$tree = new Tree($parser->reversePolishNotation());

echo 'expression :' . PHP_EOL;
echo $expression . PHP_EOL;

$parser->showExpression();


echo 'answer : ' . PHP_EOL;
echo $expression . ' = ' . $tree->runCalculation() . PHP_EOL;
