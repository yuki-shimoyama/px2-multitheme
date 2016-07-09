<?php
/**
 * test
 */

class mainTest extends PHPUnit_Framework_TestCase{

	/**
	 * ファイルシステムユーティリティ
	 */
	private $fs;

	/**
	 * setup
	 */
	public function setup(){
		$this->fs = new \tomk79\filesystem();
	}

	/**
	 * Px2を実行してみる
	 */
	public function testMain(){

		// トップページを実行
		$output = $this->passthru( ['php', __DIR__.'/../.px_execute.php' , '/'] );

		// var_dump($output);
		$this->assertTrue( gettype($output) == gettype('') );

		// 後始末
		$output = $this->passthru( ['php', __DIR__.'/../.px_execute.php' , '/?PX=clearcache'] );

	}//testMain()

	/**
	 * theme "standard"
	 */
	public function testStandard(){

		// トップページを実行
		$output = $this->passthru( ['php', __DIR__.'/testdata/standard/.px_execute.php' , '/'] );

		// var_dump($output);
		$this->assertEquals( preg_match( '/'.preg_quote('standard - default.html', '/').'/', $output ), 1 );
		$this->assertEquals( preg_match( '/'.preg_quote('<span>FAILED</span>', '/').'/', $output ), 0 );
		$this->assertEquals( preg_match( '/'.preg_quote('<div class="contents" data-contents-area="main">', '/').'/', $output ), 1 );
		$this->assertEquals( preg_match( '/'.preg_quote('<p>これはコンテンツファイル。</p>', '/').'/', $output ), 1 );

		// 不正なテーマ名を付与して実行した場合、選択は無効になり、オプションなしの実行と同じ結果が返ってくるはず。
		$output_invalid = $this->passthru( ['php', __DIR__.'/testdata/standard/.px_execute.php' , '/?THEME=invalid//theme'] );
		$this->assertEquals( $output, $output_invalid );

		// /layout_test1.html を実行
		$output = $this->passthru( ['php', __DIR__.'/testdata/standard/.px_execute.php' , '/layout_test1.html'] );
		$this->assertEquals( preg_match( '/'.preg_quote('standard - test1.html', '/').'/', $output ), 1 );
		$this->assertEquals( preg_match( '/'.preg_quote('<p>/layout_test1.html</p>', '/').'/', $output ), 1 );

		// 後始末
		$output = $this->passthru( ['php', __DIR__.'/testdata/standard/.px_execute.php' , '/?PX=clearcache'] );

	}//testStandard()

	/**
	 * theme "standard2"
	 */
	public function testStandard2(){

		// トップページを実行
		$output = $this->passthru( ['php', __DIR__.'/testdata/standard/.px_execute.php' , '/?THEME=standard2'] );

		// var_dump($output);
		$this->assertEquals( preg_match( '/'.preg_quote('standard2 - default.html', '/').'/', $output ), 1 );
		$this->assertEquals( preg_match( '/'.preg_quote('<span>FAILED</span>', '/').'/', $output ), 0 );
		$this->assertEquals( preg_match( '/'.preg_quote('<div class="contents" data-contents-area="main">', '/').'/', $output ), 1 );
		$this->assertEquals( preg_match( '/'.preg_quote('<p>これはコンテンツファイル。</p>', '/').'/', $output ), 1 );
		$this->assertEquals( preg_match( '/'.preg_quote('<p>/index.html</p>', '/').'/', $output ), 1 );

		// /layout_test1.html を実行
		$output = $this->passthru( ['php', __DIR__.'/testdata/standard/.px_execute.php' , '/layout_test1.html?THEME=standard2'] );
		$this->assertEquals( preg_match( '/'.preg_quote('standard2 - default.html', '/').'/', $output ), 1 );
		$this->assertEquals( preg_match( '/'.preg_quote('<p>/layout_test1.html</p>', '/').'/', $output ), 1 );

		// 後始末
		$output = $this->passthru( ['php', __DIR__.'/testdata/standard/.px_execute.php' , '/?PX=clearcache'] );

	}//testStandard2()

	/**
	 * theme "standard3"
	 * 存在しないテーマを読み込むテスト
	 */
	public function testStandard3(){

		// トップページを実行
		$output = $this->passthru( ['php', __DIR__.'/testdata/standard/.px_execute.php' , '/?THEME=undefined'] );

		// var_dump($output);
		$this->assertEquals( preg_match( '/'.preg_quote('HOME | Px2-MultiTheme - test - standard', '/').'/', $output ), 1 );
		$this->assertEquals( preg_match( '/'.preg_quote('<span>FAILED</span>', '/').'/', $output ), 0 );
		$this->assertEquals( preg_match( '/'.preg_quote('<div class="contents" data-contents-area="main">', '/').'/', $output ), 1 );
		$this->assertEquals( preg_match( '/'.preg_quote('<p>これはコンテンツファイル。</p>', '/').'/', $output ), 1 );
		$this->assertEquals( preg_match( '/'.preg_quote('<p>/index.html</p>', '/').'/', $output ), 1 );

		// /layout_test1.html を実行
		$output = $this->passthru( ['php', __DIR__.'/testdata/standard/.px_execute.php' , '/layout_test1.html?THEME=undefined'] );
		$this->assertEquals( preg_match( '/'.preg_quote('TEST1 | Px2-MultiTheme - test - standard', '/').'/', $output ), 1 );
		$this->assertEquals( preg_match( '/'.preg_quote('<span>FAILED</span>', '/').'/', $output ), 0 );
		$this->assertEquals( preg_match( '/'.preg_quote('<div class="contents" data-contents-area="main">', '/').'/', $output ), 1 );
		$this->assertEquals( preg_match( '/'.preg_quote('<p>これはコンテンツファイル。</p>', '/').'/', $output ), 1 );
		$this->assertEquals( preg_match( '/'.preg_quote('<p>/layout_test1.html</p>', '/').'/', $output ), 1 );

		// 後始末
		$output = $this->passthru( ['php', __DIR__.'/testdata/standard/.px_execute.php' , '/?PX=clearcache'] );

	}//testStandard3()

	/**
	 * 後始末
	 */
	public function testFinal(){

		// 後始末
		$output = $this->passthru( ['php', __DIR__.'/testdata/standard/.px_execute.php' , '/?PX=clearcache'] );

	}//testFinal()




	/**
	 * コマンドを実行し、標準出力値を返す
	 * @param array $ary_command コマンドのパラメータを要素として持つ配列
	 * @return string コマンドの標準出力値
	 */
	private function passthru( $ary_command ){
		$cmd = array();
		foreach( $ary_command as $row ){
			$param = '"'.addslashes($row).'"';
			array_push( $cmd, $param );
		}
		$cmd = implode( ' ', $cmd );
		ob_start();
		passthru( $cmd );
		$bin = ob_get_clean();
		return $bin;
	}// passthru()

}
