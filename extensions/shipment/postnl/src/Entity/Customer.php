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
 * Class Customer
 *
 * @package ThirtyBees\PostNL\Entity
 *
 * @method string|null getCustomerNumber()
 * @method string|null getCustomerCode()
 * @method string|null getCollectionLocation()
 * @method string|null getContactPerson()
 * @method string|null getEmail()
 * @method string|null getName()
 * @method string|null getAddress()
 * @method string|null getGlobalPackCustomerCode()
 * @method string|null getGlobalPackBarcodeType()
 *
 * @method Customer setCustomerNumber(string|null $customerNr = null)
 * @method Customer setCustomerCode(string|null $customerCode = null)
 * @method Customer setCollectionLocation(string|null $collectionLocation = null)
 * @method Customer setContactPerson(string|null $contactPerson = null)
 * @method Customer setEmail(string|null $email = null)
 * @method Customer setName(string|null $name = null)
 * @method Customer setAddress(Address|null $address = null)
 * @method Customer setGlobalPackCustomerCode(string|null $code = null)
 * @method Customer setGlobalPackBarcodeType(string|null $type = null)
 */
class Customer extends AbstractEntity
{
    /** @var string[][] $defaultProperties */
    public static $defaultProperties = [
        'Barcode'        => [
            'CustomerCode'   => BarcodeService::DOMAIN_NAMESPACE,
            'CustomerNumber' => BarcodeService::DOMAIN_NAMESPACE,
        ],
        'Confirming'     => [
            'Address'            => ConfirmingService::DOMAIN_NAMESPACE,
            'CollectionLocation' => ConfirmingService::DOMAIN_NAMESPACE,
            'ContactPerson'      => ConfirmingService::DOMAIN_NAMESPACE,
            'CustomerCode'       => ConfirmingService::DOMAIN_NAMESPACE,
            'CustomerNumber'     => ConfirmingService::DOMAIN_NAMESPACE,
            'Email'              => ConfirmingService::DOMAIN_NAMESPACE,
            'Name'               => ConfirmingService::DOMAIN_NAMESPACE,
        ],
        'Labelling'      => [
            'Address'            => LabellingService::DOMAIN_NAMESPACE,
            'CollectionLocation' => LabellingService::DOMAIN_NAMESPACE,
            'ContactPerson'      => LabellingService::DOMAIN_NAMESPACE,
            'CustomerCode'       => LabellingService::DOMAIN_NAMESPACE,
            'CustomerNumber'     => LabellingService::DOMAIN_NAMESPACE,
            'Email'              => LabellingService::DOMAIN_NAMESPACE,
            'Name'               => LabellingService::DOMAIN_NAMESPACE,
        ],
        'ShippingStatus' => [
            'Address'            => ShippingStatusService::DOMAIN_NAMESPACE,
            'CollectionLocation' => ShippingStatusService::DOMAIN_NAMESPACE,
            'ContactPerson'      => ShippingStatusService::DOMAIN_NAMESPACE,
            'CustomerCode'       => ShippingStatusService::DOMAIN_NAMESPACE,
            'CustomerNumber'     => ShippingStatusService::DOMAIN_NAMESPACE,
            'Email'              => ShippingStatusService::DOMAIN_NAMESPACE,
            'Name'               => ShippingStatusService::DOMAIN_NAMESPACE,
        ],
        'DeliveryDate'   => [
            'Address'            => DeliveryDateService::DOMAIN_NAMESPACE,
            'CollectionLocation' => DeliveryDateService::DOMAIN_NAMESPACE,
            'ContactPerson'      => DeliveryDateService::DOMAIN_NAMESPACE,
            'CustomerCode'       => DeliveryDateService::DOMAIN_NAMESPACE,
            'CustomerNumber'     => DeliveryDateService::DOMAIN_NAMESPACE,
            'Email'              => DeliveryDateService::DOMAIN_NAMESPACE,
            'Name'               => DeliveryDateService::DOMAIN_NAMESPACE,
        ],
        'Location'       => [
            'Address'            => LocationService::DOMAIN_NAMESPACE,
            'CollectionLocation' => LocationService::DOMAIN_NAMESPACE,
            'ContactPerson'      => LocationService::DOMAIN_NAMESPACE,
            'CustomerCode'       => LocationService::DOMAIN_NAMESPACE,
            'CustomerNumber'     => LocationService::DOMAIN_NAMESPACE,
            'Email'              => LocationService::DOMAIN_NAMESPACE,
            'Name'               => LocationService::DOMAIN_NAMESPACE,
        ],
        'Timeframe'      => [
            'Address'            => TimeframeService::DOMAIN_NAMESPACE,
            'CollectionLocation' => TimeframeService::DOMAIN_NAMESPACE,
            'ContactPerson'      => TimeframeService::DOMAIN_NAMESPACE,
            'CustomerCode'       => TimeframeService::DOMAIN_NAMESPACE,
            'CustomerNumber'     => TimeframeService::DOMAIN_NAMESPACE,
            'Email'              => TimeframeService::DOMAIN_NAMESPACE,
            'Name'               => TimeframeService::DOMAIN_NAMESPACE,
        ],
    ];
    // @codingStandardsIgnoreStart
    /** @var Address|null $Address */
    protected $Address;
    /** @var string|null $CollectionLocation */
    protected $CollectionLocation;
    /** @var string|null $ContactPerson */
    protected $ContactPerson;
    /** @var string|null $CustomerCode */
    protected $CustomerCode;
    /** @var string|null $CustomerNumber */
    protected $CustomerNumber;
    /** @var null|string $GlobalPackCustomerCode */
    protected $GlobalPackCustomerCode;
    /** @var null|string $GlobalPackBarcodeType */
    protected $GlobalPackBarcodeType;
    /** @var string|null $Email */
    protected $Email;
    /** @var string|null $Name */
    protected $Name;
    // @codingStandardsIgnoreEnd

    /**
     * @param string  $customerNr
     * @param string  $customerCode
     * @param string  $collectionLocation
     * @param string  $contactPerson
     * @param string  $email
     * @param string  $name
     * @param Address $address
     */
    public function __construct(
        $customerNr = null,
        $customerCode = null,
        $collectionLocation = null,
        $contactPerson = null,
        $email = null,
        $name = null,
        Address $address = null
    ) {
        parent::__construct();

        $this->setCustomerNumber($customerNr);
        $this->setCustomerCode($customerCode);
        $this->setCollectionLocation($collectionLocation);
        $this->setContactPerson($contactPerson);
        $this->setEmail($email);
        $this->setName($name);
        $this->setAddress($address);
    }
}
