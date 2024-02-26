{% block content %}
<h1>Форма добавления пользователя в файл</h1>

<form action="save/" method="get" id="user-add-form">
    <label for="name">Имя и Фамилия
        <input type="text" name="name" id="name" required>

        <label for="birthday">Дата рождения
            <input type="date" name="birthday" id="birthday" required>

            <input type="submit" value="Добавить">
</form>

{% if message is defined %}
<p>{{ message }}</p>
{% endif %}
{% endblock %}

