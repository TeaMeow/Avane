<html>
<head>
    <title>Caris</title>
</head>

<body>
    {% if a.b.c + d.e.f == 3 %}
    
        { loop.odd >> 奇數 : 偶數 } 
        
        {% foreach ['d', 'e', 'f'] as B %}
        
            {% if loop.index == 2 %}
                幹！
                {% break %}
            {% /if %}
            
            {loop.index}
            
        {% /foreach %}
        
        <br>
        
    {% /foreach %}
</body>
</html> 