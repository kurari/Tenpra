<?php
/**
 * Templator
 *
 */
require_once 'store/store.class.php';

class TTemplator extends TStore
{
	/**
	 * left delimiter
	 * @var string
	 */
	public $leftDelimiter  = '{{';

	/**
	 * right delimiter
	 * @var string
	 */
	public $rightDelimiter = '}}';

	/**
	 * plugins
	 */
	public $plugins = array(
		'resource'=>array( ),
		'compiler'=>array( )
	);

	public function __construct( )
	{
		$this->registerResourceHandler('file', array($this,'resource_plugin_file_get'));
		$this->registerResourceHandler('string', array($this,'resource_plugin_string_get'));
		$this->registerCompiler('foreach', array($this,'compiler_plugin_foreach_compile'));
	}

	/**
	 * Fetch Applyed Data
	 */
	function fetch( )
	{
		ob_start( );
		$args = func_get_args( );
		call_user_func_array(array($this, 'display'), $args );
		$data = ob_get_clean();
		return $data;
	}

	/**
	 * Display Applyed Data
	 */
	function display( $resource )
	{
		$code = $this->compile($this->getTemplate( $resource ));
		$store = new TStore( );
		$store->set($this->get());
		eval('?>'.$code);
	}

	/**
	 * Get Delimiters
	 */
	function getDelimiters( )
	{
		return array( $this->leftDelimiter, $this->rightDelimiter );
	}


	/**
	 * Get Template
	 */
	function getTemplate( $resource )
	{
		if( preg_match('/([^:]+):\/\/(.*)/', $resource, $m) ){
			$type = $m[1];
			$path = $m[2];
		}
		return call_user_func( $this->plugins['resource'][$type]['get'], $path, $this);
	}

	/**
	 * Compile Data
	 */
	function compile( $text )
	{
		list($l,$r) = $this->getDelimiters( );
		$quick    = '/(?P<bef>.*?)(?P<tag>'.preg_quote($l) .'(?P<inner>.*?)'.preg_quote($r).')(?P<aft>.*)/xms';
		$compiled = "";
		while(preg_match($quick, $text, $m)){
			$bef  = $m['bef'];
			$aft  = $m['aft'];
			$inn  = $m['inner'];
			$text = $aft;
			$compiled .= $bef;
			$compiled .= $this->compileTag( $m['tag'], $inn, $text );
		}
		$compiled .= $text;
		return $compiled;
	}

	/**
	 *
	 */
	function compileTag( $org, $inn, &$text )
	{
		if($inn[0] == '$'){
			return '<?php echo '.$this->compileVar($inn).';?>';
		}
		$p = strpos($inn,' ');
		$name = $p === false ? $inn: substr($inn,0, $p);
		if(isset($this->plugins['compiler'][$name])){
			$offset = 0;
			while(true){
				// find closer
				$p1 = strpos($text, $this->leftDelimiter.'/'.$name, $offset);
				$p2 = strpos($text, $this->leftDelimiter.$name, $offset);
				if($p2 === false || $p2 > $p1 ) break;
				$offset = $p1 + strlen($this->leftDelimiter.'/'.$name);
			}
			$data = substr($text, 0, $p1);
			$text = substr($text, $p1+strlen($this->leftDelimiter.'/'.$name.$this->rightDelimiter));
			return call_user_func( $this->plugins['compiler'][$name]['compile'], $org, $inn, $data, $this );
		}
		return $org;
	}


	function getTagOption( $inn ){
		$text = $this->compileVar( $inn );
		if( preg_match_all('/\s*([^\s=]+)\s*=\s*( (?:[^"\'(\s]|  "[^"]+" | \([^)]*\) |  \'[^\']+\')+ )\s*/xms',$text,$m) ){
			$opt = array_combine( $m[1], $m[2] );
		}
		return $opt;
	}

	function compileVar( $text )
	{
		$text = preg_replace('/\$([a-zA-Z0-9_.]+)/', '$store->get("\1")', $text);
		return $text;
	}


	/**
	 * Regist Resource Handler
	 *
	 * @param string Name
	 * @param callback Getter
	 */
	function registerResourceHandler( $name, $get )
	{
		$this->plugins['resource'][$name] = array('get'=>$get);
	}

	/**
	 * Regist Compiler Handler
	 *
	 * @param string Name
	 * @param callback Getter
	 */
	function registerCompiler( $name, $compile )
	{
		$this->plugins['compiler'][$name] = array('compile'=>$compile);
	}

	//---------------------------------------------------------------------------------
	//
	// Default Plugin's
	//
	//---------------------------------------------------------------------------------


	/**
	 * Resource Handler 
	 *
	 * @param string
	 * @param TTemplator
	 */
	function resource_plugin_file_get( $path, $Templator )
	{
		$file = $path;
		return file_get_contents($file);
	}

	/**
	 * Resource Handler ( string )
	 *
	 * @param string
	 * @param TTemplator
	 */
	function resource_plugin_string_get( $path, $Templator )
	{
		return $path;
	}

	/**
	 * Compiler Plugin Foreach
	 */
	function compiler_plugin_foreach_compile( $org, $inn, $data, $Templator  )
	{
		$opt = $this->getTagOption($inn);
		$from = $opt['from'];
		$key = isset($opt['key']) ? $opt['key']: "k";
		$value = isset($opt['value']) ? $opt['value']: "v";

		$code = implode("\n", array(
			'<?php',
			'$from='.$from.';',
			'foreach( $from as $k=>$v ):',
			'$store->set("'.$key.'",$k);',
			'$store->set("'.$value.'",$v);',
			'?>'
		));
		$code .= $Templator->compile($data);
		$code .= '<?php endforeach; unset($from, $k, $v); ?>';

		return $code;
	}
	
}
?>
