<?php

namespace Superkozel\YmlWriter;


use XMLWriter;

class YmlWriter
{
    final const PROGRESS_NONE = 0;
    final const PROGRESS_STARTED = 1;
    final const PROGRESS_CATEGORIES = 2;
    final const PROGRESS_OFFERS = 3;
    final const PROGRESS_FINISHED = 4;

    protected string $path;

    protected string $name;
    protected string $company;
    protected string $url;
    protected ?string $platform;
    protected ?string $agency;
    protected ?string $email;
    protected ?string $localDeliveryCost = null;
    protected XMLWriter $writer;
    protected int $progress;

    public function __construct()
    {
        $this->writer = new XMLWriter();
        $this->progress = static::PROGRESS_NONE;
    }

    public static function create(): static
    {
        return new static();
    }

    public function addCategory(int $id, string $name, int $parentId): void
    {
        if ($this->progress === self::PROGRESS_STARTED) {
            $this->progress = self::PROGRESS_CATEGORIES;
            $this->getWriter()->endElement();
            $this->getWriter()->startElement('categories');
        }

        $this->getWriter()->startElement('category');

        $this->getWriter()->writeAttribute('id', (string)$id);

        if ($parentId) {
            $this->getWriter()->writeAttribute('parentId', (string)$parentId);
        }

        $this->getWriter()->text($name);

        $this->getWriter()->endElement();
    }

    public function addOffer(YmlOfferWriterInterface $offer): void
    {
        $writer = $this->getWriter();

        if ($this->progress === self::PROGRESS_CATEGORIES) {
            $writer->endElement();
            $writer->startElement('offers');
            $this->progress = self::PROGRESS_OFFERS;
        }

        $offer->write($writer);
    }

    public function start(): void
    {
        $writer = new XMLWriter();

        $this->setWriter($writer);

//        $writer->openURI($this->getPath());
        $writer->openMemory();

        $writer->startDocument('1.0', 'utf-8');

        $writer->startDtd('yml_catalog', 'shops.dtd', 'shops.dtd');
        $writer->endDtd();

        $writer->setIndent(true);
        $writer->startElement('yml_catalog');

        $writer->writeAttribute('date', date('Y-m-d H:i'));

        $writer->startElement('shop');

        $writer->writeElement('name', $this->getName());
        $writer->writeElement('company', $this->getCompany());
        $writer->writeElement('url', $this->getUrl());
        $writer->writeElement('local_delivery_cost', $this->getLocalDeliveryCost());

        $this->progress = self::PROGRESS_STARTED;
    }

    public function finish(): string
    {
        $writer = $this->getWriter();

        $writer->endElement();
        $writer->endElement();
        $writer->endElement();

        return $writer->outputMemory();
    }

    public function setCompany(string $company): void
    {
        $this->company = $company;
    }

    public function getCompany(): string
    {
        return $this->company;
    }

    public function setLocalDeliveryCost(string $localDeliveryCost): void
    {
        $this->localDeliveryCost = $localDeliveryCost;
    }

    public function getLocalDeliveryCost(): ?string
    {
        return $this->localDeliveryCost;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setPath($path): void
    {
        $this->path = $path;
    }

    public function getPath(): string
    {
        return $this->path;
    }

    public function setUrl(string $url): void
    {
        $this->url = $url;
    }

    public function getUrl(): string
    {
        return $this->url;
    }

    public function setWriter(XMLWriter $writer): void
    {
        $this->writer = $writer;
    }

    public function getWriter(): XMLWriter
    {
        return $this->writer;
    }
}
