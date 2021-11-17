<?php

namespace Codeception\Util;

/**
 * Class containing constants of HTTP Status Codes
 * and method to print HTTP code with its description.
 *
 * Usage:
 *
 * ```php
 * <?php
 * use \Codeception\Util\HttpCode;
 *
 * // using REST, PhpBrowser, or any Framework module
 * $I->seeResponseCodeIs(HttpCode::OK);
 * $I->dontSeeResponseCodeIs(HttpCode::NOT_FOUND);
 * ```
 *
 *
 */
class HttpCode
{
    // const CONTINUE = 100;
    /**
     * @var int
     */
    const SWITCHING_PROTOCOLS = 101;
    /**
     * @var int
     */
    const PROCESSING = 102;            // RFC2518
    /**
     * @var int
     */
    const EARLY_HINTS = 103;           // RFC8297
    /**
     * @var int
     */
    const OK = 200;
    /**
     * @var int
     */
    const CREATED = 201;
    /**
     * @var int
     */
    const ACCEPTED = 202;
    /**
     * @var int
     */
    const NON_AUTHORITATIVE_INFORMATION = 203;
    /**
     * @var int
     */
    const NO_CONTENT = 204;
    /**
     * @var int
     */
    const RESET_CONTENT = 205;
    /**
     * @var int
     */
    const PARTIAL_CONTENT = 206;
    /**
     * @var int
     */
    const MULTI_STATUS = 207;          // RFC4918
    /**
     * @var int
     */
    const ALREADY_REPORTED = 208;      // RFC5842
    /**
     * @var int
     */
    const IM_USED = 226;               // RFC3229
    /**
     * @var int
     */
    const MULTIPLE_CHOICES = 300;
    /**
     * @var int
     */
    const MOVED_PERMANENTLY = 301;
    /**
     * @var int
     */
    const FOUND = 302;
    /**
     * @var int
     */
    const SEE_OTHER = 303;
    /**
     * @var int
     */
    const NOT_MODIFIED = 304;
    /**
     * @var int
     */
    const USE_PROXY = 305;
    /**
     * @var int
     */
    const RESERVED = 306;
    /**
     * @var int
     */
    const TEMPORARY_REDIRECT = 307;
    /**
     * @var int
     */
    const PERMANENTLY_REDIRECT = 308;  // RFC7238
    /**
     * @var int
     */
    const BAD_REQUEST = 400;
    /**
     * @var int
     */
    const UNAUTHORIZED = 401;
    /**
     * @var int
     */
    const PAYMENT_REQUIRED = 402;
    /**
     * @var int
     */
    const FORBIDDEN = 403;
    /**
     * @var int
     */
    const NOT_FOUND = 404;
    /**
     * @var int
     */
    const METHOD_NOT_ALLOWED = 405;
    /**
     * @var int
     */
    const NOT_ACCEPTABLE = 406;
    /**
     * @var int
     */
    const PROXY_AUTHENTICATION_REQUIRED = 407;
    /**
     * @var int
     */
    const REQUEST_TIMEOUT = 408;
    /**
     * @var int
     */
    const CONFLICT = 409;
    /**
     * @var int
     */
    const GONE = 410;
    /**
     * @var int
     */
    const LENGTH_REQUIRED = 411;
    /**
     * @var int
     */
    const PRECONDITION_FAILED = 412;
    /**
     * @var int
     */
    const REQUEST_ENTITY_TOO_LARGE = 413;
    /**
     * @var int
     */
    const REQUEST_URI_TOO_LONG = 414;
    /**
     * @var int
     */
    const UNSUPPORTED_MEDIA_TYPE = 415;
    /**
     * @var int
     */
    const REQUESTED_RANGE_NOT_SATISFIABLE = 416;
    /**
     * @var int
     */
    const EXPECTATION_FAILED = 417;
    /**
     * @var int
     */
    const I_AM_A_TEAPOT = 418;                                               // RFC2324
    /**
     * @var int
     */
    const MISDIRECTED_REQUEST = 421;                                         // RFC7540
    /**
     * @var int
     */
    const UNPROCESSABLE_ENTITY = 422;                                        // RFC4918
    /**
     * @var int
     */
    const LOCKED = 423;                                                      // RFC4918
    /**
     * @var int
     */
    const FAILED_DEPENDENCY = 424;                                           // RFC4918
    /**
     * @var int
     */
    const RESERVED_FOR_WEBDAV_ADVANCED_COLLECTIONS_EXPIRED_PROPOSAL = 425;   // RFC2817
    /**
     * @var int
     */
    const UPGRADE_REQUIRED = 426;                                            // RFC2817
    /**
     * @var int
     */
    const PRECONDITION_REQUIRED = 428;                                       // RFC6585
    /**
     * @var int
     */
    const TOO_MANY_REQUESTS = 429;                                           // RFC6585
    /**
     * @var int
     */
    const REQUEST_HEADER_FIELDS_TOO_LARGE = 431;                             // RFC6585
    /**
     * @var int
     */
    const UNAVAILABLE_FOR_LEGAL_REASONS = 451;
    /**
     * @var int
     */
    const INTERNAL_SERVER_ERROR = 500;
    /**
     * @var int
     */
    const NOT_IMPLEMENTED = 501;
    /**
     * @var int
     */
    const BAD_GATEWAY = 502;
    /**
     * @var int
     */
    const SERVICE_UNAVAILABLE = 503;
    /**
     * @var int
     */
    const GATEWAY_TIMEOUT = 504;
    /**
     * @var int
     */
    const VERSION_NOT_SUPPORTED = 505;
    /**
     * @var int
     */
    const VARIANT_ALSO_NEGOTIATES_EXPERIMENTAL = 506;                        // RFC2295
    /**
     * @var int
     */
    const INSUFFICIENT_STORAGE = 507;                                        // RFC4918
    /**
     * @var int
     */
    const LOOP_DETECTED = 508;                                               // RFC5842
    /**
     * @var int
     */
    const NOT_EXTENDED = 510;                                                // RFC2774
    /**
     * @var int
     */
    const NETWORK_AUTHENTICATION_REQUIRED = 511;                             // RFC6585
    /**
     * @var array<int, string>
     */
    private static $codes = [
         100 => 'Continue',
         102 => 'Processing',
         103 => 'Early Hints',
         200 => 'OK',
         201 => 'Created',
         202 => 'Accepted',
         203 => 'Non-Authoritative Information',
         204 => 'No Content',
         205 => 'Reset Content',
         206 => 'Partial Content',
         207 => 'Multi-Status',
         208 => 'Already Reported',
         226 => 'IM Used',
         300 => 'Multiple Choices',
         301 => 'Moved Permanently',
         302 => 'Found',
         303 => 'See Other',
         304 => 'Not Modified',
         305 => 'Use Proxy',
         306 => 'Reserved',
         307 => 'Temporary Redirect',
         308 => 'Permanent Redirect',
         400 => 'Bad Request',
         401 => 'Unauthorized',
         402 => 'Payment Required',
         403 => 'Forbidden',
         404 => 'Not Found',
         405 => 'Method Not Allowed',
         406 => 'Not Acceptable',
         407 => 'Proxy Authentication Required',
         408 => 'Request Timeout',
         409 => 'Conflict',
         410 => 'Gone',
         411 => 'Length Required',
         412 => 'Precondition Failed',
         413 => 'Request Entity Too Large',
         414 => 'Request-URI Too Long',
         415 => 'Unsupported Media Type',
         416 => 'Requested Range Not Satisfiable',
         417 => 'Expectation Failed',
         418 => 'Unassigned',
         421 => 'Misdirected Request',
         422 => 'Unprocessable Entity',
         423 => 'Locked',
         424 => 'Failed Dependency',
         425 => 'Too Early',
         426 => 'Upgrade Required',
         428 => 'Precondition Required',
         429 => 'Too Many Requests',
         431 => 'Request Header Fields Too Large',
         451 => 'Unavailable For Legal Reasons',
         500 => 'Internal Server Error',
         501 => 'Not Implemented',
         502 => 'Bad Gateway',
         503 => 'Service Unavailable',
         504 => 'Gateway Timeout',
         505 => 'HTTP Version Not Supported',
         506 => 'Variant Also Negotiates',
         507 => 'Insufficient Storage',
         508 => 'Loop Detected',
         510 => 'Not Extended',
         511 => 'Network Authentication Required'
    ];

    /**
     * Returns string with HTTP code and its description
     *
     * ```php
     * <?php
     * HttpCode::getDescription(200); // '200 (OK)'
     * HttpCode::getDescription(401); // '401 (Unauthorized)'
     * ```
     *
     * @param int $code
     * @return int|string
     */
    public static function getDescription($code)
    {
        if (isset(self::$codes[$code])) {
            return sprintf('%d (%s)', $code, self::$codes[$code]);
        }
        return $code;
    }
}
