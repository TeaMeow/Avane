
    {% foreach ['a', 'b', 'c'] as A %}
    
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
    
    { a.b.c.d.e.f.g }
