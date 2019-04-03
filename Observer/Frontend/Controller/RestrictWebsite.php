<?php

namespace TwentyCI\WebsiteRestriction\Observer\Frontend\Controller;

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Customer\Model\Context;

class RestrictWebsite implements ObserverInterface
{
    /**
     * @var \Magento\Framework\App\Http\Context
     */
    protected $_context;

    /**
     * @var \Magento\Framework\App\Response\Http
     */
    protected $_response;
    
    /**
     * @var \Magento\Framework\UrlFactory
     */
    protected $_urlFactory;

    /**
     * RestrictWebsite constructor.
     * @param \Magento\Framework\App\Response\Http $response
     * @param \Magento\Framework\UrlFactory $urlFactory
     * @param \Magento\Framework\App\Http\Context $context
     */
    public function __construct(
        \Magento\Framework\App\Response\Http $response,
        \Magento\Framework\UrlFactory $urlFactory,
        \Magento\Framework\App\Http\Context $context
    )
    {
        $this->_response = $response;
        $this->_urlFactory = $urlFactory;
        $this->_context = $context;
    }
 
    /**
     * Execute observer
     *
     * @param \Magento\Framework\Event\Observer $observer
     * @return void
     */
    public function execute(Observer $observer) {

        $allowedRoutes = [
            'customer_account_login',
            'customer_account_loginpost',
            'customer_account_create',
            'customer_account_createpost',
            'customer_account_logoutsuccess',
            'customer_account_confirm',
            'customer_account_confirmation',
            'customer_account_forgotpassword',
            'customer_account_forgotpasswordpost',
            'customer_account_createpassword',
            'customer_account_resetpasswordpost',
            'customer_section_load'
        ];
        $request = $observer->getEvent()->getRequest();
        $isCustomerLoggedIn = $this->_context->getValue(Context::CONTEXT_AUTH);
        $actionFullName = strtolower($request->getFullActionName());
        if (!$isCustomerLoggedIn && !in_array($actionFullName, $allowedRoutes)) {
            $this->_response->setRedirect($this->_urlFactory->create()->getUrl('customer/account/login'));
        }
    }
}
