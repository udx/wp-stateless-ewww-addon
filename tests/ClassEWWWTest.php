<?php

namespace WPSL\Ewww;

use PHPUnit\Framework\TestCase;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use Brain\Monkey;
use Brain\Monkey\Actions;
use Brain\Monkey\Filters;
use Brain\Monkey\Functions;
use wpCloud\StatelessMedia\WPStatelessStub;

/**
 * Class ClassEWWWTest
 */
class ClassEWWWTest extends TestCase {

  // Adds Mockery expectations to the PHPUnit assertions count.
  use MockeryPHPUnitIntegration;

  const TEST_URL = 'https://test.test';
  const TEST_FILE = 'folder/image.png';
  const SRC_URL = self::TEST_URL . '/' . self::TEST_FILE;
  
  public function setUp(): void {
		parent::setUp();
		Monkey\setUp();
  }

  public function tearDown(): void {
		Monkey\tearDown();
		parent::tearDown();
	}

  public function testShouldInitHooks() {
    $ewww = new Ewww();

    $ewww->module_init([]);

    self::assertNotFalse( has_action('ewww_image_optimizer_post_optimization', [ $ewww, 'post_optimization' ]) );
  }

  public function testPreOptimization() {
    $ewww = new Ewww();

    Filters\expectApplied('wp_stateless_file_name')
      ->once()
      ->andReturn( self::TEST_FILE );

    Actions\expectDone('sm:sync::syncFile')
      ->once();
    
    $ewww->pre_optimization(self::SRC_URL, null, null);
  }

  public function testPostOptimization() {
    $ewww = new Ewww();

    Filters\expectApplied('wp_stateless_file_name')
      ->once()
      ->andReturn( self::TEST_FILE );

    Actions\expectDone('sm:sync::syncFile')
      ->twice();

    $ewww->post_optimization(self::SRC_URL, null, null);
  }
}

function file_exists() {
  return true;
}
