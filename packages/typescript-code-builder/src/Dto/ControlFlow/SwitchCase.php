<?php
namespace Apie\TypescriptCodeBuilder\Dto\ControlFlow;

use Apie\TypescriptCodeBuilder\Lists\CodeList;
use Apie\TypescriptCodeBuilder\TypescriptFileExpressionInterface;

class SwitchCase
{
    public function __construct(
        public ?TypescriptFileExpressionInterface $condition,
        public CodeList $codeList,
    ) {
    }
}
