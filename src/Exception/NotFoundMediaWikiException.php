<?php

namespace Attus\MediaWikiApi\Exception;

/**
 * NotFoundMediaWikiException
 *
 * @author Attila Németh
 * 08.03.2021
 */
class NotFoundMediaWikiException extends \Exception implements MediaWikiApiExceptionInterface {
  
  public function __construct(string $message = "", int $code = 404, \Throwable $previous = NULL) {
    parent::__construct($message, $code, $previous);
  }
  
}
