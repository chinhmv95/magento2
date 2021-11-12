<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Magento\Catalog\Model\Theme\CustomerData;

use Magento\Catalog\Model\Product\ProductFrontendAction\Synchronizer;
use Magento\Framework\App\Config;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Message\ManagerInterface as MessageManager;
use Magento\Theme\CustomerData\MessageServiceInterface;

class MessageService implements MessageServiceInterface
{
    /**
     * @var Config
     */
    private $appConfig;

    /**
     * @var RequestInterface
     */
    private $request;

    /**
     * Manager messages
     *
     * @var MessageManager
     */
    private $messageManager;

    /**
     * Constructor
     *
     * @param Config           $appConfig
     * @param RequestInterface $request
     * @param MessageManager   $messageManager
     */
    public function __construct(
        Config $appConfig,
        RequestInterface $request,
        MessageManager $messageManager
    ) {
        $this->appConfig      = $appConfig;
        $this->request        = $request;
        $this->messageManager = $messageManager;
    }

    /**
     * Verify flag value for synchronize product actions with backend or not
     * @return object
     */
    public function getMessages(): object
    {
        $clearSessionMessages = true;

        if ((bool) $this->appConfig->getValue(Synchronizer::ALLOW_SYNC_WITH_BACKEND_PATH)) {

            $forceNewSectionTimestamp = $this->request->getParam('force_new_section_timestamp') ?? null;

            if ('true' !== $forceNewSectionTimestamp) {
                $clearSessionMessages = false;
            }
        }

        return $this->messageManager->getMessages($clearSessionMessages);
    }
}
