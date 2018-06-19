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

use Sabre\Xml\Writer;
use ThirtyBees\PostNL\Service\BarcodeService;
use ThirtyBees\PostNL\Service\ConfirmingService;
use ThirtyBees\PostNL\Service\DeliveryDateService;
use ThirtyBees\PostNL\Service\LabellingService;
use ThirtyBees\PostNL\Service\LocationService;
use ThirtyBees\PostNL\Service\ShippingStatusService;
use ThirtyBees\PostNL\Service\TimeframeService;

/**
 * Class Shipment
 *
 * @package ThirtyBees\PostNL\Entity
 *
 * @method Address[]|null       getAddresses()
 * @method string|null          getBarcode()
 * @method int|null             getPhaseCode()
 * @method string|null          getDateFrom()
 * @method string|null          getDateTo()
 * @method Dimension|null       getDimension()
 * @method string|null          getProductCodeDelivery()
 * @method Amount[]|null        getAmounts()
 * @method string|null          getCollectionTimeStampEnd()
 * @method string|null          getCollectionTimeStampStart()
 * @method Contact[]|null       getContacts()
 * @method string|null          getContent()
 * @method string|null          getCostCenter()
 * @method Customer|null        getCustomer()
 * @method string|null          getCustomerOrderNumber()
 * @method Customs|null         getCustoms()
 * @method string|null          getDeliveryAddress()
 * @method string|null          getDeliveryDate()
 * @method string|null          getDownPartnerBarcode()
 * @method string|null          getDownPartnerID()
 * @method string|null          getDownPartnerLocation()
 * @method Event[]|null         getEvents()
 * @method Group[]|null         getGroups()
 * @method string|null          getIDExpiration()
 * @method string|null          getIDNumber()
 * @method string|null          getIDType()
 * @method OldStatus[]|null     getOldStatuses()
 * @method string|null          getProductCodeCollect()
 * @method ProductOption[]|null getProductOptions()
 * @method string|null          getReceiverDateOfBirth()
 * @method string|null          getReference()
 * @method string|null          getReferenceCollect()
 * @method string|null          getRemark()
 * @method string|null          getReturnBarcode()
 * @method string|null          getReturnReference()
 * @method string|null          getStatusCode()
 *
 * @method Shipment setAddresses(Address[]|null $addresses = null)
 * @method Shipment setBarcode(string|null $barcode = null)
 * @method Shipment setDimension(string|null $dimension = null)
 * @method Shipment setProductCodeDelivery(string|null $productCodeDelivery = null)
 * @method Shipment setAmounts(Amount[]|null $amounts = null)
 * @method Shipment setCollectionTimeStampEnd(string|null $value = null)
 * @method Shipment setCollectionTimeStampStart(string|null $value = null)
 * @method Shipment setContacts(Contact[]|null $contact = null)
 * @method Shipment setContent(string|null $content = null)
 * @method Shipment setCostCenter(string|null $costCenter = null)
 * @method Shipment setCustomer(Customer|null $customer = null)
 * @method Shipment setCustomerOrderNumber(string|null $customerOrderNumber = null)
 * @method Shipment setCustoms(Customs|null $customs = null)
 * @method Shipment setPhaseCode(int|null $phaseCode = null)
 * @method Shipment setDateFrom(string|null $date = null)
 * @method Shipment setDateTo(string $date = null)
 * @method Shipment setDeliveryAddress(string|null $deliveryAddress = null)
 * @method Shipment setDeliveryDate(string|null $deliveryDate = null)
 * @method Shipment setDownPartnerBarcode(string|null $downPartnerBarcode = null)
 * @method Shipment setDownPartnerID(string|null $downPartnerID = null)
 * @method Shipment setDownPartnerLocation(string|null $downPartnerLocation = null)
 * @method Shipment setEvents(Event[]|null $events = null)
 * @method Shipment setGroups(Group[]|null $groups = null)
 * @method Shipment setIDExpiration(string|null $idExpiration = null)
 * @method Shipment setIDNumber(string|null $idNumber = null)
 * @method Shipment setIDType(string|null $idType = null)
 * @method Shipment setOldStatuses(OldStatus[]|null $oldStatuses = null)
 * @method Shipment setProductCodeCollect(string|null $productCodeCollect = null)
 * @method Shipment setProductOptions(ProductOption[]|null $productOptions = null)
 * @method Shipment setReceiverDateOfBirth(string|null $receiverDateOfBirth = null)
 * @method Shipment setReference(string|null $reference = null)
 * @method Shipment setReferenceCollect(string|null $referenceCollect = null)
 * @method Shipment setRemark(string|null $remark = null)
 * @method Shipment setReturnBarcode(string|null $returnBarcode = null)
 * @method Shipment setReturnReference(string|null $returnReference = null)
 * @method Shipment setStatusCode(string|null $statusCode = null)
 */
