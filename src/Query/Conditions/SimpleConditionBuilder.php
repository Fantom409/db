<?php

namespace Yiisoft\Db\Query\Conditions;

use Yiisoft\Db\Expression\ExpressionBuilderInterface;
use Yiisoft\Db\Expression\ExpressionBuilderTrait;
use Yiisoft\Db\Expression\ExpressionInterface;

/**
 * Class NotConditionBuilder builds objects of {@see SimpleCondition}.
 */
class SimpleConditionBuilder implements ExpressionBuilderInterface
{
    use ExpressionBuilderTrait;

    /**
     * Method builds the raw SQL from the $expression that will not be additionally escaped or quoted.
     *
     * @param ExpressionInterface|SimpleCondition $expression the expression to be built.
     * @param array $params the binding parameters.
     *
     * @return string the raw SQL that will not be additionally escaped or quoted.
     */
    public function build(ExpressionInterface $expression, array &$params = []): string
    {
        $operator = $expression->getOperator();
        $column = $expression->getColumn();
        $value = $expression->getValue();

        if (strpos($column, '(') === false) {
            $column = $this->queryBuilder->getDb()->quoteColumnName($column);
        }

        if ($value === null) {
            return "$column $operator NULL";
        }
        if ($value instanceof ExpressionInterface) {
            return "$column $operator {$this->queryBuilder->buildExpression($value, $params)}";
        }

        $phName = $this->queryBuilder->bindParam($value, $params);

        return "$column $operator $phName";
    }
}
