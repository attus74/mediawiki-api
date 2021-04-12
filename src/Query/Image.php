<?php

namespace Attus\MediaWikiApi\Query;

use Attus\MediaWikiApi\QueryBase;
use Attus\MediaWikiApi\Exception\NotFoundMediaWikiException;

/**
 * Image
 *
 * @author Attila Németh
 * 08.03.2021
 */
class Image extends QueryBase {
  
  // Page Title
  private           $_title;
  
  // Image Page URL
  private           $_pageUrl;
  
  // Original width and height
  private           $_width;
  private           $_height;
  
  // Original User
  private           $_user;

  /**
   * Set Image Title
   * @param string $title
   * @return void
   */
  public function setTitle(string $title): void 
  {
    $this->_title = $title;
  }
  
  /**
   * Get Image Title
   * @return string
   */
  public function getTitle(): string
  {
    return $this->_title;
  }
  
  /**
   * Get Image Page URL
   * 
   * This function does not return the URL to the image file itself, but that to the image description page
   * @return string
   * @throws NotFoundMediaWikiException
   */
  public function getPageUrl(): string
  {
    if (empty($this->_pageUrl)) {
      $this->_prop = 'imageinfo';
      $this->_setTitle($this->_title);
      $this->_addParam('iiprop', ['url', 'size', 'user']);
      $response = $this->_get();
      if (property_exists($response->pages, '-1')) {
        throw new NotFoundMediaWikiException('Not Found: ' . $this->_title);
      }
      foreach($response->pages as $page) {
        foreach($page->imageinfo as $info) {
          $this->_width = $info->width;
          $this->_height = $info->height;
          $this->_pageUrl = $info->descriptionurl;
          $this->_user = $info->user;
          break 2;
        }
      }
    }
    return $this->_pageUrl;
  }
  
  /**
   * Get image URL
   * @param int $width
   *  The width of the image, whose URL is asked for. If the original image is smaller than that, the original will be returned.
   * @return string
   * @throws NotFoundMediaWikiException
   */
  public function getImageUrl(int $width): string
  {
    $this->_prop = 'imageinfo';
    $this->_setTitle($this->_title);
    $this->_addParam('iiprop', ['url', 'size', 'user']);
    $this->_addParam('iiurlwidth', $width);
    $response = $this->_get();
    if (property_exists($response->pages, '-1')) {
      throw new NotFoundMediaWikiException('Not Found: ' . $this->_title);
    }
    foreach($response->pages as $page) {
      foreach($page->imageinfo as $info) {
        $this->_width = $info->width;
        $this->_height = $info->height;
        $this->_pageUrl = $info->descriptionurl;
        $this->_user = $info->user;
        if ($info->width >= $info->height || $info->thumbheight <= $width) {
          return $info->thumburl;
        }
        else {
          $calculatedWidth = round($width / $info->height * $info->width, 0);
          $this->_addParam('iiurlwidth', $calculatedWidth);
          $calculatedResponse = $this->_get();
          foreach($calculatedResponse->pages as $calculatedPage) {
            foreach($calculatedPage->imageinfo as $calculatedInfo) {
              return $calculatedInfo->thumburl;
            }
          }
        }
      }
    }
  }
  
  /**
   * Get image user name
   * @return string
   * @throws NotFoundMediaWikiException
   */
  public function getImageUser(): string
  {
    if (empty($this->_user)) {
      $this->_prop = 'imageinfo';
      $this->_setTitle($this->_title);
      $this->_addParam('iiprop', ['url', 'size', 'user']);
      $this->_addParam('iiurlwidth', $width);
      $response = $this->_get();
      if (property_exists($response->pages, '-1')) {
        throw new NotFoundMediaWikiException('Not Found: ' . $this->_title);
      }
      foreach($response->pages as $page) {
        foreach($page->imageinfo as $info) {
          $this->_width = $info->width;
          $this->_height = $info->height;
          $this->_pageUrl = $info->descriptionurl;
          $this->_user = $info->user;
          break 2;
        }
      }
    }
    return $this->_user;
  }
  
}
