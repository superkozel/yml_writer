<?php

namespace Superkozel\YmlWriter;

use DateInterval;
use Superkozel\YmlWriter\YmlOffer\YmlOfferAgeUnit;
use Superkozel\YmlWriter\YmlOffer\YmlOfferConditionQuality;
use Superkozel\YmlWriter\YmlOffer\YmlOfferConditionType;
use Superkozel\YmlWriter\YmlOffer\YmlOfferType;
use Superkozel\YmlWriter\YmlOffer\YmlOfferVAT;

class YmlOffer implements YmlOfferWriterInterface
{
    protected int $id;
    protected string $url;
    protected int|float $price;
    protected int|float|null $oldPrice = null;
    protected string $currencyId;
    protected ?YmlOfferVAT $vat = null;
    protected bool $available;
    protected bool $disabled = false;
    protected ?int $count = null;
    protected ?int $minQuantity = null;
    protected ?int $stepQuantity = null;
    protected int $categoryId;
    protected ?string $marketCategory = null;
    protected string $picture;
    protected ?bool $delivery = null;
    protected ?bool $store = null;
    protected ?bool $pickup = null;
    protected float $localDeliveryCost;
    protected string $name;
    protected string $vendor;
    protected ?string $vendorCode = null;
    protected string $description;
    protected ?string $salesNotes = null;
    protected ?string $countryOfOrigin = null;
    protected ?string $barcode = null;
    protected ?string $dimensions = null;
    protected ?YmlOfferType $type = null;
    protected ?bool $adult = null;
    protected ?bool $downloadable = null;
    protected ?DateInterval $periodOfValidityDays = null;
    protected ?string $commentValidityDays = null;
    protected ?DateInterval $serviceLifeDays = null;
    protected ?string $commentLifeDays = null;
    protected ?DateInterval $warrantyDays = null;
    protected ?string $commentWarranty = null;
    protected ?bool $manufacturerWarranty = null;
    protected ?string $certificate = null;
    protected ?string $tnVedCode = null;
    protected ?YmlOfferConditionType $conditionType = null;
    protected ?YmlOfferConditionQuality $conditionQuality = null;
    protected ?string $conditionReason = null;
    protected ?YmlOfferAgeUnit $ageUnit = null;
    protected ?int $age = null;
    protected ?int $boxCount = null;
    protected array $params = [];

    public static function create(): static
    {
        return new static();
    }

    public function write(YmlXmlWriter $writer): void
    {
        $writer->startElement('offer');

        $writer->writeAttribute('id', (string)$this->id);
        $writer->writeElementOptional('available', $this->boolVal($this->available));
        if ($this->disabled === true) {
            $writer->writeElement('disabled', $this->boolVal($this->disabled));
        }
        $writer->writeElement('name', $this->name);
        $writer->startElement('description');
        $writer->writeCdata($this->description);
        $writer->endElement();
        $writer->writeElement('url', $this->url);
        if ($this->getPrice() > 0) {
            $writer->writeElement('price', $this->moneyFormat($this->price));
            if ($this->oldPrice !== null && $this->oldPrice > 0 && $this->oldPrice < $this->price) {
                $writer->writeElement('oldprice', $this->moneyFormat($this->oldPrice));
            }
        }
        $writer->writeElement('currencyId', $this->currencyId);
        $writer->writeElementOptional('vat', $this->vat?->name);
        $writer->writeElement('categoryId', (string)$this->categoryId);
        $writer->writeElement('picture', $this->picture);
        $writer->writeElement('vendor', $this->vendor);
        $writer->writeElementOptional('vendorCode', $this->vendorCode);
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
            $writer->writeElementOptional('quality', $this->conditionQuality?->value);
            $writer->writeElementOptional('reason', $this->conditionReason);
            $writer->endElement();
        }

        if ($this->age !== null && $this->ageUnit !== null) {
            $writer->startElement('age');
            $writer->writeAttribute('unit', $this->ageUnit->value);
            $writer->text((string)$this->age);
            $writer->endElement();
        }
        $writer->writeElementOptional('box-count', $this->boxCount);
        $writer->writeElementOptional('store', $this->boolVal($this->store));
        $writer->writeElementOptional('pickup', $this->boolVal($this->pickup));
        $writer->writeElementOptional('delivery', $this->boolVal($this->delivery));
        $writer->writeElementOptional('sales_notes', $this->salesNotes);
        $writer->writeElementOptional('country_of_origin', $this->countryOfOrigin);
        $writer->writeElementOptional('barcode', $this->barcode);
        $writer->writeElementOptional('dimension', $this->dimensions);
        $writer->writeElementOptional('manufacturer_warranty', $this->boolVal($this->manufacturerWarranty));

        $writer->endElement();
    }

    protected function moneyFormat(int|float $value): string
    {
        return number_format(round($value, 2), 2, ',', '');
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

    public function setCurrencyId(string $currencyId): static
    {
        $this->currencyId = $currencyId;

        return $this;
    }

    public function getCurrencyId(): string
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

    public function getStore(): ?bool
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

    public function getOldPrice(): float|int|null
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
