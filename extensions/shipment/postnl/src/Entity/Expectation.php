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
 * Class Expectation
 *
 * @package ThirtyBees\PostNL\Entity
 *
 * @method string|null getETAFrom()
 * @method string|null getETATo()
 *
 * @method Expectation setETAFrom(string|null $dateTime = null)
 * @method Expectation setETATo(string|null $dateTime = null)
 */
class Expectation extends AbstractEntity
{
    /** @var string[][] $defaultProperties */
    public static $defaultProperties = [
        'Barcode'        => [
            'ETAFrom' => BarcodeService::DOMAIN_NAMESPACE,
            'ETATo'   => BarcodeService::DOMAIN_NAMESPACE,
        ],
        'Confirming'     => [
            'ETAFrom' => ConfirmingService::DOMAIN_NAMESPACE,
            'ETATo'   => ConfirmingService::DOMAIN_NAMESPACE,
        ],
        'Labelling'      => [
            'ETAFrom' => LabellingService::DOMAIN_NAMESPACE,
            'ETATo'   => LabellingService::DOMAIN_NAMESPACE,
        ],
        'ShippingStatus' => [
            'ETAFrom' => ShippingStatusService::DOMAIN_NAMESPACE,
            'ETATo'   => ShippingStatusService::DOMAIN_NAMESPACE,
        ],
        'DeliveryDate'   => [
            'ETAFrom' => DeliveryDateService::DOMAIN_NAMESPACE,
            'ETATo'   => DeliveryDateService::DOMAIN_NAMESPACE,
        ],
        'Location'       => [
            'ETAFrom' => LocationService::DOMAIN_NAMESPACE,
            'ETATo'   => LocationService::DOMAIN_NAMESPACE,
        ],
        'Timeframe'      => [
            'ETAFrom' => TimeframeService::DOMAIN_NAMESPACE,
            'ETATo'   => TimeframeService::DOMAIN_NAMESPACE,
        ],
    ];
    // @codingStandardsIgnoreStart
    /** @var string|null $ETAFrom */
    protected $ETAFrom;
    /** @var string|null $ETATo */
    protected $ETATo;
    // @codingStandardsIgnoreEnd

    /**
     * @param string $from
     * @param string $to
     */
    public function __construct($from = null, $to = null)
    {
        parent::__construct();

        $this->setETAFrom($from);
        $this->setETATo($to);
    }
}
