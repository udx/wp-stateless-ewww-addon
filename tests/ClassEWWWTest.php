<?php

namespace WPSL\Ewww;

use PHPUnit\Framework\TestCase;
use wpCloud\StatelessMedia\WPStatelessStub;

/**
 * Class ClassEWWWTest
 */
class ClassEWWWTest extends TestCase {

  public static $functions;

  public function setUp(): void {
    self::$functions = $this->createPartialMock(
      ClassEWWWTest::class,
      ['add_filter', 'add_action', 'apply_filters', 'do_action', 'remove_filter']
    );

    $this::$functions->method('apply_filters')->will($this->returnArgument(1));
  }

  public function testShouldInitModule() {
    self::$functions->expects($this->exactly(1))
      ->method('add_action')
      ->with('ewww_image_optimizer_post_optimization');

    $ewww = new Ewww();
    $ewww->module_init([]);
  }

  public function testPreOptimization() {
    $ewww = new Ewww();

    $this::$functions->expects($this->exactly(1))
      ->method('apply_filters')->with('wp_stateless_file_name');

    $this::$functions->expects($this->exactly(1))
      ->method('do_action')->with('sm:sync::syncFile');

    $this->assertEquals(null, $ewww->pre_optimization('https://test.test/test/test.test', null, null));
  }

  public function testPostOptimization() {
    $ewww = new Ewww();

    $this::$functions->expects($this->exactly(1))
      ->method('apply_filters')->with('wp_stateless_file_name');

    $this::$functions->expects($this->exactly(2))
      ->method('do_action')->with('sm:sync::syncFile');

    $this::$functions->expects($this->exactly(1))
      ->method('add_filter')->with('upload_mimes');

    $this::$functions->expects($this->exactly(1))
      ->method('remove_filter')->with('upload_mimes');

    $this->assertEquals(null, $ewww->post_optimization('https://test.test/test/test.test', null, null));
  }

  public function add_filter() {
  }

  public function add_action() {
  }

  public function apply_filters($a, $b) {
  }

  public function do_action($a, ...$b) {
  }

  public function remove_filter($a, ...$b) {
  }

  public function debug_backtrace($a, $b) {
  }

}

function add_filter($a, $b, $c = 10, $d = 1) {
  return ClassEWWWTest::$functions->add_filter($a, $b, $c, $d);
}

function add_action($a, $b, $c = 10, $d = 1) {
  return ClassEWWWTest::$functions->add_action($a, $b, $c, $d);
}

function apply_filters($a, $b) {
  return ClassEWWWTest::$functions->apply_filters($a, $b);
}

function do_action($a, ...$b) {
  return ClassEWWWTest::$functions->do_action($a, ...$b);
}

function remove_filter($a, $b) {
  return ClassEWWWTest::$functions->remove_filter($a, $b);
}

function wp_doing_ajax() {
  return true;
}
function file_exists() {
  return true;
}

function wp_get_upload_dir() {
  return [
    'baseurl' => 'https://test.test/uploads',
    'basedir' => '/var/www/uploads'
  ];
}

function ud_get_stateless_media() {
  return WPStatelessStub::instance();
}
