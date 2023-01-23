<?php

namespace Superkozel\YmlWriter;

class YmlWriter
{
    protected final const PROGRESS_NONE = 0;
    protected final const PROGRESS_STARTED = 1;
    protected final const PROGRESS_CATEGORIES = 2;
    protected final const PROGRESS_OFFERS = 3;
    protected final const PROGRESS_FINISHED = 4;

    final const MODE_MEMORY = 1;
    final const MODE_FILE = 1;

    protected ?string $path = null;
    protected string $name;
    protected string $company;
    protected string $url;
    protected ?string $platform = null;
    protected ?string $agency = null;
    protected ?string $email = null;
    protected ?string $localDeliveryCost = null;
    protected YmlXmlWriter $writer;
    protected int $progress;
    protected int $mode;
    protected int $offerCounter;

    public function __construct()
    {
        $this->writer = new YmlXmlWriter();
        $this->progress = static::PROGRESS_NONE;
        $this->offerCounter = 0;
    }

    public static function create(): static
    {
        return new static();
    }

    public function start(): void
    {
        $writer = new YmlXmlWriter();

        $this->setWriter($writer);

        if ($this->path !== null) {
            $writer->openURI($this->path);
            $this->mode = self::MODE_FILE;
        } else {
            $writer->openMemory();
            $this->mode = self::MODE_MEMORY;
        }

        $writer->startDocument('1.0', 'utf-8');

        $writer->startDtd('yml_catalog', 'shops.dtd', 'shops.dtd');
        $writer->endDtd();

        $writer->setIndent(true);
        $writer->startElement('yml_catalog');

        $writer->writeAttribute('date', date('Y-m-d H:i'));

        $writer->startElement('shop');

        $writer->writeElement('name', $this->name);
        $writer->writeElement('company', $this->company);
        $writer->writeElement('url', $this->url);
        $writer->writeElementOptional('local_delivery_cost', $this->localDeliveryCost);
        $writer->writeElementOptional('agency', $this->agency);
        $writer->writeElementOptional('platform', $this->platform);
        $writer->writeElementOptional('email', $this->email);

        $this->progress = self::PROGRESS_STARTED;
    }

    public function addCategory(int $id, string $name, ?int $parentId): void
    {
        if ($this->progress === self::PROGRESS_STARTED) {
            $this->progress = self::PROGRESS_CATEGORIES;
            $this->getWriter()->startElement('categories');
        }

        $this->getWriter()->startElement('category');
        $this->getWriter()->writeAttribute('id', (string)$id);
        if ($parentId !== null) {
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
            if ($this->mode === self::MODE_FILE) {
                $writer->flush();
            }
            $writer->startElement('offers');
            $this->progress = self::PROGRESS_OFFERS;
        }

        ++$this->offerCounter;

        if ($this->mode === self::MODE_FILE && $this->offerCounter % 200 === 0) {
            $writer->flush();
        }

        $offer->write($writer);
    }

    public function finish(): ?string
    {
        $writer = $this->getWriter();

        $writer->endDocument();
        $this->progress = self::PROGRESS_FINISHED;

        if ($this->mode === self::MODE_MEMORY) {
            return $writer->outputMemory();
        }

        $writer->flush();

        return null;
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

    public function getPath(): ?string
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

    public function setWriter(YmlXmlWriter $writer): void
    {
        $this->writer = $writer;
    }

    public function getWriter(): YmlXmlWriter
    {
        return $this->writer;
    }
}
