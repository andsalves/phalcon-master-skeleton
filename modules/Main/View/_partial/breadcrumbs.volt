{% if navigationItem %}
    <div class="breadcrumb-env">
        <ol class="breadcrumb bc-1">
            <li>
                <a href="/{{ navigationItem.getPath() }}">
                    <i class="{{ navigationItem.getIconClass() }}"></i>
                    {{ navigationItem.getTitle() }}
                </a>
            </li>

            {% if navigationItem.getCurrentSubpage() %}
                <li class="active">
                    <strong> {{ navigationItem.getCurrentSubpage().getTitle() }} </strong>
                </li>
            {% endif %}
        </ol>
    </div>
{% endif %}