class Shipment extends AbstractEntity
{
    /** @var string[][] $defaultProperties */
    public static $defaultProperties = [
        'Barcode'        => [
            'Addresses'                => BarcodeService::DOMAIN_NAMESPACE,
            'Amounts'                  => BarcodeService::DOMAIN_NAMESPACE,
            'Barcode'                  => BarcodeService::DOMAIN_NAMESPACE,
            'CollectionTimeStampEnd'   => BarcodeService::DOMAIN_NAMESPACE,
            'CollectionTimeStampStart' => BarcodeService::DOMAIN_NAMESPACE,
            'Contacts'                 => BarcodeService::DOMAIN_NAMESPACE,
            'Content'                  => BarcodeService::DOMAIN_NAMESPACE,
            'CostCenter'               => BarcodeService::DOMAIN_NAMESPACE,
            'Customer'                 => BarcodeService::DOMAIN_NAMESPACE,
            'CustomerOrderNumber'      => BarcodeService::DOMAIN_NAMESPACE,
            'Customs'                  => BarcodeService::DOMAIN_NAMESPACE,
            'DeliveryAddress'          => BarcodeService::DOMAIN_NAMESPACE,
            'DeliveryTimeStampStart'   => BarcodeService::DOMAIN_NAMESPACE,
            'DeliveryTimestampEnd'     => BarcodeService::DOMAIN_NAMESPACE,
            'DeliveryDate'             => BarcodeService::DOMAIN_NAMESPACE,
            'Dimension'                => BarcodeService::DOMAIN_NAMESPACE,
            'DownPartnerBarcode'       => BarcodeService::DOMAIN_NAMESPACE,
            'DownPartnerID'            => BarcodeService::DOMAIN_NAMESPACE,
            'DownPartnerLocation'      => BarcodeService::DOMAIN_NAMESPACE,
            'Events'                   => BarcodeService::DOMAIN_NAMESPACE,
            'Groups'                   => BarcodeService::DOMAIN_NAMESPACE,
            'IDExpiration'             => BarcodeService::DOMAIN_NAMESPACE,
            'IDNumber'                 => BarcodeService::DOMAIN_NAMESPACE,
            'IDType'                   => BarcodeService::DOMAIN_NAMESPACE,
            'OldStatuses'              => BarcodeService::DOMAIN_NAMESPACE,
            'PhaseCode'                => BarcodeService::DOMAIN_NAMESPACE,
            'ProductCodeCollect'       => BarcodeService::DOMAIN_NAMESPACE,
            'ProductCodeDelivery'      => BarcodeService::DOMAIN_NAMESPACE,
            'ProductOptions'           => BarcodeService::DOMAIN_NAMESPACE,
            'ReceiverDateOfBirth'      => BarcodeService::DOMAIN_NAMESPACE,
            'Reference'                => BarcodeService::DOMAIN_NAMESPACE,
            'ReferenceCollect'         => BarcodeService::DOMAIN_NAMESPACE,
            'Remark'                   => BarcodeService::DOMAIN_NAMESPACE,
            'ReturnBarcode'            => BarcodeService::DOMAIN_NAMESPACE,
            'ReturnReference'          => BarcodeService::DOMAIN_NAMESPACE,
            'StatusCode'               => BarcodeService::DOMAIN_NAMESPACE,
            'DateFrom'                 => BarcodeService::DOMAIN_NAMESPACE,
            'DateTo'                   => BarcodeService::DOMAIN_NAMESPACE,
        ],
        'Confirming'     => [
            'Addresses'                => ConfirmingService::DOMAIN_NAMESPACE,
            'Amounts'                  => ConfirmingService::DOMAIN_NAMESPACE,
            'Barcode'                  => ConfirmingService::DOMAIN_NAMESPACE,
            'CollectionTimeStampEnd'   => ConfirmingService::DOMAIN_NAMESPACE,
            'CollectionTimeStampStart' => ConfirmingService::DOMAIN_NAMESPACE,
            'Contacts'                 => ConfirmingService::DOMAIN_NAMESPACE,
            'Content'                  => ConfirmingService::DOMAIN_NAMESPACE,
            'CostCenter'               => ConfirmingService::DOMAIN_NAMESPACE,
            'Customer'                 => ConfirmingService::DOMAIN_NAMESPACE,
            'CustomerOrderNumber'      => ConfirmingService::DOMAIN_NAMESPACE,
            'Customs'                  => ConfirmingService::DOMAIN_NAMESPACE,
            'DeliveryAddress'          => ConfirmingService::DOMAIN_NAMESPACE,
            'DeliveryTimestampStart'   => ConfirmingService::DOMAIN_NAMESPACE,
            'DeliveryTimestampEnd'     => ConfirmingService::DOMAIN_NAMESPACE,
            'DeliveryDate'             => ConfirmingService::DOMAIN_NAMESPACE,
            'Dimension'                => ConfirmingService::DOMAIN_NAMESPACE,
            'DownPartnerBarcode'       => ConfirmingService::DOMAIN_NAMESPACE,
            'DownPartnerID'            => ConfirmingService::DOMAIN_NAMESPACE,
            'DownPartnerLocation'      => ConfirmingService::DOMAIN_NAMESPACE,
            'Events'                   => ConfirmingService::DOMAIN_NAMESPACE,
            'Groups'                   => ConfirmingService::DOMAIN_NAMESPACE,
            'IDExpiration'             => ConfirmingService::DOMAIN_NAMESPACE,
            'IDNumber'                 => ConfirmingService::DOMAIN_NAMESPACE,
            'IDType'                   => ConfirmingService::DOMAIN_NAMESPACE,
            'OldStatuses'              => ConfirmingService::DOMAIN_NAMESPACE,
            'PhaseCode'                => ConfirmingService::DOMAIN_NAMESPACE,
            'ProductCodeCollect'       => ConfirmingService::DOMAIN_NAMESPACE,
            'ProductCodeDelivery'      => ConfirmingService::DOMAIN_NAMESPACE,
            'ProductOptions'           => ConfirmingService::DOMAIN_NAMESPACE,
            'ReceiverDateOfBirth'      => ConfirmingService::DOMAIN_NAMESPACE,
            'Reference'                => ConfirmingService::DOMAIN_NAMESPACE,
            'ReferenceCollect'         => ConfirmingService::DOMAIN_NAMESPACE,
            'Remark'                   => ConfirmingService::DOMAIN_NAMESPACE,
            'ReturnBarcode'            => ConfirmingService::DOMAIN_NAMESPACE,
            'ReturnReference'          => ConfirmingService::DOMAIN_NAMESPACE,
            'StatusCode'               => ConfirmingService::DOMAIN_NAMESPACE,
            'DateFrom'                 => ConfirmingService::DOMAIN_NAMESPACE,
            'DateTo'                   => ConfirmingService::DOMAIN_NAMESPACE,
        ],
        'Labelling'      => [
            'Addresses'                => LabellingService::DOMAIN_NAMESPACE,
            'Amounts'                  => LabellingService::DOMAIN_NAMESPACE,
            'Barcode'                  => LabellingService::DOMAIN_NAMESPACE,
            'CollectionTimeStampEnd'   => LabellingService::DOMAIN_NAMESPACE,
            'CollectionTimeStampStart' => LabellingService::DOMAIN_NAMESPACE,
            'Contacts'                 => LabellingService::DOMAIN_NAMESPACE,
            'Content'                  => LabellingService::DOMAIN_NAMESPACE,
            'CostCenter'               => LabellingService::DOMAIN_NAMESPACE,
            'Customer'                 => LabellingService::DOMAIN_NAMESPACE,
            'CustomerOrderNumber'      => LabellingService::DOMAIN_NAMESPACE,
            'Customs'                  => LabellingService::DOMAIN_NAMESPACE,
            'DeliveryAddress'          => LabellingService::DOMAIN_NAMESPACE,
            'DeliveryTimestampStart'   => LabellingService::DOMAIN_NAMESPACE,
            'DeliveryTimestampEnd'     => LabellingService::DOMAIN_NAMESPACE,
            'DeliveryDate'             => LabellingService::DOMAIN_NAMESPACE,
            'Dimension'                => LabellingService::DOMAIN_NAMESPACE,
            'DownPartnerBarcode'       => LabellingService::DOMAIN_NAMESPACE,
            'DownPartnerID'            => LabellingService::DOMAIN_NAMESPACE,
            'DownPartnerLocation'      => LabellingService::DOMAIN_NAMESPACE,
            'Events'                   => LabellingService::DOMAIN_NAMESPACE,
            'Groups'                   => LabellingService::DOMAIN_NAMESPACE,
            'IDExpiration'             => LabellingService::DOMAIN_NAMESPACE,
            'IDNumber'                 => LabellingService::DOMAIN_NAMESPACE,
            'IDType'                   => LabellingService::DOMAIN_NAMESPACE,
            'OldStatuses'              => LabellingService::DOMAIN_NAMESPACE,
            'PhaseCode'                => LabellingService::DOMAIN_NAMESPACE,
            'ProductCodeCollect'       => LabellingService::DOMAIN_NAMESPACE,
            'ProductCodeDelivery'      => LabellingService::DOMAIN_NAMESPACE,
            'ProductOptions'           => LabellingService::DOMAIN_NAMESPACE,
            'ReceiverDateOfBirth'      => LabellingService::DOMAIN_NAMESPACE,
            'Reference'                => LabellingService::DOMAIN_NAMESPACE,
            'ReferenceCollect'         => LabellingService::DOMAIN_NAMESPACE,
            'Remark'                   => LabellingService::DOMAIN_NAMESPACE,
            'ReturnBarcode'            => LabellingService::DOMAIN_NAMESPACE,
            'ReturnReference'          => LabellingService::DOMAIN_NAMESPACE,
            'StatusCode'               => LabellingService::DOMAIN_NAMESPACE,
            'DateFrom'                 => LabellingService::DOMAIN_NAMESPACE,
            'DateTo'                   => LabellingService::DOMAIN_NAMESPACE,
        ],
        'ShippingStatus' => [
            'Addresses'                => ShippingStatusService::DOMAIN_NAMESPACE,
            'Amounts'                  => ShippingStatusService::DOMAIN_NAMESPACE,
            'Barcode'                  => ShippingStatusService::DOMAIN_NAMESPACE,
            'CollectionTimeStampEnd'   => ShippingStatusService::DOMAIN_NAMESPACE,
            'CollectionTimeStampStart' => ShippingStatusService::DOMAIN_NAMESPACE,
            'Contacts'                 => ShippingStatusService::DOMAIN_NAMESPACE,
            'Content'                  => ShippingStatusService::DOMAIN_NAMESPACE,
            'CostCenter'               => ShippingStatusService::DOMAIN_NAMESPACE,
            'Customer'                 => ShippingStatusService::DOMAIN_NAMESPACE,
            'CustomerOrderNumber'      => ShippingStatusService::DOMAIN_NAMESPACE,
            'Customs'                  => ShippingStatusService::DOMAIN_NAMESPACE,
            'DeliveryAddress'          => ShippingStatusService::DOMAIN_NAMESPACE,
            'DeliveryTimestampStart'   => ShippingStatusService::DOMAIN_NAMESPACE,
            'DeliveryTimestampEnd'     => ShippingStatusService::DOMAIN_NAMESPACE,
            'DeliveryDate'             => ShippingStatusService::DOMAIN_NAMESPACE,
            'Dimension'                => ShippingStatusService::DOMAIN_NAMESPACE,
            'DownPartnerBarcode'       => ShippingStatusService::DOMAIN_NAMESPACE,
            'DownPartnerID'            => ShippingStatusService::DOMAIN_NAMESPACE,
            'DownPartnerLocation'      => ShippingStatusService::DOMAIN_NAMESPACE,
            'Events'                   => ShippingStatusService::DOMAIN_NAMESPACE,
            'Groups'                   => ShippingStatusService::DOMAIN_NAMESPACE,
            'IDExpiration'             => ShippingStatusService::DOMAIN_NAMESPACE,
            'IDNumber'                 => ShippingStatusService::DOMAIN_NAMESPACE,
            'IDType'                   => ShippingStatusService::DOMAIN_NAMESPACE,
            'OldStatuses'              => ShippingStatusService::DOMAIN_NAMESPACE,
            'PhaseCode'                => ShippingStatusService::DOMAIN_NAMESPACE,
            'ProductCodeCollect'       => ShippingStatusService::DOMAIN_NAMESPACE,
            'ProductCodeDelivery'      => ShippingStatusService::DOMAIN_NAMESPACE,
            'ProductOptions'           => ShippingStatusService::DOMAIN_NAMESPACE,
            'ReceiverDateOfBirth'      => ShippingStatusService::DOMAIN_NAMESPACE,
            'Reference'                => ShippingStatusService::DOMAIN_NAMESPACE,
            'ReferenceCollect'         => ShippingStatusService::DOMAIN_NAMESPACE,
            'Remark'                   => ShippingStatusService::DOMAIN_NAMESPACE,
            'ReturnBarcode'            => ShippingStatusService::DOMAIN_NAMESPACE,
            'ReturnReference'          => ShippingStatusService::DOMAIN_NAMESPACE,
            'StatusCode'               => ShippingStatusService::DOMAIN_NAMESPACE,
            'DateFrom'                 => ShippingStatusService::DOMAIN_NAMESPACE,
            'DateTo'                   => ShippingStatusService::DOMAIN_NAMESPACE,
        ],
        'DeliveryDate'   => [
            'Addresses'                => DeliveryDateService::DOMAIN_NAMESPACE,
            'Amounts'                  => DeliveryDateService::DOMAIN_NAMESPACE,
            'Barcode'                  => DeliveryDateService::DOMAIN_NAMESPACE,
            'CollectionTimeStampEnd'   => DeliveryDateService::DOMAIN_NAMESPACE,
            'CollectionTimeStampStart' => DeliveryDateService::DOMAIN_NAMESPACE,
            'Contacts'                 => DeliveryDateService::DOMAIN_NAMESPACE,
            'Content'                  => DeliveryDateService::DOMAIN_NAMESPACE,
            'CostCenter'               => DeliveryDateService::DOMAIN_NAMESPACE,
            'Customer'                 => DeliveryDateService::DOMAIN_NAMESPACE,
            'CustomerOrderNumber'      => DeliveryDateService::DOMAIN_NAMESPACE,
            'Customs'                  => DeliveryDateService::DOMAIN_NAMESPACE,
            'DeliveryAddress'          => DeliveryDateService::DOMAIN_NAMESPACE,
            'DeliveryTimestampStart'   => DeliveryDateService::DOMAIN_NAMESPACE,
            'DeliveryTimestampEnd'     => DeliveryDateService::DOMAIN_NAMESPACE,
            'DeliveryDate'             => DeliveryDateService::DOMAIN_NAMESPACE,
            'Dimension'                => DeliveryDateService::DOMAIN_NAMESPACE,
            'DownPartnerBarcode'       => DeliveryDateService::DOMAIN_NAMESPACE,
            'DownPartnerID'            => DeliveryDateService::DOMAIN_NAMESPACE,
            'DownPartnerLocation'      => DeliveryDateService::DOMAIN_NAMESPACE,
            'Events'                   => DeliveryDateService::DOMAIN_NAMESPACE,
            'Groups'                   => DeliveryDateService::DOMAIN_NAMESPACE,
            'IDExpiration'             => DeliveryDateService::DOMAIN_NAMESPACE,
            'IDNumber'                 => DeliveryDateService::DOMAIN_NAMESPACE,
            'IDType'                   => DeliveryDateService::DOMAIN_NAMESPACE,
            'OldStatuses'              => DeliveryDateService::DOMAIN_NAMESPACE,
            'PhaseCode'                => DeliveryDateService::DOMAIN_NAMESPACE,
            'ProductCodeCollect'       => DeliveryDateService::DOMAIN_NAMESPACE,
            'ProductCodeDelivery'      => DeliveryDateService::DOMAIN_NAMESPACE,
            'ProductOptions'           => DeliveryDateService::DOMAIN_NAMESPACE,
            'ReceiverDateOfBirth'      => DeliveryDateService::DOMAIN_NAMESPACE,
            'Reference'                => DeliveryDateService::DOMAIN_NAMESPACE,
            'ReferenceCollect'         => DeliveryDateService::DOMAIN_NAMESPACE,
            'Remark'                   => DeliveryDateService::DOMAIN_NAMESPACE,
            'ReturnBarcode'            => DeliveryDateService::DOMAIN_NAMESPACE,
            'ReturnReference'          => DeliveryDateService::DOMAIN_NAMESPACE,
            'StatusCode'               => DeliveryDateService::DOMAIN_NAMESPACE,
            'DateFrom'                 => DeliveryDateService::DOMAIN_NAMESPACE,
            'DateTo'                   => DeliveryDateService::DOMAIN_NAMESPACE,
        ],
        'Location'       => [
            'Addresses'                => LocationService::DOMAIN_NAMESPACE,
            'Amounts'                  => LocationService::DOMAIN_NAMESPACE,
            'Barcode'                  => LocationService::DOMAIN_NAMESPACE,
            'CollectionTimeStampEnd'   => LocationService::DOMAIN_NAMESPACE,
            'CollectionTimeStampStart' => LocationService::DOMAIN_NAMESPACE,
            'Contacts'                 => LocationService::DOMAIN_NAMESPACE,
            'Content'                  => LocationService::DOMAIN_NAMESPACE,
            'CostCenter'               => LocationService::DOMAIN_NAMESPACE,
            'Customer'                 => LocationService::DOMAIN_NAMESPACE,
            'CustomerOrderNumber'      => LocationService::DOMAIN_NAMESPACE,
            'Customs'                  => LocationService::DOMAIN_NAMESPACE,
            'DeliveryAddress'          => LocationService::DOMAIN_NAMESPACE,
            'DeliveryTimestampStart'   => LocationService::DOMAIN_NAMESPACE,
            'DeliveryTimestampEnd'     => LocationService::DOMAIN_NAMESPACE,
            'DeliveryDate'             => LocationService::DOMAIN_NAMESPACE,
            'Dimension'                => LocationService::DOMAIN_NAMESPACE,
            'DownPartnerBarcode'       => LocationService::DOMAIN_NAMESPACE,
            'DownPartnerID'            => LocationService::DOMAIN_NAMESPACE,
            'DownPartnerLocation'      => LocationService::DOMAIN_NAMESPACE,
            'Events'                   => LocationService::DOMAIN_NAMESPACE,
            'Groups'                   => LocationService::DOMAIN_NAMESPACE,
            'IDExpiration'             => LocationService::DOMAIN_NAMESPACE,
            'IDNumber'                 => LocationService::DOMAIN_NAMESPACE,
            'IDType'                   => LocationService::DOMAIN_NAMESPACE,
            'OldStatuses'              => LocationService::DOMAIN_NAMESPACE,
            'PhaseCode'                => LocationService::DOMAIN_NAMESPACE,
            'ProductCodeCollect'       => LocationService::DOMAIN_NAMESPACE,
            'ProductCodeDelivery'      => LocationService::DOMAIN_NAMESPACE,
            'ProductOptions'           => LocationService::DOMAIN_NAMESPACE,
            'ReceiverDateOfBirth'      => LocationService::DOMAIN_NAMESPACE,
            'Reference'                => LocationService::DOMAIN_NAMESPACE,
            'ReferenceCollect'         => LocationService::DOMAIN_NAMESPACE,
            'Remark'                   => LocationService::DOMAIN_NAMESPACE,
            'ReturnBarcode'            => LocationService::DOMAIN_NAMESPACE,
            'ReturnReference'          => LocationService::DOMAIN_NAMESPACE,
            'StatusCode'               => LocationService::DOMAIN_NAMESPACE,
            'DateFrom'                 => LocationService::DOMAIN_NAMESPACE,
            'DateTo'                   => LocationService::DOMAIN_NAMESPACE,
        ],
        'Timeframe'      => [
            'Addresses'                => TimeframeService::DOMAIN_NAMESPACE,
            'Amounts'                  => TimeframeService::DOMAIN_NAMESPACE,
            'Barcode'                  => TimeframeService::DOMAIN_NAMESPACE,
            'CollectionTimeStampEnd'   => TimeframeService::DOMAIN_NAMESPACE,
            'CollectionTimeStampStart' => TimeframeService::DOMAIN_NAMESPACE,
            'Contacts'                 => TimeframeService::DOMAIN_NAMESPACE,
            'Content'                  => TimeframeService::DOMAIN_NAMESPACE,
            'CostCenter'               => TimeframeService::DOMAIN_NAMESPACE,
            'Customer'                 => TimeframeService::DOMAIN_NAMESPACE,
            'CustomerOrderNumber'      => TimeframeService::DOMAIN_NAMESPACE,
            'Customs'                  => TimeframeService::DOMAIN_NAMESPACE,
            'DeliveryAddress'          => TimeframeService::DOMAIN_NAMESPACE,
            'DeliveryTimestampStart'   => TimeframeService::DOMAIN_NAMESPACE,
            'DeliveryTimestampEnd'     => TimeframeService::DOMAIN_NAMESPACE,
            'DeliveryDate'             => TimeframeService::DOMAIN_NAMESPACE,
            'Dimension'                => TimeframeService::DOMAIN_NAMESPACE,
            'DownPartnerBarcode'       => TimeframeService::DOMAIN_NAMESPACE,
            'DownPartnerID'            => TimeframeService::DOMAIN_NAMESPACE,
            'DownPartnerLocation'      => TimeframeService::DOMAIN_NAMESPACE,
            'Events'                   => TimeframeService::DOMAIN_NAMESPACE,
            'Groups'                   => TimeframeService::DOMAIN_NAMESPACE,
            'IDExpiration'             => TimeframeService::DOMAIN_NAMESPACE,
            'IDNumber'                 => TimeframeService::DOMAIN_NAMESPACE,
            'IDType'                   => TimeframeService::DOMAIN_NAMESPACE,
            'OldStatuses'              => TimeframeService::DOMAIN_NAMESPACE,
            'PhaseCode'                => TimeframeService::DOMAIN_NAMESPACE,
            'ProductCodeCollect'       => TimeframeService::DOMAIN_NAMESPACE,
            'ProductCodeDelivery'      => TimeframeService::DOMAIN_NAMESPACE,
            'ProductOptions'           => TimeframeService::DOMAIN_NAMESPACE,
            'ReceiverDateOfBirth'      => TimeframeService::DOMAIN_NAMESPACE,
            'Reference'                => TimeframeService::DOMAIN_NAMESPACE,
            'ReferenceCollect'         => TimeframeService::DOMAIN_NAMESPACE,
            'Remark'                   => TimeframeService::DOMAIN_NAMESPACE,
            'ReturnBarcode'            => TimeframeService::DOMAIN_NAMESPACE,
            'ReturnReference'          => TimeframeService::DOMAIN_NAMESPACE,
            'StatusCode'               => TimeframeService::DOMAIN_NAMESPACE,
            'DateFrom'                 => TimeframeService::DOMAIN_NAMESPACE,
            'DateTo'                   => TimeframeService::DOMAIN_NAMESPACE,
        ],
    ];
    // @codingStandardsIgnoreStart
    /** @var Address[]|null $Addresses */
    protected $Addresses;
    /** @var Amount[]|null $Amounts */
    protected $Amounts;
    /** @var string|null $Barcode */
    protected $Barcode;
    /** @var string|null $CollectionTimeStampEnd */
    protected $CollectionTimeStampEnd;
    /** @var string|null $CollectionTimeStampStart */
    protected $CollectionTimeStampStart;
    /** @var Contact[]|null $Contacts */
    protected $Contacts;
    /** @var string|null $Content */
    protected $Content;
    /** @var string|null $CostCenter */
    protected $CostCenter;
    /** @var string|null $CustomerOrderNumber */
    protected $CustomerOrderNumber;
    /** @var Customer|null $Customer */
    protected $Customer;
    /** @var Customs|null $Customs */
    protected $Customs;
    /** @var string |null$StatusCode */
    protected $StatusCode;
    /** @var int|null $PhaseCode */
    protected $PhaseCode;
    /** @var string|null $DateFrom */
    protected $DateFrom;
    /** @var string|null $DateTo */
    protected $DateTo;
    /** @var string|null $DeliveryAddress */
    protected $DeliveryAddress;
    /** @var string|null $DeliveryTimeStampStart */
    protected $DeliveryTimeStampStart;
    /** @var string|null $DeliveryTimeStampEnd */
    protected $DeliveryTimeStampEnd;
    /** @var string|null $DeliveryDate */
    protected $DeliveryDate;
    /** @var Dimension|null $Dimension */
    protected $Dimension;
    /** @var string|null $DownPartnerBarcode */
    protected $DownPartnerBarcode;
    /** @var string|null $DownPartnerID */
    protected $DownPartnerID;
    /** @var string|null $DownPartnerLocation */
    protected $DownPartnerLocation;
    /** @var Event[]|null $Events */
    protected $Events;
    /** @var Group[]|null $Groups */
    protected $Groups;
    /** @var string|null $IDExpiration */
    protected $IDExpiration;
    /** @var string|null $IDNumber */
    protected $IDNumber;
    /** @var string|null $IDType */
    protected $IDType;
    /** @var string|null $OldStatuses */
    protected $OldStatuses;
    /** @var string|null $ProductCodeCollect */
    protected $ProductCodeCollect;
    /** @var string|null $ProductCodeDelivery */
    protected $ProductCodeDelivery;
    /** @var ProductOption[]|null $ProductOptions */
    protected $ProductOptions;
    /** @var string|null $ReceiverDateOfBirth */
    protected $ReceiverDateOfBirth;
    /** @var string|null $Reference */
    protected $Reference;
    /** @var string|null $ReferenceCollect */
    protected $ReferenceCollect;
    /** @var string|null $Remark */
    protected $Remark;
    /** @var string|null $ReturnBarcode */
    protected $ReturnBarcode;
    /** @var string|null $ReturnReference */
    protected $ReturnReference;
    // @codingStandardsIgnoreEnd

