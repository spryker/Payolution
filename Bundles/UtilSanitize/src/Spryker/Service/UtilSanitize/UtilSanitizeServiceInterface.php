<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */
namespace Spryker\Service\UtilSanitize;

interface UtilSanitizeServiceInterface
{

    /**
     *
     * Specification:
     *  - Escape html
     *
     * @api
     *
     * @param string $text
     * @param bool $double
     * @param null $charset
     *
     * @return string
     */
    public function escapeHtml($text, $double = true, $charset = null);

}
