<?php

namespace FacturaScripts\Plugins\Sacramento\Lib\Widget;

use FacturaScripts\Core\Lib\Widget\WidgetText as DinWidgetText;

class WidgetText extends DinWidgetText
{

    protected function onclickHtml($inside, $titleurl = ''): string
    {
        if (empty($this->onclick) || is_null($this->value)) {
            return empty($titleurl) ? $inside : '<a href="' . $titleurl . '">' . $inside . '</a>';
        }

        $params = str_contains($this->onclick, '?') ? : '?';
        return '<a href="' . FS_ROUTE . '/' . $this->onclick . rawurlencode($this->value)
            . '" class="cancelClickable">' . $inside . '</a>';

    }
}