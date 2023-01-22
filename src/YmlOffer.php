<?php

namespace Superkozel;

use DateInterval;
use Superkozel\YmlOffer\YmlOfferAgeUnit;
use Superkozel\YmlOffer\YmlOfferConditionQuality;
use Superkozel\YmlOffer\YmlOfferConditionType;
use Superkozel\YmlOffer\YmlOfferType;
use Superkozel\YmlOffer\YmlOfferVAT;
use XMLWriter;

class YmlOffer implements YmlOfferWriterInterface
{
    protected int $id;
    protected string $url;
    protected int|float $price;
    protected int|float $oldPrice;
    protected int $currencyId;
    protected ?YmlOfferVAT $vat;
    protected bool $available;
    protected bool $disabled = false;
    protected ?int $count;
    protected ?int $minQuantity;
    protected ?int $stepQuantity;
    protected int $categoryId;
    protected ?string $marketCategory;
    protected string $picture;
    protected ?bool $delivery;
    protected bool $store;
    protected ?bool $pickup;
    protected float $localDeliveryCost;
    protected string $name;
    protected string $vendor;
    protected ?string $vendorCode;
    protected string $description;
    protected ?string $salesNotes;
    protected ?string $countryOfOrigin;
    protected ?string $barcode;
    protected ?string $dimensions;
    protected bool $cpa;

    protected ?YmlOfferType $type;
    protected ?bool $adult;
    protected ?bool $downloadable;
    protected ?DateInterval $periodOfValidityDays;
    protected ?string $commentValidityDays;
    protected ?DateInterval $serviceLifeDays;
    protected ?string $commentLifeDays;
    protected ?DateInterval $warrantyDays;
    protected ?string $commentWarranty;
    protected ?bool $manufacturerWarranty;
    protected ?string $certificate;
    protected ?string $tnVedCode;
    protected ?YmlOfferConditionType $conditionType;
    protected ?YmlOfferConditionQuality $conditionQuality;
    protected ?string $conditionReason;
    protected ?YmlOfferAgeUnit $ageUnit;
    protected ?int $age;
    protected ?int $boxCount;
    protected array $params = [];

    public static function create(): static
    {
        return new static();
    }

    public function write(XMLWriter $writer): void
    {
        $writer->startElement('offer');

        $writer->writeAttribute('id', (string)$this->id);
        $this->writeElementOptional('available', $this->boolVal($this->available), $writer);
        if ($this->disabled === true) {
            $writer->writeElement('disabled', $this->boolVal($this->disabled));
        }
        $writer->writeElement('name', $this->name);
        $writer->startElement('description');
        $writer->writeCdata($this->description);
        $writer->endElement();
        $writer->writeElement('url', $this->url);
        $writer->writeElement('store', $this->boolVal($this->store));
        if ($this->getPrice() > 0) {
            $writer->writeElement('price', $this->moneyFormat($this->price));
            if ($this->getOldPrice() > 0) {
                $writer->writeElement('oldprice', $this->moneyFormat($this->oldPrice));
            }
        }
        $writer->writeElement('currencyId', (string)$this->currencyId);
        $this->writeElementOptional('vat', $this->vat?->name, $writer);
        $writer->writeElement('categoryId', (string)$this->categoryId);
        $writer->writeElement('picture', $this->picture);
        $writer->writeElement('vendor', $this->vendor);
        $this->writeElementOptional('vendorCode', $this->vendorCode, $writer);
        foreach ($this->params as $name => $param) {
            [$value, $unit] = $param;
            $writer->startElement($name);
            if (!is_null($unit)) {
                $writer->writeAttribute('unit', $unit);
            }
            $writer->text($value);
            $writer->endElement();
        }

        if ($this->conditionType !== null) {
            $writer->startElement('condition');
            $writer->writeAttribute('type', $this->conditionType->value);
            $this->writeElementOptional('quality', $this->conditionQuality?->value, $writer);
            $this->writeElementOptional('reason', $this->conditionReason, $writer);
            $writer->endElement();
        }

        if ($this->age !== null && $this->ageUnit !== null) {
            $writer->startElement('age');
            $writer->writeAttribute('unit', $this->ageUnit->value);
            $writer->text((string)$this->age);
            $writer->endElement();
        }
        $this->writeElementOptional('box-count', $this->boxCount, $writer);
        $this->writeElementOptional('store', $this->boolVal($this->store), $writer);
        $this->writeElementOptional('pickup', $this->boolVal($this->pickup), $writer);
        $this->writeElementOptional('delivery', $this->boolVal($this->delivery), $writer);
        $this->writeElementOptional('local_delivery_cost', $this->moneyFormat($this->localDeliveryCost), $writer);
        $this->writeElementOptional('sales_notes', $this->salesNotes, $writer);
        $this->writeElementOptional('country_of_origin', $this->countryOfOrigin, $writer);
        $this->writeElementOptional('barcode', $this->barcode, $writer);
        $this->writeElementOptional('cpa', $this->cpa, $writer);
        $this->writeElementOptional('dimension', $this->dimensions, $writer);
        $this->writeElementOptional('manufacturer_warranty', $this->boolVal($this->manufacturerWarranty), $writer);

        $writer->endElement();
    }

