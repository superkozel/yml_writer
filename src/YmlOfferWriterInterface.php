<?php


namespace Superkozel;

use XMLWriter;

interface YmlOfferWriterInterface
{
    public function write(XMLWriter $writer): void;
}