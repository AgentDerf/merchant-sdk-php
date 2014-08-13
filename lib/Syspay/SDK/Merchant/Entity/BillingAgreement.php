<?php

/**
 * A billing agreement object
 */
class Syspay_Merchant_Entity_BillingAgreement extends Syspay_Merchant_Entity implements
    Syspay_Merchant_Entity_ReturnedEntityInterface
{
    const TYPE = 'billing_agreement';

    // First payment not yet successful
    const STATUS_PENDING   = 'PENDING';
    // Active billing agreement, the merchant can do rebills
    const STATUS_ACTIVE    = 'ACTIVE';
    // The first payment failed, (or has been cancelled by the user) the billing agreement is cancelled
    const STATUS_CANCELLED = 'CANCELLED';
    // Billing agreement has ended, the merchant cannot do rebills anymore
    const STATUS_ENDED     = 'ENDED';

    // Merchant stopped the billing agreement via his interface or via the API
    const END_REASON_UNSUBSCRIBED_MERCHANT = 'UNSUBSCRIBED_MERCHANT';
    // Admin stopped the billing agreement via the admin interface
    const END_REASON_UNSUBSCRIBED_ADMIN    = 'UNSUBSCRIBED_ADMIN';
    // Payment method has expired
    const END_REASON_SUSPENDED_EXPIRED     = 'SUSPENDED_EXPIRED';
    // A chargeback was received on this billing agreement
    const END_REASON_SUSPENDED_CHARGEBACK  = 'SUSPENDED_CHARGEBACK';

    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $status;

    /**
     * @var string
     */
    private $currency;

    /**
     * @var string
     */
    private $extra;

    /**
     * @var string
     */
    private $endReason;

    /**
     * @var Syspay_Merchant_Entity_PaymentMethod
     */
    private $payment_method;

    /**
     * @var Syspay_Merchant_Entity_Customer
     */
    private $customer;

    /**
     * @var int
     */
    private $expirationDate;

    /**
     * @var string
     */
    private $redirect;

    /**
     * @var string
     */
    protected $description;

    /**
     * Gets the value of id.
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Sets the value of id.
     *
     * @param integer $id the id
     *
     * @return self
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Gets the value of status.
     *
     * @return string
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Sets the value of status.
     *
     * @param string $status the status
     *
     * @return self
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Gets the value of currency.
     *
     * @return string
     */
    public function getCurrency()
    {
        return $this->currency;
    }

    /**
     * Sets the value of currency.
     *
     * @param string $currency the currency
     *
     * @return self
     */
    public function setCurrency($currency)
    {
        $this->currency = $currency;

        return $this;
    }

    /**
     * Gets the value of extra.
     *
     * @return string
     */
    public function getExtra()
    {
        return $this->extra;
    }

    /**
     * Sets the value of extra.
     *
     * @param string $extra the extra
     *
     * @return self
     */
    public function setExtra($extra)
    {
        $this->extra = $extra;

        return $this;
    }


    /**
     * Build a billing agreement entity based on a json-decoded billing agreement stdClass
     *
     * @param  stdClass $response The billing agreement data
     * @return Syspay_Merchant_Entity_BillingAgreement The billing agreement object
     */
    public static function buildFromResponse(stdClass $response)
    {
        $billingAgreement = new self();
        $billingAgreement->setId(isset($response->id)?$response->id:null);
        $billingAgreement->setStatus(isset($response->status)?$response->status:null);
        $billingAgreement->setCurrency(isset($response->currency)?$response->currency:null);
        $billingAgreement->setExtra(isset($response->extra)?$response->extra:null);
        $billingAgreement->setEndReason(isset($response->end_reason)?$response->end_reason:null);

        if (isset($response->expiration_date)
                && !is_null($response->expiration_date)) {
            $billingAgreement->setExpirationDate(Syspay_Merchant_Utils::tsToDateTime($response->expiration_date));
        }

        if (isset($response->payment_method)
                && ($response->payment_method instanceof stdClass)) {
            $paymentMethod = Syspay_Merchant_Entity_PaymentMethod::buildFromResponse($response->payment_method);
            $billingAgreement->setPaymentMethod($paymentMethod);
        }

        if (isset($response->customer)
                && ($response->customer instanceof stdClass)) {
            $customer = Syspay_Merchant_Entity_Customer::buildFromResponse($response->customer);
            $billingAgreement->setCustomer($customer);
        }

        $billingAgreement->raw = $response;

        return $billingAgreement;
    }

    /**
     * Gets the value of endReason.
     *
     * @return string
     */
    public function getEndReason()
    {
        return $this->endReason;
    }

    /**
     * Sets the value of endReason.
     *
     * @param string $endReason the endReason
     *
     * @return self
     */
    public function setEndReason($endReason)
    {
        $this->endReason = $endReason;

        return $this;
    }

    /**
     * Gets the value of customer.
     *
     * @return Syspay_Merchant_Entity_Customer
     */
    public function getCustomer()
    {
        return $this->customer;
    }

    /**
     * Sets the value of customer.
     *
     * @param Syspay_Merchant_Entity_Customer $customer the customer
     *
     * @return self
     */
    public function setCustomer(Syspay_Merchant_Entity_Customer $customer)
    {
        $this->customer = $customer;

        return $this;
    }

    /**
     * Gets the value of payment_method.
     *
     * @return Syspay_Merchant_Entity_PaymentMethod
     */
    public function getPaymentMethod()
    {
        return $this->payment_method;
    }

    /**
     * Sets the value of payment_method.
     *
     * @param Syspay_Merchant_Entity_PaymentMethod $payment_method the payment_method
     *
     * @return self
     */
    public function setPaymentMethod(Syspay_Merchant_Entity_PaymentMethod $payment_method)
    {
        $this->payment_method = $payment_method;

        return $this;
    }

    /**
     * Gets the value of expirationDate.
     *
     * @return DateTime
     */
    public function getExpirationDate()
    {
        return $this->expirationDate;
    }

    /**
     * Sets the value of expirationDate.
     *
     * @param DateTime $expirationDate the expirationDate
     *
     * @return self
     */
    public function setExpirationDate(DateTime $expirationDate)
    {
        $this->expirationDate = $expirationDate;

        return $this;
    }

    /**
    * Gets the value of redirect.
    * @return string
    */
    public function getRedirect()
    {
        return $this->redirect;
    }

    /**
    * Sets the value of redirect.
    * @param string $redirect the redirect
    *
    * @return self
    */
    public function setRedirect($redirect)
    {
        $this->redirect = $redirect;
    }

    /**
     * Gets the value of description.
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Sets the value of description.
     *
     * @param string $description the description
     *
     * @return self
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }
}
