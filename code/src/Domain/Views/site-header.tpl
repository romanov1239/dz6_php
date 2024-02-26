<header>
    <p>
        {% set now = date('now', 'Europe/Moscow') %}
        Текущее время: {{ now.format('H:i:s') }}
    </p>

    <nav>
        <a href="/">Главная</a>
        <a href="/users">Пользователи</a>
        <a href="/about">О нас</a>
        <a href="/user/save">Добавить в файл</a>
        <a href="/users/save">Добавить в БД</a>
        <a href="/user/update">Изменить пользователя в БД</a>
        <a href="/user/delete">Удалить пользователя в БД</a>


    </nav>
</header>