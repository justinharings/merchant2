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

namespace ThirtyBees\PostNL\Entity\Request;

use Sabre\Xml\Writer;
use ThirtyBees\PostNL\Entity\AbstractEntity;
use ThirtyBees\PostNL\Entity\Customer;
use ThirtyBees\PostNL\Entity\Message\Message;
use ThirtyBees\PostNL\Entity\Shipment;
use ThirtyBees\PostNL\Service\BarcodeService;
use ThirtyBees\PostNL\Service\ConfirmingService;
use ThirtyBees\PostNL\Service\DeliveryDateService;
use ThirtyBees\PostNL\Service\LabellingService;
use ThirtyBees\PostNL\Service\LocationService;
use ThirtyBees\PostNL\Service\ShippingStatusService;
use ThirtyBees\PostNL\Service\TimeframeService;

/**
 * Class CompleteStatusByPhase
 *
 * @package ThirtyBees\PostNL\Entity
 *
 * @method Message|null    getMessage()
 * @method Customer|null   getCustomer()
 * @method Shipment|null   getShipment()
 *
 * @method CompleteStatusByPhase setMessage(Message|null $message = null)
 * @method CompleteStatusByPhase setCustomer(Customer|null $customer = null)
 * @method CompleteStatusByPhase setShipment(Shipment|null $shipment = null)
 */
class CompleteStatusByPhase extends AbstractEntity
{
    /**
     * Default properties and namespaces for the SOAP API
     *
     * @var array $defaultProperties
     */
    public static $defaultProperties = [
        'Barcode'        => [
            'Message'  => BarcodeService::DOMAIN_NAMESPACE,
            'Customer' => BarcodeService::DOMAIN_NAMESPACE,
            'Shipment' => BarcodeService::DOMAIN_NAMESPACE,
        ],
        'Confirming'     => [
            'Message'  => ConfirmingService::DOMAIN_NAMESPACE,
            'Customer' => ConfirmingService::DOMAIN_NAMESPACE,
            'Shipment' => ConfirmingService::DOMAIN_NAMESPACE,
        ],
        'Labelling'      => [
            'Message'  => LabellingService::DOMAIN_NAMESPACE,
            'Customer' => LabellingService::DOMAIN_NAMESPACE,
            'Shipment' => LabellingService::DOMAIN_NAMESPACE,
        ],
        'ShippingStatus' => [
            'Message'  => ShippingStatusService::DOMAIN_NAMESPACE,
            'Customer' => ShippingStatusService::DOMAIN_NAMESPACE,
            'Shipment' => ShippingStatusService::DOMAIN_NAMESPACE,
        ],
        'DeliveryDate'   => [
            'Message'  => DeliveryDateService::DOMAIN_NAMESPACE,
            'Customer' => DeliveryDateService::DOMAIN_NAMESPACE,
            'Shipment' => DeliveryDateService::DOMAIN_NAMESPACE,
        ],
        'Location'       => [
            'Message'  => LocationService::DOMAIN_NAMESPACE,
            'Customer' => LocationService::DOMAIN_NAMESPACE,
            'Shipment' => LocationService::DOMAIN_NAMESPACE,
        ],
        'Timeframe'      => [
            'Message'  => TimeframeService::DOMAIN_NAMESPACE,
            'Customer' => TimeframeService::DOMAIN_NAMESPACE,
            'Shipment' => TimeframeService::DOMAIN_NAMESPACE,
        ],
    ];
    // @codingStandardsIgnoreStart
    /** @var Message|null $Message */
    protected $Message;
    /** @var Customer|null $Customer */
    protected $Customer;
    /** @var Shipment|null $Shipment */
    protected $Shipment;
    // @codingStandardsIgnoreEnd

    /**
     * CompleteStatusByPhase constructor.
     *
     * @param Shipment|null $shipment
     * @param Customer|null $customer
     * @param Message|null  $message
     */
    public function __construct(Shipment $shipment = null, Customer $customer = null, Message $message = null)
    {
        parent::__construct();

        $this->setMessage($message ?: new Message());
        $this->setShipment($shipment);
        $this->setCustomer($customer);
    }

    /**
     * Return a serializable array for the XMLWriter
     *
     * @param Writer $writer
     *
     * @return void
     */
    public function xmlSerialize(Writer $writer)
    {
        $xml = [];
        if (!$this->currentService || !in_array($this->currentService, array_keys(static::$defaultProperties))) {
            $writer->write($xml);

            return;
        }

        foreach (static::$defaultProperties[$this->currentService] as $propertyName => $namespace) {
            if (isset($this->{$propertyName})) {
                $xml[$namespace ? "{{$namespace}}{$propertyName}" : $propertyName] = $this->{$propertyName};
            }
        }
        // Auto extending this object with other properties is not supported with SOAP
        $writer->write($xml);
    }
}
