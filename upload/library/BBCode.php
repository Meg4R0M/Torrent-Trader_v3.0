<?php
  
   /**
   * @package BBCode Wrapper
   * @version v0.1
   * @author  Lee Howarth
   */
   
   class BBCode
   {
       private $bbcode;
       
       private function __construct()
       {
           if ( function_exists( 'bbcode_create' ) )
           {
                $this -> bbcode = bbcode_create( $this -> getTags() );
                
                $this -> initSmilies();
                
                return;
           }
           
           show_error_msg( 'Error', 'BBCode not available, you need to run `pecl install bbcode-1.0.3b1`', 1 );
       }
       
       static function getInstance()
       {
           static $instance;
           return $instance ? $instance : $instance = new self();
       }
       
       private function getTags()
       {
           return array(
           
              'i' => array( 'type' => BBCODE_TYPE_NOARG, 'open_tag' => '<i>', 'close_tag' => '</i>' ),
              
              'b' => array( 'type' => BBCODE_TYPE_NOARG, 'open_tag' => '<b>', 'close_tag' => '</b>' ),
   
              'u' => array( 'type' => BBCODE_TYPE_NOARG, 'open_tag' => '<u>', 'close_tag' => '</u>' ),
              
              'quote' => array( 'type' => BBCODE_TYPE_NOARG, 'open_tag' => '<quote><h4>Source: {PARAM}</h4>', 'close_tag' => '</quote> '),
              
              'center' => array( 'type' => BBCODE_TYPE_NOARG, 'open_tag' => '<div style="text-align:center;">', 'close_tag' => '</div>'),
          
              'strike' => array( 'type' => BBCODE_TYPE_NOARG, 'open_tag' => '<span style="text-decoration:line-through;">', 'close_tag' => '</span>'),
            
              'color' => array( 'type' => BBCODE_TYPE_ARG, 'open_tag' => '<span style="color:{PARAM}">', 'close_tag' => '</span>')
    
           );
           
           /*[quote="[b]Test[/b]"]test1, test2[/quote]
           [color=navy]Navy Text[/color]
           [color='red\'']Red Text[/color]
           [color="green\""]Green Text[/color]
           [color=&quot;blue\&quot;Test&quot;]Blue Text[/color]*/
       }
       
       private function initSmilies()
       { 
       }
       
       public function parse( $str )
       {
           return bbcode_parse( $this -> bbcode, $str );
       }
       
       public function __destruct()
       {
           bbcode_destroy( $this -> bbcode );
       }
   }
