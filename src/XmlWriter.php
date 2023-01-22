<?php

namespace Superkozel\YmlWriter;

class XmlWriter extends \XMLWriter
{
    public function writeElementOptional(string $name, mixed $value): void
    {
        if ($value === null) {
            return;
        }

        $this->writeElement($name, $value);
    }
}