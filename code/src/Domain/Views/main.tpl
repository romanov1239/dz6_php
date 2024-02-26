<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1, maximum-scale=1, minimum-scale=1">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ title }}</title>
    <link rel="stylesheet" href="../../../style.css">
</head>
<body>
{% include 'site-header.tpl' %}

<main>
    {% if content_template_name == 'error.tpl' %}
    {% include content_template_name with {'title': title, 'message': message, 'code': code, 'file': file, 'line': line} %}
    {% else %}
    {% include content_template_name %}
    {% endif %}
</main>

{% include 'site-footer.tpl' %}

</body>
</html>
