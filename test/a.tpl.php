<html>
<head>
    <title>Caris</title>
</head>

<body>
    {% includes header %}
    
    { isLoggedIn.a >> ok : no }
    { loop.index0 }
    {% if a.b.c.d != null == $a %}
        <div>
            {% foreach users as key => user %}
            
            {% foreach users as user %}
                
                {% foreach user.friends as friend %}
                    
                    { friend.name }

                {% /foreach %}
                
            {% /foreach %}
            { $trivago }
            
            {% while a + 1 %}
            {% /while %}
            
            { $trivago | upper }
            { aaa.b.c.dd }
            { world | upper }
        </div>
    {% /if %}
    
    
    
    {% import css %}
    {% import js %}
    
    {% if a.b.c.d + e.f == 1 %}
        <div>
            { cyka ? 'yes' : no.c }
        </div>
    {% /if %}
    
    {% includes footer %}
</body>
</html>