<?php
/**
 * The MIT License (MIT)
 *
 * Copyright (c) 2017-2018 Thirty Development, LLC
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy of this software and
 * associated documentation files (the "Software"), to deal in the Software without restriction,
 * including without limitation the rights to use, copy, modify, merge, publish, distribute,
 * sublicense, and/or sell copies of the Software, and to permit persons to whom the Software
 * is furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in all copies or
 * substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT
 * NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND
 * NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM,
 * DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
 *
 * @author    Michael Dekker <michael@thirtybees.com>
 * @copyright 2017-2018 Thirty Development, LLC
 * @license   https://opensource.org/licenses/MIT The MIT License
 */

namespace ThirtyBees\PostNL\Entity\Response;

use ThirtyBees\PostNL\Entity\AbstractEntity;
use ThirtyBees\PostNL\Entity\Label;
use ThirtyBees\PostNL\Entity\Warning;
use ThirtyBees\PostNL\Service\BarcodeService;
use ThirtyBees\PostNL\Service\ConfirmingService;
use ThirtyBees\PostNL\Service\DeliveryDateService;
use ThirtyBees\PostNL\Service\LabellingService;
use ThirtyBees\PostNL\Service\LocationService;
use ThirtyBees\PostNL\Service\ShippingStatusService;
use ThirtyBees\PostNL\Service\TimeframeService;

/**
 * Class ResponseShipment
 *
 * @package ThirtyBees\PostNL\Entity
 *
 * @method string|null    getBarcode()
 * @method string|null    getProductCodeDelivery()
 * @method string|null    getDownPartnerBarcode()
 * @method string|null    getDownPartnerId()
 * @method string|null    getDownPartnerLocation()
 * @method Label[]|null   getLabels()
 * @method Warning[]|null getWarnings()
 *
 * @method MergedLabel setBarcode(string|null $barcode = null)
 * @method MergedLabel setProductCodeDelivery(string|null $productCodeDelivery = null)
 * @method MergedLabel setDownPartnerBarcode(string|null $downPartnerCode = null)
 * @method MergedLabel setDownPartnerId(string|null $downPartnerId = null)
 * @method MergedLabel setDownPartnerLocation(string|null $downPartnerLocation = null)
 * @method MergedLabel setLabels(Label[]|null $labels = null)
 * @method MergedLabel setWarnings(Warning[]|null $warnings = null)
 */
