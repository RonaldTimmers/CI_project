<?php

/* searchExtraNoticeTemplate.html.twig */
class __TwigTemplate_772161cc35b8933ae9b40b4cc2b2ce75ffa8ef805f87893f0aa3d3df49c9846c extends Twig_Template
{
    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        $this->parent = false;

        $this->blocks = array(
        );
    }

    protected function doDisplay(array $context, array $blocks = array())
    {
        // line 2
        echo "
";
        // line 3
        if (((isset($context["secondQuery"]) ? $context["secondQuery"] : null) == "less-strict")) {
            // line 4
            echo "    <div id=\"search-less-strict\" class=\"alert alert-warning search-warning\" role=\"alert\">
        <b>Notice:</b> Because we could not find enough results, the search engine give less strict results
    </div>
";
        }
        // line 8
        echo "
";
        // line 9
        if (((isset($context["secondQuery"]) ? $context["secondQuery"] : null) == "multiple-words")) {
            // line 10
            echo "    <div id=\"search-no-result\" class=\"alert alert-warning search-warning text-center\" role=\"alert\">
        <strong>Sorry, despite we are working hard on the total amount of products we did not found anything.</strong><br />
        <br />


        Some Tips:<br />
        - Try your search with fewer keywords. <br />
        - Put the most important keywords first. <br />
        - Keep in mind that the engine works only with the English language. 
    </div>
";
        }
    }

    public function getTemplateName()
    {
        return "searchExtraNoticeTemplate.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  35 => 10,  33 => 9,  30 => 8,  24 => 4,  22 => 3,  19 => 2,);
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

{% if secondQuery == 'less-strict' %}
    <div id=\"search-less-strict\" class=\"alert alert-warning search-warning\" role=\"alert\">
        <b>Notice:</b> Because we could not find enough results, the search engine give less strict results
    </div>
{% endif %}

{% if secondQuery == 'multiple-words' %}
    <div id=\"search-no-result\" class=\"alert alert-warning search-warning text-center\" role=\"alert\">
        <strong>Sorry, despite we are working hard on the total amount of products we did not found anything.</strong><br />
        <br />


        Some Tips:<br />
        - Try your search with fewer keywords. <br />
        - Put the most important keywords first. <br />
        - Keep in mind that the engine works only with the English language. 
    </div>
{% endif %}", "searchExtraNoticeTemplate.html.twig", "/home/CI/public_html/_CI_Dev/templates/searchExtraNoticeTemplate.html.twig");
    }
}