    protected function moneyFormat(int|float $value): string
    {
        return str_replace('.', ',', (string)round($value, 2));
    }

    protected function writeElementOptional(string $name, mixed $value, XMLWriter $writer): void
    {
        if ($value === null) {
            return;
        }

        $writer->writeElement($name, $value);
    }


    protected function boolVal(?bool $val): ?string
    {
        if ($val === null) {
            return null;
        }

        return $val ? 'true' : 'false';
    }

    public function setAvailable(bool $available): static
    {
        $this->available = $available;

        return $this;
    }

    public function getAvailable(): bool
    {
        return $this->available;
    }

    public function setBarcode(string $barcode): static
    {
        $this->barcode = $barcode;

        return $this;
    }

    public function getBarcode(): ?string
    {
        return $this->barcode;
    }

    public function setCategoryId(int $categoryId): static
    {
        $this->categoryId = $categoryId;

        return $this;
    }

    public function getCategoryId(): int
    {
        return $this->categoryId;
    }

    public function setCountryOfOrigin(string $countryOfOrigin): static
    {
        $this->countryOfOrigin = $countryOfOrigin;

        return $this;
    }

    public function getCountryOfOrigin(): ?string
    {
        return $this->countryOfOrigin;
    }

    public function setCpa(bool $cpa): static
    {
        $this->cpa = $cpa;

        return $this;
    }

    public function getCpa(): bool
    {
        return $this->cpa;
    }

    public function setCurrencyId(int $currencyId): static
    {
        $this->currencyId = $currencyId;

        return $this;
    }

    public function getCurrencyId(): int
    {
        return $this->currencyId;
    }

    public function setDelivery(bool $delivery): static
    {
        $this->delivery = $delivery;

        return $this;
    }

