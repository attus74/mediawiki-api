<?php

namespace Attus\MediaWikiApi;

use Attus\MediaWikiApi\Query\Page;
use Attus\MediaWikiApi\Query\Image;

/**
 * MediaWiki API
 *
 * @author Attila Németh
 * 07.03.2021
 */
class Api {
  
  // The API base URL, without /w/api.php, e.g. https://en.wikipedia.org
  private       $_baseUrl;
  
  public function __construct(string $baseUrl) {
    $this->_baseUrl = $baseUrl;
  }
  
  /**
   * Get a MediaWiki page
   * @param string $title
   *  Page Title
   * @return Page
   */
  public function getPage(string $title): Page
  {
    $page = new Page($this->_baseUrl);
    $page->setTitle($title);
    return $page;
  }
  
  /**
   * Get a MediaWiki image
   * @param string $title
   *  Image Title
   * @return Image
   */
  public function getImage(string $title): Image
  {
    $image = new Image($this->_baseUrl);
    $image->setTitle($title);
    return $image;
  }
  
}
