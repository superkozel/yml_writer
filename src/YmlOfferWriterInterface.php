<?php

namespace Superkozel\YmlWriter;

interface YmlOfferWriterInterface
{
    public function write(YmlXmlWriter $writer): void;
}