    /**
     * Shipment constructor.
     *
     * @param Address[]|null       $addresses
     * @param array|null           $amounts
     * @param string|null          $barcode
     * @param Contact[]|null       $contacts
     * @param string|null          $content
     * @param string|null          $collectionTimeStampEnd
     * @param string|null          $collectionTimeStampStart
     * @param string|null          $costCenter
     * @param Customer|null        $customer
     * @param string|null          $customerOrderNumber
     * @param Customs|null         $customs
     * @param string|null          $deliveryAddress
     * @param string|null          $deliveryDate
     * @param Dimension|null       $dimension
     * @param string|null          $downPartnerBarcode
     * @param string|null          $downPartnerId
     * @param string|null          $downPartnerLocation
     * @param Event[]|null         $events
     * @param Group[]|null         $groups
     * @param string|null          $idExpiration
     * @param string|null          $idNumber
     * @param string|null          $idType
     * @param array|null           $oldStatuses
     * @param string|null          $productCodeCollect
     * @param string|null          $productCodeDelivery
     * @param ProductOption[]|null $productOptions
     * @param string|null          $receiverDateOfBirth
     * @param string|null          $reference
     * @param string|null          $referenceCollect
     * @param string|null          $remark
     * @param string|null          $returnBarcode
     * @param string|null          $returnReference
     * @param string|null          $statusCode
     * @param int|null             $phaseCode
     * @param string|null          $dateFrom
     * @param string|null          $dateTo
     */
    public function __construct(
        array $addresses = null,
        array $amounts = null,
        $barcode = null,
        array $contacts = null,
        $content = null,
        $collectionTimeStampEnd = null,
        $collectionTimeStampStart = null,
        $costCenter = null,
        $customer = null,
        $customerOrderNumber = null,
        Customs $customs = null,
        $deliveryAddress = null,
        $deliveryDate = null,
        Dimension $dimension = null,
        $downPartnerBarcode = null,
        $downPartnerId = null,
        $downPartnerLocation = null,
        array $events = null,
        array $groups = null,
        $idExpiration = null,
        $idNumber = null,
        $idType = null,
        array $oldStatuses = null,
        $productCodeCollect = null,
        $productCodeDelivery = null,
        array $productOptions = null,
        $receiverDateOfBirth = null,
        $reference = null,
        $referenceCollect = null,
        $remark = null,
        $returnBarcode = null,
        $returnReference = null,
        $statusCode = null,
        $phaseCode = null,
        $dateFrom = null,
        $dateTo = null
    ) {
        parent::__construct();

        $this->setAddresses($addresses);
        $this->setAmounts($amounts);
        $this->setBarcode($barcode);
        $this->setCollectionTimeStampEnd($collectionTimeStampEnd);
        $this->setCollectionTimeStampStart($collectionTimeStampStart);
        $this->setContacts($contacts);
        $this->setContent($content);
        $this->setCostCenter($costCenter);
        $this->setCustomer($customer);
        $this->setCustomerOrderNumber($customerOrderNumber);
        $this->setCustoms($customs);
        $this->setDeliveryAddress($deliveryAddress);
        $this->setDeliveryDate($deliveryDate);
        $this->setDimension($dimension);
        $this->setDownPartnerBarcode($downPartnerBarcode);
        $this->setDownPartnerID($downPartnerId);
        $this->setDownPartnerLocation($downPartnerLocation);
        $this->setEvents($events);
        $this->setGroups($groups);
        $this->setIDExpiration($idExpiration);
        $this->setIDNumber($idNumber);
        $this->setIDType($idType);
        $this->setOldStatuses($oldStatuses);
        $this->setProductCodeCollect($productCodeCollect);
        $this->setProductCodeDelivery($productCodeDelivery);
        $this->setProductOptions($productOptions);
        $this->setReceiverDateOfBirth($receiverDateOfBirth);
        $this->setReference($reference);
        $this->setReferenceCollect($referenceCollect);
        $this->setRemark($remark);
        $this->setReturnBarcode($returnBarcode);
        $this->setReturnReference($returnReference);
        $this->setStatusCode($statusCode);
        $this->setPhaseCode($phaseCode);
        $this->setDateFrom($dateFrom);
        $this->setDateTo($dateTo);
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
        foreach (static::$defaultProperties[$this->currentService] as $propertyName => $namespace) {
            if ($propertyName === 'Addresses') {
                if (is_array($this->Addresses)) {
                    $items = [];
                    foreach ($this->Addresses as $address) {
                        $items[] = ["{{$namespace}}Address" => $address];
                    }
                    $xml["{{$namespace}}Addresses"] = $items;
                }
            } elseif ($propertyName === 'Amounts') {
                if (is_array($this->Amounts)) {
                    $items = [];
                    foreach ($this->Amounts as $amount) {
                        $items[] = ["{{$namespace}}Amount" => $amount];
                    }
                    $xml["{{$namespace}}Amounts"] = $items;
                }
            } elseif ($propertyName === 'Contacts') {
                if (is_array($this->Contacts)) {
                    $items = [];
                    foreach ($this->Contacts as $contact) {
                        $items[] = ["{{$namespace}}Contact" => $contact];
                    }
                    $xml["{{$namespace}}Contacts"] = $items;
                }
            } elseif ($propertyName === 'Events') {
                if (is_array($this->Events)) {
                    $items = [];
                    foreach ($this->Events as $event) {
                        $items[] = ["{{$namespace}}Event" => $event];
                    }
                    $xml["{{$namespace}}Events"] = $items;
                }
            } elseif ($propertyName === 'Groups') {
                if (is_array($this->Groups)) {
                    $items = [];
                    foreach ($this->Groups as $group) {
                        $items[] = ["{{$namespace}}Group" => $group];
                    }
                    $xml["{{$namespace}}Groups"] = $items;
                }
            }
//            elseif ($propertyName === 'OldStatuses') {
//                if (is_array($this->OldStatuses)) {
//                    $items = [];
//                    foreach ($this->OldStatuses as $option) {
//                        $items[] = ["{{$namespace}}OldStatus" => $option];
//                    }
//                    $xml["{{$namespace}}OldStatuses"] = $items;
//                }
//            }
            elseif ($propertyName === 'ProductOptions') {
                if (is_array($this->ProductOptions)) {
                    $items = [];
                    foreach ($this->ProductOptions as $option) {
                        $items[] = ["{{$namespace}}ProductOption" => $option];
                    }
                    $xml["{{$namespace}}ProductOptions"] = $items;
                }
            } elseif (isset($this->{$propertyName})) {
                $xml[$namespace ? "{{$namespace}}{$propertyName}" : $propertyName] = $this->{$propertyName};
            }
        }
        // Auto extending this object with other properties is not supported with SOAP
        $writer->write($xml);
    }
}
