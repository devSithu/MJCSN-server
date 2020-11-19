<?php

namespace App\Lib\CpsBlade;

use Illuminate\Support\Debug\HtmlDumper;
use Symfony\Component\VarDumper\Cloner\VarCloner;

class Dumper
{

    public function dump($value, $checkEmpty = false, $option = [])
    {
        if ($checkEmpty && empty($value)) {
            return;
        }
        $dumper = new HtmlDumper;
        $dumper->setStyles([
            'default' => 'background-color:#fff; color:#222; line-height:1.2em; font-weight:normal; font:12px Monaco, Consolas, monospace; word-wrap: break-word; white-space: pre-wrap; position:relative; z-index:100000; margin:0px; display:table; '
        ]);
        $dumper->setDumpBoundaries(
                '<pre class=sf-dump id=%s data-indent-pad="%s"><span class="sf-dump-note">&nbsp;</span><samp>', //
                '</samp></pre><script>Sfdump(%s)</script>'
        );
        $style = $option['style'] ?? '';
        return "<div tp-ignore='1' style='display:inline-block;$style'>" . $dumper->dump((new VarCloner)->cloneVar($value), true, ['maxDepth' => 0]) . "</div>";
    }

}
