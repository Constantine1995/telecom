<!doctype html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Договор_{{ $contract->contract_number }}</title>
    <style>
        body {
            font-family: 'DejaVu Sans', sans-serif;
            margin: 0;
            padding: 0;
            height: 100%;
        }

        .container {
            width: 100%;
            text-align: center;
        }

        .content {
            display: inline-block;
            text-align: center;
            margin-top: 0;
            padding-top: 0;
        }

        table {
            width: 100%;
            background-color: #f3f3f3;
        }

        .signature-table {
            width: 100%;
            background-color: #ffffff;
        }

        td {
            padding: 5px;
            text-align: left;
            font-size: 13px;
        }

        .sm-td {
            padding: 6px;
            text-align: left;
            font-size: 14px;
        }

        .section-header {
            background-color: #d1d1d1;
            padding: 5px;
            font-weight: bold;
            text-align: center;
        }

        h2 {
            margin-top: 0;
            padding-top: 0;
        }

        .clause {
            margin: 10px 0;
            text-align: justify;
        }

    </style>
</head>

<body>

<div class="container">
    <div class="content">
        <h3>Договор № {{ $contract->contract_number }}</h3>
    </div>
    <div>
        <table>
            <tr>
                <td class="section-header">Сведения об Абоненте</td>
            </tr>
            <tr>
                <td><strong>Фамилия:</strong> {{ $passport->last_name }}</td>
            </tr>
            <tr>
                <td><strong>Имя:</strong> {{ $passport->first_name }}</td>
            </tr>
            <tr>
                <td><strong>Отчество:</strong> {{ $passport->middle_name }}</td>
            </tr>
            <tr>
                <td><strong>Дата Рождения:</strong> {{ $passport->formatted_birth_date }}</td>
            </tr>
            <tr>
                <td><strong>Место Рождения:</strong> {{ $passport->birthplace }}</td>
            </tr>
            <tr>
                <td><strong>Моб. телефон:</strong> {{ $contract->connectRequest->phone }}</td>
            </tr>
            <tr>
                <td class="section-header">Паспортные данные</td>
            </tr>
            <tr>
                <td><strong>Номер и Серия:</strong> {{ $passport->serial_number }}</td>
            </tr>
            <tr>
                <td><strong>Выдан:</strong> {{ $passport->issue_by_organization }}</td>
            </tr>
            <tr>
                <td><strong>Когда:</strong> {{ $passport->formatted_issue_date }}</td>
            </tr>
            <tr>
                <td class="section-header">Адрес регистрации</td>
            </tr>
            <tr>
                <td><strong>Адрес:</strong> {{ $contract->address->getFullAddress() }}</td>
            </tr>
            <tr>
                <td class="section-header">Тарифный план</td>
            </tr>
            <tr>
                <td><strong>Название:</strong> {{ $contract->tariff->name }}</td>
            </tr>
            <tr>
                <td><strong>Тип подключения:</strong> {{ $contract->tariff->connection_type->label() }}</td>
            </tr>
            <tr>
                <td><strong>Цена:</strong> {{ $contract->tariff->formattedPrice }}</td>
            </tr>
            <tr>
                <td><strong>Скорость:</strong> {{ $contract->tariff->formatted_speed }}</td>
            </tr>
            <tr>
                <td class="section-header">Оборудование</td>
            </tr>
            <tr>
                <td><strong>Модель:</strong> {{ $contract->device->name ?? 'Отсутствует' }}</td>
            </tr>
            <tr>
                <td><strong>IP-адрес:</strong> {{ $contract->device->ip_address ?? 'Отсутствует' }}</td>
            </tr>
            <tr>
                <td><strong>MAC-адрес:</strong> {{ $contract->device->mac_address ?? 'Отсутствует' }}</td>
            </tr>
            <tr>
                <td><strong>Дата
                        подключения:</strong> {{ $contract->device->formatted_date_connection ?? 'Отсутствует' }}</td>
            </tr>
        </table>


    </div>

    <br>
    <p class="clause" style="font-size: 0.9em; color: #666;">
        *Настоящий договор составлен в двух экземплярах, имеющих равную юридическую силу.<br>
        **Перед подписанием рекомендуется консультация юриста.<br>
    </p>

    <div>
        <table class="signature-table">
            <tr>
                <td class="sm-td">
                    <p><strong>Исполнитель:</strong> _________________</p>
                </td>
                <td class="sm-td">
                    <p><strong>Заказчик:</strong> _________________</p>
                </td>
                <td class="sm-td" style="text-align: right;">
                    <p><strong>Дата:</strong> _________________</p>
                </td>
            </tr>


        </table>
    </div>

</div>

</body>
</html>
