{% block content %}
<h1>Форма добавления пользователя в базу данных</h1>

<form action="save/" method="get" id="user-add-form">
    <label for="name">Имя
        <input type="text" name="name" id="name" required>
    </label>

    <label for="lastname">Фамилия
        <input type="text" name="lastname" id="lastname" required>
    </label>

    <label for="birthday">Дата рождения
        <input type="date" name="birthday" id="birthday" required>
    </label>

    <input type="submit" value="Добавить">
</form>

{% if message is defined %}
<p>{{ message }}</p>
{% endif %}
{% endblock %}