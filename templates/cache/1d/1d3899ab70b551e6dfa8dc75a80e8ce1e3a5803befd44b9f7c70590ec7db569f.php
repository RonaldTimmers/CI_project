<?php

/* thumbsBasic_4_Template.html.twig */
class __TwigTemplate_05bd13f09194b8ef953be21a69af2468a6d094be3050c571c88c5515851605dd extends Twig_Template
{
    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        // line 2
        $this->parent = $this->loadTemplate("thumbsBasicTemplate.html.twig", "thumbsBasic_4_Template.html.twig", 2);
        $this->blocks = array(
            'thumbsSize' => array($this, 'block_thumbsSize'),
        );
    }

    protected function doGetParent(array $context)
    {
        return "thumbsBasicTemplate.html.twig";
    }

    protected function doDisplay(array $context, array $blocks = array())
    {
        $this->parent->display($context, array_merge($this->blocks, $blocks));
    }

    // line 4
    public function block_thumbsSize($context, array $blocks = array())
    {
        // line 5
        echo "    <div class=\"product-item col-lg-3 col-md-3 col-sm-3 col-ms-4 col-xs-6\">
";
    }

    public function getTemplateName()
    {
        return "thumbsBasic_4_Template.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  31 => 5,  28 => 4,  11 => 2,);
    }

    /** @deprecated since 1.27 (to be removed in 2.0). Use getSourceContext() instead */
    public function getSource()
    {
        @trigger_error('The '.__METHOD__.' method is deprecated since version 1.27 and will be removed in 2.0. Use getSourceContext() instead.', E_USER_DEPRECATED);

        return $this->getSourceContext()->getCode();
    }

    public function getSourceContext()
    {
        return new Twig_Source("{# empty Twig template #}
{% extends \"thumbsBasicTemplate.html.twig\" %}

{% block thumbsSize %}
    <div class=\"product-item col-lg-3 col-md-3 col-sm-3 col-ms-4 col-xs-6\">
{% endblock %}
    
", "thumbsBasic_4_Template.html.twig", "/home/CI/public_html/_CI_Dev/templates/thumbsBasic_4_Template.html.twig");
    }
}
