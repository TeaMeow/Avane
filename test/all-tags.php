<?php echo "adasd"; ?>   
        $this->Pushes['/\${(\w(?<!\d)[\w\'-]*)}/']    = '<?php echo $this->Get(\'$1\'); ?>';  //${Variable}
        $this->Pushes['/{else}/']                   = '<?php else: ?>';       //『{elseif』 xxxxxx == xxxxx  }         HIGH PRIORITY
        $this->Pushes['/{\/if}/']                   = '<?php endif; ?>';      //『{/if}』];                            HIGH PRIORITY
        $this->Pushes['/(?<={if|{elseif)(.*?)(})/'] = '$1): ?>';              //  {if       xxxxxx == xxxxx『}』       HIGH PRIORITY
        $this->Pushes["/\{(.*?)\?(.*?):(.*?)\}/"]    = '<?php echo ($1)?$2:$3;?>';  // {%Starred?'stared':'ok'}
        $this->Pushes["/\{(.*?)\|(.*?):(.*?)\}/"]    = '<?php echo ($1)?\'$2\':\'$3\';?>';  // {%Starred|stared:ok}
        $this->Pushes["/{%(\w(?<!\d)[\w'-]*)}/"]    = '<?php echo $this->Get(\'$1\'); ?>';  //{%Variable}
        
        $this->Pushes["/(%(\w(?<!\d)[\w'-]*))/"]    = '$this->Get(\'$2\')';                 // %Variables
        $this->Pushes['/{elseif/']                  = '<?php elseif(';        //『{elseif』 xxxxxx == xxxxx  }
        $this->Pushes['/{if/']                      = '<?php if(';            //『{if』     xxxxxx == xxxxx  }
        
        $this->Pushes['/{repeat (.*) (.*)}/']       = '<?php for($$2=0;$$2<$1;$$2++) { ?>';            //『{if』     xxxxxx == xxxxx  }
        $this->Pushes['/{\/repeat}/']               = '<?php } ?>';            //『{if』     xxxxxx == xxxxx  }