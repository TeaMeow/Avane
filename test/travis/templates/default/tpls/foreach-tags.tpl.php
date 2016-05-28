<!-- Test -->
{% foreach datas as data %}
{ loop.index0 } - { data }
{% /foreach %}
<!-- / Test -->


<!-- Test -->
{% foreach datas2 as key => data %}
{ key } - { data }
{% /foreach %}
<!-- / Test -->