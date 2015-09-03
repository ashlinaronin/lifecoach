var update_link = document.getElementById('update_link');

// Create steps list
var sort = Sortable.create(step_list, {
    animation: 150,
    dataIdAttr: 'data-id'
});

// Define step ids using Twig so we can update them
var step_ids = [
    {% for step in steps %}
        {{ step.getId }}

        {# only add a comma to steps b4 last one #}
        {% if loop.index != steps|length %}
            ,
        {% endif %}
    {% endfor %}
];

update_link.onclick = function()
{
    var get_url = "/coach/active_project/{{ project.getId }}/reorder_steps?";
    var new_positions = sort.toArray();

    // Pass new step positions in URL in format step_id=new_postition
    for (i = 0; i < step_ids.length; i++)
    {
        get_url += new_positions[i] += "=" + (i+1) + "&";
    }

    window.location.href = get_url;
}
