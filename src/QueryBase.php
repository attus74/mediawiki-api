<?php

namespace Attus\MediaWikiApi;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ConnectException;
use Attus\MediaWikiApi\Exception\NotFoundMediaWikiException;

/**
 * Query
 *
 * @author Attila Németh
 * 07.03.2021
 */
class QueryBase {
  
  // The API base URL, without /w/api.php, e.g. https://en.wikipedia.org
  private       $_baseUrl;
  
  // MediaWiki property
  protected     $_prop;
  
  // Titles to query
  protected     $_titles                                        = [];
  
  // Additional variable parameters
  protected     $_params                                        = [];

  public function __construct(string $baseUrl) {
    $this->_baseUrl = $baseUrl;
  }
  
  /**
   * Add a title
   * @param string $title
   */
  protected function _addTitle(string $title)
  {
    $this->_titles[] = $title;
  }
  
  /**
   * Set the title. Only this one title will be looked for
   * @param string $title
   */
  protected function _setTitle(string $title)
  {
    $this->_titles = [$title];
  }


  /**
   * Add a query parameter
   * @param string $key
   * @param type $value
   * @return void
   */
  protected function _addParam(string $key, $value): void
  {
    if (is_array($value)) {
      $value = implode('|', $value);
    }
    $this->_params[$key] = $value;
  }
  
  protected function _get(): object
  {
    $url = $this->_baseUrl . '/w/api.php';
    $params = $this->_params + [
      'action'  => 'query',
      'format'  => 'json',
      'prop'    => $this->_prop,
      'titles'  => implode('|', $this->_titles),
    ];
    $client = new Client();
    try {
      $response = $client->get($url, [
        'query' => $params,
      ]);
      $c = (string)$response->getBody();
      $r = json_decode($c);
      $q = $r->query;
      return $q;
    } catch (ConnectException $ex) {
      throw new NotFoundMediaWikiException('Wrong URL: ' . $this->_baseUrl);
    }
  }
  
}
