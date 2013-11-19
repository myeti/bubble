<?php

class Bubble
{

    /** @var string */
    public $query;

    /** @var array */
    protected $_config = [
        'path.dir'      => 'pages/',
        'path.layout'   => '_layout.php',
        'path.index'    => 'index.html',
        'path.404'      => '404.html'
    ];

    /** @var array */
    protected $_gums = [
        '*' => []
    ];

    /**
     * Setup new Bubble
     * @param array  $config
     * @internal param string $dir
     */
    public function __construct(array $config = [])
    {
        // setup config
        $this->_config = $config + $this->_config;

        // define query
        $this->query = isset($_SERVER['PATH_INFO']) ? trim($_SERVER['PATH_INFO'], '/') : '';

        // setup base path
        $this->_config['path.dir'] = __DIR__ . '/' . $this->_config['path.dir'];
    }

    /**
     * Add vars for specific page
     * @param  string $page
     * @param mixed $input
     * @return Bubble
     */
    public function gum($page, array $input)
    {
        // create gum
        if(!isset($this->_gums[$page])) {
            $this->_gums[$page] = [];
        }

        // add data
        $this->_gums[$page] = $input + $this->_gums[$page];

        return $this;
    }

    /**
     * Lets go !
     */
    public function pop()
    {
        // index
        if(!$this->query) {
            $template = $this->_config['path.dir'] . $this->_config['path.index'];
        }
        // specific page
        else {
            $pages = glob($this->_config['path.dir'] . $this->query . '.*');
            $template = empty($pages) ? $this->_config['path.dir'] . $this->_config['path.404'] : $pages[0]; // 404 if no page found
        }

        // check template
        if(!file_exists($template)) {
            throw new \RuntimeException('Template "' . $template . '" does not exists.');
        }

        // check layout
        if($this->_config['path.layout'] and !file_exists($this->_config['path.dir'] . $this->_config['path.layout'])) {
            throw new \RuntimeException('Layout "' . $this->_config['path.layout'] . '" does not exists.');
        }

        // add query to vars
        $this->gum('*', ['__query' => $this->query]);

        // set global vars
        $vars = $this->_gums['*'];

        // set local vars
        if(isset($this->_gums[$this->query])) {
            $vars = $this->_gums[$this->query] + $vars;
        }

        // render template
        $content = static::render($template, $vars);

        // render layout
        if($this->_config['path.layout']) {
            $vars['__content'] = $content;
            $content = static::render($this->_config['path.dir'] . $this->_config['path.layout'], $vars);
        }

        echo $content;
    }

    /**
     * Compile view
     * @param $__template
     * @param array $__vars
     * @return string
     */
    protected static function render($__template, array $__vars = [])
    {
        extract($__vars);
        ob_start();
        require $__template;
        return ob_get_clean();
    }

}