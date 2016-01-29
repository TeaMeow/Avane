<html>
<head>
    <title>Caris</title>
</head>

<body>
    {% foreach ['a', 'b', 'c'] as A %}
    
        { loop.odd >> 奇數 : 偶數 }
        
        {% foreach ['d', 'e', 'f'] as B %}
        
            {loop.index}
            
        {% /foreach %}
        
        <br>
        
    {% /foreach %}
</body>
</html> 