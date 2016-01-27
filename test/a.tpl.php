<html>
<head>
    <title>Caris</title>
</head>
<body>
    {% if a.b.c.d + e.f + c == 1 %}
        <div>
            {% foreach users as user %}
                
                {% foreach user.friends as friend %}
                    
                    { friend.name }

                {% /foreach %}
                
            {% /foreach %}
            { aaa.b.c.dd }
            { world | upper }
        </div>
    {% /if %}
    
    {% if a.b.c.d + e.f == 1 %}
        <div>
            { cyka ? 'yes' : no }
        </div>
    {% /if %}
</body>
</html>