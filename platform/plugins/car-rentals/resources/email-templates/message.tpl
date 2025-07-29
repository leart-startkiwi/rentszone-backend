{{ header }}
<div class="bb-main-content">
    <table class="bb-box" cellpadding="0" cellspacing="0">
        <tbody>
        <tr>
            <td class="bb-content bb-pb-0" align="center">
                <table class="bb-icon bb-icon-lg bb-bg-blue" cellspacing="0" cellpadding="0">
                    <tbody>
                    <tr>
                        <td valign="middle" align="center">
                            <img src="{{ 'mail' | icon_url }}" class="bb-va-middle" width="40" height="40" alt="Icon">
                        </td>
                    </tr>
                    </tbody>
                </table>
                <h1 class="bb-text-center bb-m-0 bb-mt-md">New Message</h1>
            </td>
        </tr>
        <tr>
            <td>
                <table cellpadding="0" cellspacing="0">
                    <tbody>
                    <tr>
                        <td class="bb-content">
                            <p>Dear Admin,</p>

                            <h4>There is a new message from {{ site_title }}:</h4>

                            <table class="bb-table" cellspacing="0" cellpadding="0">
                                <thead>
                                <tr>
                                    <th width="120px"></th>
                                </tr>
                                </thead>
                                <tbody>
                                {% if message_name %}
                                <tr>
                                    <td>Name:</td>
                                    <td class="bb-font-strong bb-text-left"> {{ message_name }} ({{ message_ip_address }})</td>
                                </tr>
                                {% endif %}
                                {% if message_subject %}
                                <tr>
                                    <td>Subject:</td>
                                    <td class="bb-font-strong bb-text-left"> <a href="{{ message_link }}">{{ message_subject }}</a> </td>
                                </tr>
                                {% endif %}
                                {% if message_email %}
                                <tr>
                                    <td>Email:</td>
                                    <td class="bb-font-strong bb-text-left"> {{ message_email }} </td>
                                </tr>
                                {% endif %}
                                {% if message_address %}
                                <tr>
                                    <td>Address:</td>
                                    <td class="bb-font-strong bb-text-left"> {{ message_address }} </td>
                                </tr>
                                {% endif %}
                                {% if message_phone %}
                                <tr>
                                    <td>Phone:</td>
                                    <td class="bb-font-strong bb-text-left"> {{ message_phone }} </td>
                                </tr>
                                {% endif %}
                                {% for key, value in message_custom_fields %}
                                    <tr>
                                        <td>{{ key }}:</td>
                                        <td class="bb-font-strong bb-text-left"> {{ value }} </td>
                                    </tr>
                                {% endfor %}
                                {% if message_content %}
                                <tr>
                                    <td colspan="2">Content:</td>
                                </tr>
                                <tr>
                                    <td colspan="2" class="bb-font-strong"><i>{{ message_content }}</i></td>
                                </tr>
                                {% endif %}
                                </tbody>
                            </table>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </td>
        </tr>
        </tbody>
    </table>
</div>

{{ footer }}
