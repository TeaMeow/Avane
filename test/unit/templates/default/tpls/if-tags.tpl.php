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
{% if !I_AM_TRUE %}
    no
{% elseif I_AM_FALSE %}
    no
{% else %}
    ok
{% /if %}
<!-- / Test -->
