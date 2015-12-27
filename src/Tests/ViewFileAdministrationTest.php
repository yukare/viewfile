<?php
/**
 * @file
 * Definition of Drupal\viewfile\Tests\ViewFileAdministrationTest.
 */

// Namespace of tests.
namespace Drupal\viewfile\Tests;

// Use of base class for the tests.
use Drupal\simpletest\WebTestBase;

/**
 * Test for administrative interface of ViewFile.
 *
 * @group viewfile
 */
class ViewFileAdministrationTest extends WebTestBase {

  /**
   * Administration user.
   */
  protected $adminUser;

  /**
   * List of modules to enable.
   */
  public static $modules = array(
    'libraries',
    'geshifilter',
    'filter',
    'viewfile',
  );

  /**
   * Configuration object.
   *
   * @var \Drupal\Core\Config\Config
   */
  protected $config;

  /**
   * Set up the tests and create the users.
   */
  public function setUp() {
    parent::setUp();

    // Create object with configuration.
    $this->config = \Drupal::config('viewfile.settings');

    // Create a admin user.
    $permissions = array(
      'administer filters',
      'access administration pages',
      'administer site configuration',
    );
    $this->adminUser = $this->drupalCreateUser($permissions);

    // Log in with filter admin user.
    $this->drupalLogin($this->adminUser);

    // Create folder with test.
    $edit['name'] = 'Test';
    $edit['id'] = 'test';
    $edit['path'] = 'public://test';
    $this->drupalPostForm('admin/config/media/viewfile/folder/add', $edit, t('Save'));
  }

  protected function testFolder() {
    // Create folder with test.
    $edit['name'] = 'Test';
    $edit['id'] = 'test';
    $edit['path'] = 'public://test';
    $this->drupalPostForm('admin/config/media/viewfile/folder/add', $edit, t('Save'));
    $this->drupalGet('admin/config/media/viewfile/folder/teste');
  }

}
