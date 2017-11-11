<?php
 /**
 * Copyright (c) 2017  arvato Finance B.V.
 *
 * AfterPay reserves all rights in the Program as delivered. The Program
 * or any portion thereof may not be reproduced in any form whatsoever without
 * the written consent of AfterPay.
 *
 * Disclaimer:
 * THIS NOTICE MAY NOT BE REMOVED FROM THE PROGRAM BY ANY USER THEREOF.
 * THE PROGRAM IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS
 * OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL
 * THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE PROGRAM OR THE USE OR OTHER DEALINGS
 * IN THE PROGRAM.
 * 
 * @name        AfterPay Class
 * @author      AfterPay (support@afterpay.nl)
 * @description PHP Library to connect with AfterPay Post Payment services
 * @copyright   Copyright (c) 2017 arvato Finance B.V.
 */


return [
    'nl.afterpay.mercury.soap.exception.AccessDeniedException' => [
        'message' => 'There was an authentication exception while connecting with the AfterPay BE webservice.',
        'description' =>
            'A technical error occurred in the connection with AfterPay, please contact our customer service.'
    ],
    'nl.afterpay.ad3.web.service.impl.exception.AuthenticationException' => [
        'message' => 'There was an authentication exception while connecting with the AfterPay NL webservice.',
        'description' => 
            'A technical error occurred in the connection with AfterPay, please contact our customer service.'
    ],
    [
        'default.message' =>
            'A technical error occurred in the connection with AfterPay, please contact our customer service.'
    ]
];