    public function getDelivery(): ?bool
    {
        return $this->delivery;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function setId(int $id): static
    {
        $this->id = $id;

        return $this;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function setLocalDeliveryCost($localDeliveryCost): static
    {
        $this->localDeliveryCost = $localDeliveryCost;

        return $this;
    }

    public function getLocalDeliveryCost(): float
    {
        return $this->localDeliveryCost;
    }

    public function setManufacturerWarranty(bool $manufacturerWarranty): static
    {
        $this->manufacturerWarranty = $manufacturerWarranty;

        return $this;
    }

    public function getManufacturerWarranty(): ?bool
    {
        return $this->manufacturerWarranty;
    }

    public function setMarketCategory(string $marketCategory): static
    {
        $this->marketCategory = $marketCategory;

        return $this;
    }

    public function getMarketCategory(): ?string
    {
        return $this->marketCategory;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setPickup(bool $pickup): static
    {
        $this->pickup = $pickup;

        return $this;
    }

    public function getPickup(): ?bool
    {
        return $this->pickup;
    }

    public function setPicture(string $picture): static
    {
        $this->picture = $picture;

        return $this;
    }

    public function getPicture(): string
    {
        return $this->picture;
    }

    public function setPrice(int|float $price): static
    {
        $this->price = $price;

        return $this;
    }

    public function getPrice(): float
    {
        return $this->price;
    }

    public function setSalesNotes(string $salesNotes): static
    {
        $this->salesNotes = $salesNotes;

        return $this;
    }

    public function getSalesNotes(): ?string
    {
        return $this->salesNotes;
    }

    public function setStore(bool $store): static
    {
        $this->store = $store;

        return $this;
    }

    public function getStore(): bool
    {
        return $this->store;
    }

    public function setUrl(string $url): static
    {
        $this->url = $url;

        return $this;
    }

    public function getUrl(): string
    {
        return $this->url;
    }

    public function setVendor(string $vendor): static
    {
        $this->vendor = $vendor;

        return $this;
    }

    public function getVendor(): string
    {
        return $this->vendor;
    }

    public function setVendorCode(string $vendorCode): static
    {
        $this->vendorCode = $vendorCode;

        return $this;
    }

    public function getVendorCode(): ?string
    {
        return $this->vendorCode;
    }

    public function getOldPrice(): float|int
    {
        return $this->oldPrice;
    }

    public function setOldPrice(float|int $oldPrice): static
    {
        $this->oldPrice = $oldPrice;

        return $this;
    }

    public function getVat(): ?YmlOfferVAT
    {
        return $this->vat;
    }

    public function setVat(?YmlOfferVAT $vat): YmlOffer
    {
        $this->vat = $vat;

        return $this;
    }

    public function isDisabled(): bool
    {
        return $this->disabled;
    }

    public function setDisabled(bool $disabled): static
    {
        $this->disabled = $disabled;

        return $this;
    }

    public function getCount(): ?int
    {
        return $this->count;
    }

    public function setCount(?int $count): static
    {
        $this->count = $count;

        return $this;
    }

    public function getMinQuantity(): ?int
    {
        return $this->minQuantity;
    }

    public function setMinQuantity(?int $minQuantity): static
    {
        $this->minQuantity = $minQuantity;

        return $this;
    }

    public function getStepQuantity(): ?int
    {
        return $this->stepQuantity;
    }

    public function setStepQuantity(?int $stepQuantity): static
    {
        $this->stepQuantity = $stepQuantity;

        return $this;
    }

    public function getDimensions(): ?string
    {
        return $this->dimensions;
    }

    public function setDimensions(string $dimensions): static
    {
        $this->dimensions = $dimensions;

        return $this;
    }

    public function getType(): ?YmlOfferType
    {
        return $this->type;
    }

    public function setType(?YmlOfferType $type): static
    {
        $this->type = $type;

        return $this;
    }

    public function getAdult(): ?bool
    {
        return $this->adult;
    }

    public function setAdult(?bool $adult): static
    {
        $this->adult = $adult;

        return $this;
    }

    public function getDownloadable(): ?bool
    {
        return $this->downloadable;
    }

    public function setDownloadable(?bool $downloadable): static
    {
        $this->downloadable = $downloadable;

        return $this;
    }

    public function getPeriodOfValidityDays(): ?DateInterval
    {
        return $this->periodOfValidityDays;
    }

    public function setPeriodOfValidityDays(?DateInterval $periodOfValidityDays): static
    {
        $this->periodOfValidityDays = $periodOfValidityDays;

        return $this;
    }

    public function getCommentValidityDays(): ?string
    {
        return $this->commentValidityDays;
    }

    public function setCommentValidityDays(?string $commentValidityDays): static
    {
        $this->commentValidityDays = $commentValidityDays;

        return $this;
    }

    public function getServiceLifeDays(): ?DateInterval
    {
        return $this->serviceLifeDays;
    }

    public function setServiceLifeDays(?DateInterval $serviceLifeDays): static
    {
        $this->serviceLifeDays = $serviceLifeDays;

        return $this;
    }

    public function getCommentLifeDays(): ?string
    {
        return $this->commentLifeDays;
    }

    public function setCommentLifeDays(?string $commentLifeDays): static
    {
        $this->commentLifeDays = $commentLifeDays;

        return $this;
    }

    public function getWarrantyDays(): ?DateInterval
    {
        return $this->warrantyDays;
    }

    public function setWarrantyDays(?DateInterval $warrantyDays): static
    {
        $this->warrantyDays = $warrantyDays;

        return $this;
    }

    public function getCommentWarranty(): ?string
    {
        return $this->commentWarranty;
    }

    public function setCommentWarranty(?string $commentWarranty): static
    {
        $this->commentWarranty = $commentWarranty;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getCertificate(): ?string
    {
        return $this->certificate;
    }

    /**
     * @param string|null $certificate
     */
    public function setCertificate(?string $certificate): static
    {
        $this->certificate = $certificate;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getTnVedCode(): ?string
    {
        return $this->tnVedCode;
    }

    /**
     * @param string|null $tnVedCode
     */
    public function setTnVedCode(?string $tnVedCode): static
    {
        $this->tnVedCode = $tnVedCode;

        return $this;
    }

    public function getConditionType(): ?YmlOfferConditionType
    {
        return $this->conditionType;
    }

    public function setCondition(YmlOfferConditionType $conditionType, ?YmlOfferConditionQuality $conditionQuality, ?string $conditionReason): static
    {
        $this->conditionType = $conditionType;
        $this->conditionQuality = $conditionQuality;
        $this->conditionReason = $conditionReason;

        return $this;
    }


    public function getConditionQuality(): ?YmlOfferConditionQuality
    {
        return $this->conditionQuality;
    }

    public function getConditionReason(): ?string
    {
        return $this->conditionReason;
    }

    public function getAgeUnit(): ?YmlOfferAgeUnit
    {
        return $this->ageUnit;
    }

    public function getAge(): ?int
    {
        return $this->age;
    }

    public function setAge(int $age, YmlOfferAgeUnit $unit): static
    {
        $this->age = $age;
        $this->ageUnit = $unit;

        return $this;
    }

    public function getBoxCount(): ?int
    {
        return $this->boxCount;
    }

    public function setBoxCount(int $boxCount): static
    {
        $this->boxCount = $boxCount;

        return $this;
    }

    public function getParams(): ?array
    {
        return $this->params;
    }

    public function addParam(string $name, string $value, ?string $unit): static
    {
        $this->params[] = [$value, $unit];

        return $this;
    }
}
