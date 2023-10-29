<?php

namespace WPSL\ShortPixelImageOptimizer;

use PHPUnit\Framework\TestCase;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use Brain\Monkey;
use Brain\Monkey\Actions;
use Brain\Monkey\Filters;
use Brain\Monkey\Functions;
use wpCloud\StatelessMedia\WPStatelessStub;

/**
 * Class ClassShortPixelImageOptimizerTest
 */

class ClassShortPixelImageOptimizerTest extends TestCase {
  // Adds Mockery expectations to the PHPUnit assertions count.
  use MockeryPHPUnitIntegration;

  public function setUp(): void {
		parent::setUp();
		Monkey\setUp();
  }
	
  public function tearDown(): void {
		Monkey\tearDown();
		parent::tearDown();
	}

  public function testShouldInitHooks() {
    $shortPixelImageOptimizer = new ShortPixelImageOptimizer();

    $shortPixelImageOptimizer->module_init([]);

    self::assertNotFalse( has_filter('shortpixel_image_exists', [ $shortPixelImageOptimizer, 'shortpixel_image_exists' ]) );
    self::assertNotFalse( has_filter('shortpixel_skip_backup', [ $shortPixelImageOptimizer, 'shortpixel_skip_backup' ]) );
    self::assertNotFalse( has_filter('wp_update_attachment_metadata', [ $shortPixelImageOptimizer, 'wp_update_attachment_metadata' ]) );
    self::assertNotFalse( has_filter('shortpixel_skip_delete_backups_and_webps', [ $shortPixelImageOptimizer, 'shortpixel_skip_delete_backups_and_webps' ]) );
    self::assertNotFalse( has_filter('shortpixel_backup_folder', [ $shortPixelImageOptimizer, 'getBackupFolderAny' ]) );
    self::assertNotFalse( has_filter('wp_stateless_add_media_args', [ $shortPixelImageOptimizer, 'wp_stateless_add_media_args' ]) );
    self::assertNotFalse( has_filter('shortpixel_webp_image_base', [ $shortPixelImageOptimizer, 'shortpixel_webp_image_base' ]) );
    self::assertNotFalse( has_filter('shortpixel_image_urls', [ $shortPixelImageOptimizer, 'shortpixel_image_urls' ]) );

    self::assertNotFalse( has_action('shortpixel_image_optimised', [ $shortPixelImageOptimizer, 'shortpixel_image_optimised' ]) );
    self::assertNotFalse( has_action('shortpixel_before_restore_image', [ $shortPixelImageOptimizer, 'shortpixel_before_restore_image' ]) );
    self::assertNotFalse( has_action('shortpixel_after_restore_image', [ $shortPixelImageOptimizer, 'handleRestoreBackup' ]) );
    self::assertNotFalse( has_action('admin_enqueue_scripts', [ $shortPixelImageOptimizer, 'shortPixelJS' ]) );
    self::assertNotFalse( has_action('sm:synced::image', [ $shortPixelImageOptimizer, 'sync_backup_file' ]) );
    self::assertNotFalse( has_action('sm:synced::image', [ $shortPixelImageOptimizer, 'sync_webp_file' ]) );
  }

  public function testShouldInitHooksWithDisabledSubdomain() {
    $shortPixelImageOptimizer = new ShortPixelImageOptimizer();

    define('WP_STATELESS_MEDIA_SHORTPIXEL_DISABLE_SUBDOMAIN_LINK', true);

    $shortPixelImageOptimizer->module_init([]);

    self::assertFalse( has_filter('shortpixel_image_urls', [ $shortPixelImageOptimizer, 'shortpixel_image_urls' ]) );
  }
}
