<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ 'Payout Invoice' | trans }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 14px;
            line-height: 1.5;
            color: #333;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
        }
        .logo {
            max-width: 200px;
            max-height: 80px;
        }
        .invoice-info {
            text-align: right;
        }
        .invoice-title {
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 10px;
        }
        .company-info, .vendor-info {
            margin-bottom: 30px;
        }
        .section-title {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 10px;
            border-bottom: 1px solid #ddd;
            padding-bottom: 5px;
        }
        .info-row {
            display: flex;
            margin-bottom: 5px;
        }
        .info-label {
            font-weight: bold;
            width: 150px;
        }
        .info-value {
            flex: 1;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }
        th, td {
            padding: 10px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        th {
            background-color: #f5f5f5;
            font-weight: bold;
        }
        .amount-row {
            font-weight: bold;
        }
        .total-row {
            font-size: 16px;
            font-weight: bold;
        }
        .footer {
            margin-top: 50px;
            text-align: center;
            font-size: 12px;
            color: #777;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div>
                {% if company.logo %}
                    <img src="{{ company.logo }}" alt="{{ company.name }}" class="logo">
                {% else %}
                    <h2>{{ company.name }}</h2>
                {% endif %}
            </div>
            <div class="invoice-info">
                <div class="invoice-title">{{ 'Payout Invoice' | trans }}</div>
                <div>{{ 'Invoice ID' | trans }}: #{{ withdrawal.id }}</div>
                <div>{{ 'Date' | trans }}: {{ withdrawal.created_at | date('F d, Y') }}</div>
                <div>{{ 'Status' | trans }}: {{ withdrawal_status }}</div>
            </div>
        </div>

        <div class="company-info">
            <div class="section-title">{{ 'Company Information' | trans }}</div>
            <div class="info-row">
                <div class="info-label">{{ 'Name' | trans }}:</div>
                <div class="info-value">{{ company.name }}</div>
            </div>
            {% if company.address %}
            <div class="info-row">
                <div class="info-label">{{ 'Address' | trans }}:</div>
                <div class="info-value">{{ company.address }}</div>
            </div>
            {% endif %}
            {% if company.city or company.state or company.zipcode %}
            <div class="info-row">
                <div class="info-label">{{ 'City/State/Zip' | trans }}:</div>
                <div class="info-value">
                    {% if company.city %}{{ company.city }}{% endif %}
                    {% if company.state %}{% if company.city %}, {% endif %}{{ company.state }}{% endif %}
                    {% if company.zipcode %}{% if company.city or company.state %}, {% endif %}{{ company.zipcode }}{% endif %}
                </div>
            </div>
            {% endif %}
            {% if company.phone %}
            <div class="info-row">
                <div class="info-label">{{ 'Phone' | trans }}:</div>
                <div class="info-value">{{ company.phone }}</div>
            </div>
            {% endif %}
            {% if company.email %}
            <div class="info-row">
                <div class="info-label">{{ 'Email' | trans }}:</div>
                <div class="info-value">{{ company.email }}</div>
            </div>
            {% endif %}
            {% if company.tax_id %}
            <div class="info-row">
                <div class="info-label">{{ 'Tax ID' | trans }}:</div>
                <div class="info-value">{{ company.tax_id }}</div>
            </div>
            {% endif %}
        </div>

        <div class="vendor-info">
            <div class="section-title">{{ 'Vendor Information' | trans }}</div>
            <div class="info-row">
                <div class="info-label">{{ 'Name' | trans }}:</div>
                <div class="info-value">{{ withdrawal.customer.name }}</div>
            </div>
            {% if withdrawal.customer.email %}
            <div class="info-row">
                <div class="info-label">{{ 'Email' | trans }}:</div>
                <div class="info-value">{{ withdrawal.customer.email }}</div>
            </div>
            {% endif %}
            {% if withdrawal.customer.phone %}
            <div class="info-row">
                <div class="info-label">{{ 'Phone' | trans }}:</div>
                <div class="info-value">{{ withdrawal.customer.phone }}</div>
            </div>
            {% endif %}
            <div class="info-row">
                <div class="info-label">{{ 'Payment Method' | trans }}:</div>
                <div class="info-value">{{ withdrawal_payment_channel }}</div>
            </div>
        </div>

        {% if withdrawal.bank_info %}
        <div class="payment-info">
            <div class="section-title">{{ 'Payment Information' | trans }}</div>
            {% if withdrawal.bank_info.name %}
            <div class="info-row">
                <div class="info-label">{{ 'Bank Name' | trans }}:</div>
                <div class="info-value">{{ withdrawal.bank_info.name }}</div>
            </div>
            {% endif %}
            {% if withdrawal.bank_info.number %}
            <div class="info-row">
                <div class="info-label">{{ 'Account Number' | trans }}:</div>
                <div class="info-value">{{ withdrawal.bank_info.number }}</div>
            </div>
            {% endif %}
            {% if withdrawal.bank_info.full_name %}
            <div class="info-row">
                <div class="info-label">{{ 'Account Holder' | trans }}:</div>
                <div class="info-value">{{ withdrawal.bank_info.full_name }}</div>
            </div>
            {% endif %}
        </div>
        {% endif %}

        <table>
            <thead>
                <tr>
                    <th>{{ 'Description' | trans }}</th>
                    <th style="text-align: right;">{{ 'Amount' | trans }}</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>{{ 'Withdrawal Amount' | trans }}</td>
                    <td style="text-align: right;">{{ withdrawal.amount | price_format }}</td>
                </tr>
                <tr>
                    <td>{{ 'Fee' | trans }} ({{ withdrawal_fee_percentage }}%)</td>
                    <td style="text-align: right;">{{ withdrawal.fee | price_format }}</td>
                </tr>
                <tr class="total-row">
                    <td>{{ 'Total' | trans }}</td>
                    <td style="text-align: right;">{{ (withdrawal.amount - withdrawal.fee) | price_format }}</td>
                </tr>
            </tbody>
        </table>

        {% if withdrawal.description %}
        <div class="notes">
            <div class="section-title">{{ 'Notes' | trans }}</div>
            <p>{{ withdrawal.description }}</p>
        </div>
        {% endif %}

        <div class="footer">
            <p>{{ 'Thank you for your business!' | trans }}</p>
        </div>
    </div>
</body>
</html>
