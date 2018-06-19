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

namespace ThirtyBees\PostNL\Entity;

use ThirtyBees\PostNL\Service\BarcodeService;
use ThirtyBees\PostNL\Service\ConfirmingService;
use ThirtyBees\PostNL\Service\DeliveryDateService;
use ThirtyBees\PostNL\Service\LabellingService;
use ThirtyBees\PostNL\Service\LocationService;
use ThirtyBees\PostNL\Service\ShippingStatusService;
use ThirtyBees\PostNL\Service\TimeframeService;

/**
 * Class Barcode
 *
 * @package ThirtyBees\PostNL\Entity
 *
 * @method string|null getType()
 * @method string|null getRange()
 * @method string|null getSerie()
 *
 * @method Barcode setType(string|null $type = null)
 * @method Barcode setRange(string|null $range = null)
 * @method Barcode setSerie(string|null $serie = null)
 */
class Barcode extends AbstractEntity
{
    /** @var string[][] $defaultProperties */
    public static $defaultProperties = [
        'Barcode'        => [
            'Type'  => BarcodeService::DOMAIN_NAMESPACE,
            'Range' => BarcodeService::DOMAIN_NAMESPACE,
            'Serie' => BarcodeService::DOMAIN_NAMESPACE,
        ],
        'Confirming'     => [
            'Type'  => ConfirmingService::DOMAIN_NAMESPACE,
            'Range' => ConfirmingService::DOMAIN_NAMESPACE,
            'Serie' => ConfirmingService::DOMAIN_NAMESPACE,
        ],
        'Labelling'      => [
            'Type'  => LabellingService::DOMAIN_NAMESPACE,
            'Range' => LabellingService::DOMAIN_NAMESPACE,
            'Serie' => LabellingService::DOMAIN_NAMESPACE,
        ],
        'ShippingStatus' => [
            'Type'  => ShippingStatusService::DOMAIN_NAMESPACE,
            'Range' => ShippingStatusService::DOMAIN_NAMESPACE,
            'Serie' => ShippingStatusService::DOMAIN_NAMESPACE,
        ],
        'DeliveryDate'   => [
            'Type'  => DeliveryDateService::DOMAIN_NAMESPACE,
            'Range' => DeliveryDateService::DOMAIN_NAMESPACE,
            'Serie' => DeliveryDateService::DOMAIN_NAMESPACE,
        ],
        'Location'       => [
            'Type'  => LocationService::DOMAIN_NAMESPACE,
            'Range' => LocationService::DOMAIN_NAMESPACE,
            'Serie' => LocationService::DOMAIN_NAMESPACE,
        ],
        'Timeframe'      => [
            'Type'  => TimeframeService::DOMAIN_NAMESPACE,
            'Range' => TimeframeService::DOMAIN_NAMESPACE,
            'Serie' => TimeframeService::DOMAIN_NAMESPACE,
        ],
    ];
    // @codingStandardsIgnoreStart
    /** @var string|null $Type */
    protected $Type;
    /** @var string|null $Range */
    protected $Range;
    /** @var string|null $Serie */
    protected $Serie;
    // @codingStandardsIgnoreEnd

    /**
     * @param string|null $type
     * @param string|null $range
     * @param string|null $serie
     */
    public function __construct($type = null, $range = null, $serie = '000000000-999999999')
    {
        parent::__construct();

        $this->setType($type);
        $this->setRange($range);
        $this->setSerie($serie);
    }
}
