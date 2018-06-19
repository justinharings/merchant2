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
 * Class Confirming
 *
 * @package ThirtyBees\PostNL\Entity
 *
 * @method Customer|null   getCustomer()
 * @method Message|null    getMessage()
 * @method Shipment[]|null getShipments()
 *
 * @method Confirming setCustomer(Customer|null $customer = null)
 * @method Confirming setMessage(Message|null $message = null)
 * @method Confirming setShipments(Shipment[]|null $shipments = null)
 */
class Confirming extends AbstractEntity
{
    /**
     * Default properties and namespaces for the SOAP API
     *
     * @var array $defaultProperties
     */
    public static $defaultProperties = [
        'Barcode'        => [
            'Customer'  => BarcodeService::DOMAIN_NAMESPACE,
            'Message'   => BarcodeService::DOMAIN_NAMESPACE,
            'Shipments' => BarcodeService::DOMAIN_NAMESPACE,
        ],
        'Confirming'     => [
            'Customer'  => ConfirmingService::DOMAIN_NAMESPACE,
            'Message'   => ConfirmingService::DOMAIN_NAMESPACE,
            'Shipments' => ConfirmingService::DOMAIN_NAMESPACE,
        ],
        'Labelling'      => [
            'Customer'  => LabellingService::DOMAIN_NAMESPACE,
            'Message'   => LabellingService::DOMAIN_NAMESPACE,
            'Shipments' => LabellingService::DOMAIN_NAMESPACE,
        ],
        'ShippingStatus' => [
            'Message'   => ShippingStatusService::DOMAIN_NAMESPACE,
            'Customer'  => ShippingStatusService::DOMAIN_NAMESPACE,
            'Shipments' => ShippingStatusService::DOMAIN_NAMESPACE,
        ],
        'DeliveryDate'   => [
            'Message'   => DeliveryDateService::DOMAIN_NAMESPACE,
            'Customer'  => DeliveryDateService::DOMAIN_NAMESPACE,
            'Shipments' => DeliveryDateService::DOMAIN_NAMESPACE,
        ],
        'Location'       => [
            'Message'   => LocationService::DOMAIN_NAMESPACE,
            'Customer'  => LocationService::DOMAIN_NAMESPACE,
            'Shipments' => LocationService::DOMAIN_NAMESPACE,
        ],
        'Timeframe'      => [
            'Message'   => TimeframeService::DOMAIN_NAMESPACE,
            'Customer'  => TimeframeService::DOMAIN_NAMESPACE,
            'Shipments' => TimeframeService::DOMAIN_NAMESPACE,
        ],
    ];
    // @codingStandardsIgnoreStart
    /** @var Customer|null $Customer */
    protected $Customer;
    /** @var Message|null $Message */
    protected $Message;
    /** @var Shipment[]|null $Shipments */
    protected $Shipments;
    // @codingStandardsIgnoreEnd

    /**
     * Confirming constructor.
     *
     * @param Shipment[]|null $shipments
     * @param Customer|null   $customer
     * @param Message|null    $message
     */
    public function __construct(array $shipments = null, Customer $customer = null, Message $message = null)
    {
        parent::__construct();

        $this->setShipments($shipments);
        $this->setMessage($message ?: new Message());
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
            if ($propertyName === 'Shipments') {
                $shipments = [];
                foreach ($this->Shipments as $shipment) {
                    $shipments[] = ["{{$namespace}}Shipment" => $shipment];
                }
                $xml["{{$namespace}}Shipments"] = $shipments;
            } elseif (isset($this->{$propertyName})) {
                $xml[$namespace ? "{{$namespace}}{$propertyName}" : $propertyName] = $this->{$propertyName};
            }
        }
        // Auto extending this object with other properties is not supported with SOAP
        $writer->write($xml);
    }

    /**
     * Return a serializable array for `json_encode`
     *
     * @return array
     */
    public function jsonSerialize()
    {
        $json = [];
        if (!$this->currentService || !in_array($this->currentService, array_keys(static::$defaultProperties))) {
            return $json;
        }

        foreach (array_keys(static::$defaultProperties[$this->currentService]) as $propertyName) {
            if (isset($this->{$propertyName})) {
                // The REST API only seems to accept one shipment per request at the moment of writing (Sep. 24th, 2017)
                if ($propertyName === 'Shipments' && count($this->{$propertyName}) >= 1) {
                    $json[$propertyName] = $this->{$propertyName}[0];
                } else {
                    $json[$propertyName] = $this->{$propertyName};
                }
            }
        }

        return $json;
    }
}
