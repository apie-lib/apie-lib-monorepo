<?php
namespace Apie\TypescriptCodeBuilder\Enums;

enum BinaryOperator: string
{
    case Add = '+';
    case Subtract = '-';
    case Divide = '/';
    case Multiply = '*';
    case LogicalAnd = '&&';
    case BitwiseAnd = '&';
    case BitwiseOr = '|';
    case LogicalOr = '||';
    case Equal = '===';
    case NotEqual = '!==';
    case LessThan = '<';
    case LessThanOrEqual = '<=';
    case GreaterThan = '>';
    case GreaterThanOrEqual = '>=';
    case NullCoalesce = '??';

    public function allowsNumbers(): bool
    {
        return in_array($this, [
            self::Add,
            self::Subtract,
            self::Divide,
            self::Multiply,
            self::BitwiseAnd,
            self::BitwiseOr,
            self::Equal,
            self::NotEqual,
            self::LessThan,
            self::LessThanOrEqual,
            self::GreaterThan,
            self::GreaterThanOrEqual
        ]);
    }

    public function allowsNull(): bool
    {
        return in_array($this, [
            self::Equal,
            self::NotEqual,
            self::NullCoalesce,
        ]);
    }

    public function allowsBoolean(): bool
    {
        return in_array(
            $this,
            [
                self::LogicalAnd,
                self::LogicalOr,
                self::Equal,
                self::NotEqual,
            ]
        );
    }

    public function allowsString(): string
    {
        return in_array(
            $this,
            [
                self::Add,
                self::Equal,
                self::NotEqual
            ]
        );
    }
}
