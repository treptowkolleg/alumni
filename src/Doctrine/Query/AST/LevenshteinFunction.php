<?php

namespace App\Doctrine\Query\AST;

use Doctrine\ORM\Query\AST\Functions\FunctionNode;
use Doctrine\ORM\Query\Parser;
use Doctrine\ORM\Query\QueryException;
use Doctrine\ORM\Query\SqlWalker;
use Doctrine\ORM\Query\TokenType;

class LevenshteinFunction extends FunctionNode
{
    public $firstString = null;
    public $secondString = null;
    public $threshold = null;

    public function getSql(SqlWalker $sqlWalker): string
    {
        return sprintf(
            'LEVENSHTEIN(%s, %s, %s)',
            $this->firstString->dispatch($sqlWalker),
            $this->secondString->dispatch($sqlWalker),
            $this->threshold->dispatch($sqlWalker)
        );
    }

    /**
     * @throws QueryException
     */
    public function parse(Parser $parser): void
    {
        $lexer = $parser->getLexer();
        $parser->match(TokenType::T_IDENTIFIER); // 'LEVENSHTEIN'
        $parser->match(TokenType::T_OPEN_PARENTHESIS);
        $this->firstString = $parser->ArithmeticExpression();
        $parser->match(TokenType::T_COMMA);
        $this->secondString = $parser->ArithmeticExpression();
        $parser->match(TokenType::T_COMMA);
        $this->threshold = $parser->ArithmeticExpression();
        $parser->match(TokenType::T_CLOSE_PARENTHESIS);
    }
}