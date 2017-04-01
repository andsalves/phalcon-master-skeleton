<div class="page-title">
    <div class="title-env" style="padding-top: 6px">
        {% if navigationItem %}
            {% if navigationItem.getCurrentSubpage() %}
                <h1 class="title"> {{ navigationItem.getCurrentSubpage().getTitle() }} </h1>
                {% if navigationItem.getCurrentSubpage().getDescription() %}
                    <p class="description">
                        {{ navigationItem.getCurrentSubpage().getDescription() }}
                    </p>
                {% endif %}
            {% else %}
                <h1 class="title"> {{ navigationItem.getTitle() }} </h1>
                {% if navigationItem.getDescription() %}
                    <p class="description">
                        {{ navigationItem.getDescription() }}
                    </p>
                {% endif %}
            {% endif %}
        {% endif %}
    </div>

    {{ tag.navigation().renderBreadcrumbs() }}

</div>