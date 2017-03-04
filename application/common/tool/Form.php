<?php
namespace app\common\tool;

use think\form\FormBuilder;
use think\form\HtmlBuilder;

class Form extends FormBuilder
{

    public function __construct(HtmlBuilder $html, $csrfToken)
    {
        parent::__construct($html, $csrfToken);
    }
}