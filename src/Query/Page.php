<?php

namespace Attus\MediaWikiApi\Query;

//https://www.mediawiki.org/wiki/API:Main_page/de
//https://en.wikipedia.org/w/api.php?action=help&modules=query%2Brevisions
//https://www.mediawiki.org/wiki/API:Query/de
//https://www.mediawiki.org/w/api.php?action=help&modules=query%2Binfo


use Attus\MediaWikiApi\QueryBase;
use Attus\MediaWikiApi\Exception\NotFoundMediaWikiException;

/**
 * Page
 *
 * @author Attila Németh
 * 07.03.2021
 */
class Page extends QueryBase {
  
  // Page Title
  private           $_title;
  
  // Page Content
  private           $_content;
  
  // Page URL
  private           $_url;
  
  /**
   * Set Page Title
   * @param string $title
   * @return void
   */
  public function setTitle(string $title): void 
  {
    $this->_title = $title;
  }
  
  /**
   * Get Page Title
   * @return string
   */
  public function getTitle(): string
  {
    return $this->_title;
  }
  
  /**
   * Get Page Content
   * @return string
   * @throws NotFoundMediaWikiException
   */
  public function getContent(): string
  {
    if (empty($this->_content)) {
      $this->_prop = 'revisions';
      $this->_addParam('rvprop', 'content');
      $this->_addParam('rvslots', '*');
      $this->_addParam('titles', $this->_title);
      $response = $this->_get();
      if (property_exists($response->pages, '-1')) {
        throw new NotFoundMediaWikiException('Not Found: ' . $this->_title);
      }
      foreach($response->pages as $page) {
        $rev = reset($page->revisions);
        foreach($rev->slots as $slot) {
          foreach($slot as $property => $value) {
            if ($property === '*') {
              $this->_content = $value;
              break 3;
            }
          }
        }
      }
    }
    return $this->_content;
  }
  
  /**
   * Get Page canonical URL
   * @return string
   * @throws NotFoundMediaWikiException
   */
  public function getUrl(): string
  {
    if (empty($this->_url)) {
      $this->_prop = 'info';
      $this->_addParam('inprop', 'url');
      $this->_addParam('titles', $this->_title);
      $response = $this->_get();
      if (property_exists($response->pages, '-1')) {
        throw new NotFoundMediaWikiException('Not Found: ' . $this->_title);
      }
      foreach($response->pages as $page) {
        $this->_url = $page->canonicalurl;
        break;
      }
    }
    return $this->_url;
  }
  
}
