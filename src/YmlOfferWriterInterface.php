<?php

namespace Superkozel\YmlWriter;

interface YmlOfferWriterInterface
{
    public function write(XmlWriter $writer): void;
}