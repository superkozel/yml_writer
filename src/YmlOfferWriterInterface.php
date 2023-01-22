<?php


namespace Superkozel\YmlWriter;

use XMLWriter;

interface YmlOfferWriterInterface
{
    public function write(XMLWriter $writer): void;
}