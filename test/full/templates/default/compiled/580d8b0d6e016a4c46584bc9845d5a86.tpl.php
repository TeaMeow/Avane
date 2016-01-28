<html>
<head>
    <title>Caris</title>
</head>

<body>
    <?php includes 'header'; ?>
    
    <?=  $this->get('isLoggedIn')['a'] ? ' ok ' : ' no '; ?>
    <?=  $this->get('loop')['index0']; ?> 
    <?php if($this->get('a')['b']['c']['d'] != null == $a): ?>
        <div>
            <?php foreach($this->get('users') as $key => user): $this->loopUnzip($key => user, 'key => user'); ?>
            
            <?php foreach($this->get('users') as $user): $this->loopUnzip($user, 'user'); ?>
                
                <?php foreach($this->get('user')['friends'] as $friend): $this->loopUnzip($friend, 'friend'); ?>
                    
                    <?=  $this->get('friend')['name']; ?>

                <?php endforeach; ?>
                
            <?php endforeach; ?>
            <?=  $trivago ; ?>
            
            <?php while($this->get('a') + 1): ?>
            <?php endwhile; ?>
            
            <?= $this->directive( $trivago , '_upper'); ?>
            <?=  $this->get('aaa')['b']['c']['dd']; ?>
            <?= $this->directive( $this->get('world') , '_upper'); ?>
        </div>
    <?php endif; ?>
    
    
    
    <?php $this->Output('css'); ?>
    <?php $this->Output('js'); ?>
    
    <?php if($this->get('a')['b']['c']['d'] + $this->get('e')['f'] == 1): ?>
        <div>
            <?=  $this->get('cyka')  ?  'yes'  :  $this->get('no')['c'] ?>
        </div>
    <?php endif; ?>
    
    <?php includes 'footer'; ?>
</body>
</html>