class ResponseShipment extends AbstractEntity
{
    /** @var string[][] $defaultProperties */
    public static $defaultProperties = [
        'Barcode'        => [
            'Barcode'             => BarcodeService::DOMAIN_NAMESPACE,
            'DownPartnerBarcode'  => BarcodeService::DOMAIN_NAMESPACE,
            'DownPartnerID'       => BarcodeService::DOMAIN_NAMESPACE,
            'DownPartnerLocation' => BarcodeService::DOMAIN_NAMESPACE,
            'Labels'              => BarcodeService::DOMAIN_NAMESPACE,
            'ProductCodeDelivery' => BarcodeService::DOMAIN_NAMESPACE,
            'Warnings'            => BarcodeService::DOMAIN_NAMESPACE,
        ],
        'Confirming'     => [
            'Barcode'             => ConfirmingService::DOMAIN_NAMESPACE,
            'DownPartnerBarcode'  => ConfirmingService::DOMAIN_NAMESPACE,
            'DownPartnerID'       => ConfirmingService::DOMAIN_NAMESPACE,
            'DownPartnerLocation' => ConfirmingService::DOMAIN_NAMESPACE,
            'Labels'              => ConfirmingService::DOMAIN_NAMESPACE,
            'ProductCodeDelivery' => ConfirmingService::DOMAIN_NAMESPACE,
            'Warnings'            => ConfirmingService::DOMAIN_NAMESPACE,
        ],
        'Labelling'      => [
            'Barcode'             => LabellingService::DOMAIN_NAMESPACE,
            'DownPartnerBarcode'  => LabellingService::DOMAIN_NAMESPACE,
            'DownPartnerID'       => LabellingService::DOMAIN_NAMESPACE,
            'DownPartnerLocation' => LabellingService::DOMAIN_NAMESPACE,
            'Labels'              => LabellingService::DOMAIN_NAMESPACE,
            'ProductCodeDelivery' => LabellingService::DOMAIN_NAMESPACE,
            'Warnings'            => LabellingService::DOMAIN_NAMESPACE,
        ],
        'ShippingStatus' => [
            'Barcode'             => ShippingStatusService::DOMAIN_NAMESPACE,
            'DownPartnerBarcode'  => ShippingStatusService::DOMAIN_NAMESPACE,
            'DownPartnerID'       => ShippingStatusService::DOMAIN_NAMESPACE,
            'DownPartnerLocation' => ShippingStatusService::DOMAIN_NAMESPACE,
            'Labels'              => ShippingStatusService::DOMAIN_NAMESPACE,
            'ProductCodeDelivery' => ShippingStatusService::DOMAIN_NAMESPACE,
            'Warnings'            => ShippingStatusService::DOMAIN_NAMESPACE,
        ],
        'DeliveryDate'   => [
            'Barcode'             => DeliveryDateService::DOMAIN_NAMESPACE,
            'DownPartnerBarcode'  => DeliveryDateService::DOMAIN_NAMESPACE,
            'DownPartnerID'       => DeliveryDateService::DOMAIN_NAMESPACE,
            'DownPartnerLocation' => DeliveryDateService::DOMAIN_NAMESPACE,
            'Labels'              => DeliveryDateService::DOMAIN_NAMESPACE,
            'ProductCodeDelivery' => DeliveryDateService::DOMAIN_NAMESPACE,
            'Warnings'            => DeliveryDateService::DOMAIN_NAMESPACE,
        ],
        'Location'       => [
            'Barcode'             => LocationService::DOMAIN_NAMESPACE,
            'DownPartnerBarcode'  => LocationService::DOMAIN_NAMESPACE,
            'DownPartnerID'       => LocationService::DOMAIN_NAMESPACE,
            'DownPartnerLocation' => LocationService::DOMAIN_NAMESPACE,
            'Labels'              => LocationService::DOMAIN_NAMESPACE,
            'ProductCodeDelivery' => LocationService::DOMAIN_NAMESPACE,
            'Warnings'            => LocationService::DOMAIN_NAMESPACE,
        ],
        'Timeframe'      => [
            'Barcode'             => TimeframeService::DOMAIN_NAMESPACE,
            'DownPartnerBarcode'  => TimeframeService::DOMAIN_NAMESPACE,
            'DownPartnerID'       => TimeframeService::DOMAIN_NAMESPACE,
            'DownPartnerLocation' => TimeframeService::DOMAIN_NAMESPACE,
            'Labels'              => TimeframeService::DOMAIN_NAMESPACE,
            'ProductCodeDelivery' => TimeframeService::DOMAIN_NAMESPACE,
            'Warnings'            => TimeframeService::DOMAIN_NAMESPACE,
        ],
    ];
    // @codingStandardsIgnoreStart
    /** @var string|null $Barcode */
    protected $Barcode;
    /** @var string|null $DownPartnerBarcode */
    protected $DownPartnerBarcode;
    /** @var string|null $DownPartnerID */
    protected $DownPartnerID;
    /** @var string|null $DownPartnerLocation */
    protected $DownPartnerLocation;
    /** @var Label[]|null $Labels */
    protected $Labels;
    /** @var string|null $ProductCodeDelivery */
    protected $ProductCodeDelivery;
    /** @var Warning[]|null $Warnings */
    protected $Warnings;
    // @codingStandardsIgnoreEnd

    /**
     * @param string|null  $barcode
     * @param string|null  $productCodeDelivery
     * @param Label[]|null $labels
     * @param string|null  $downPartnerBarcode
     * @param string|null  $downPartnerId
     * @param string|null  $downPartnerLocation
     * @param array|null   $warnings
     */
    public function __construct(
        $barcode = null,
        $productCodeDelivery = null,
        array $labels = null,
        $downPartnerBarcode = null,
        $downPartnerId = null,
        $downPartnerLocation = null,
        $warnings = null
    ) {
        parent::__construct();

        $this->setBarcode($barcode);
        $this->setProductCodeDelivery($productCodeDelivery);
        $this->setDownPartnerBarcode($downPartnerBarcode);
        $this->setDownPartnerId($downPartnerId);
        $this->setDownPartnerLocation($downPartnerLocation);
        $this->setLabels($labels);
        $this->setWarnings($warnings);
    }
}
