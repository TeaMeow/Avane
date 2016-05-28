<!-- Test -->
{% if 1 + 1 === 2 %}
    ok
{% /if %}
<!-- / Test -->


<!-- Test -->
{% if 1 + 1 === 3 %}
    no
{% elseif 1 + 1 === 2 %}

    {% if 1 + 1 === 2 %}
        ok
    {% else %}
        no
    {% /if %}

{% /if %}
<!-- / Test -->


<!-- Test -->
{% if !A %}
    no
{% elseif B %}
    no
{% else %}
    ok
{% /if %}
<!-- / Test -->
