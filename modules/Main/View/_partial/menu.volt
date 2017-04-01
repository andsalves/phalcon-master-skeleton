<ul id="main-menu" class="main-menu multiple-expanded">
    {% for navItem in navigationItems %}
    <li class="{{ navItem.isCurrent() ? 'active' : '' }}">
        <a href="/{{ navItem.getPath() }}">
            <i class="{{ navItem.getIconClass() }}"></i>
            <span class="title"> {{ navItem.getTitle() }} </span>
        </a>

        {% if (navItem.getSubpages() | length) > 0 %}
        <ul style="display: {{ navItem.isCurrent() ? 'block' : 'none' }}">
            {% for subItem in navItem.getSubpages() %}
            <li class="{{ subItem.isCurrent() ? 'active' : '' }}">
                <a href="/{{ subItem.getPath() }}">
                    <i class="{{ subItem.getIconClass() }}"></i>
                    <span class="title"> {{ subItem.getTitle() }} </span>
                </a>
            </li>
            {% endfor %}
        </ul>
        {% endif %}
    </li>
    {% endfor %}
</ul>