<!-- Test -->
{% foreach datas as data %}
{ loop.index0 } - { data } \n
{% /foreach %}
<!-- / Test -->


<!-- Test -->
{% foreach datas2 as datas %}
    { loop.index0 } |
    {% foreach datas as data %}
        { loop.index0 } - { data } \n
    {% /foreach %}
{% /foreach %}
<!-- / Test -->


<!-- Test -->
{% foreach mixedData as data %}
    { loop.index0 } - { data } \n
{% /foreach %}
<!-- / Test -->