<?php

/* searchSuggestionsTemplate.html.twig */
class __TwigTemplate_b70393f72cbd303190c28b03bf3effbd4c6b6f103b2773efc4c5a2e6745fafbd extends Twig_Template
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
        echo "<div id=\"search-overview\" class=\"jumbotron\">
    <span id=\"search-suggestions\" style=\"font-size: 0.7em; font-weight: 400;\">Did you mean? 
        
        ";
        // line 5
        $context['_parent'] = $context;
        $context['_seq'] = twig_ensure_traversable((isset($context["keywordSuggestions"]) ? $context["keywordSuggestions"] : null));
        $context['loop'] = array(
          'parent' => $context['_parent'],
          'index0' => 0,
          'index'  => 1,
          'first'  => true,
        );
        if (is_array($context['_seq']) || (is_object($context['_seq']) && $context['_seq'] instanceof Countable)) {
            $length = count($context['_seq']);
            $context['loop']['revindex0'] = $length - 1;
            $context['loop']['revindex'] = $length;
            $context['loop']['length'] = $length;
            $context['loop']['last'] = 1 === $length;
        }
        foreach ($context['_seq'] as $context["_key"] => $context["suggestion"]) {
            // line 6
            echo "            <a href=\"";
            echo twig_escape_filter($this->env, (isset($context["BASE_URL"]) ? $context["BASE_URL"] : null), "html", null, true);
            echo "search/";
            echo twig_escape_filter($this->env, twig_urlencode_filter($this->getAttribute($context["suggestion"], "keyword", array(), "array")), "html", null, true);
            echo "/\">";
            echo twig_escape_filter($this->env, $this->getAttribute($context["suggestion"], "keyword", array(), "array"), "html", null, true);
            echo "</a>
            ";
            // line 7
            if ( !$this->getAttribute($context["loop"], "last", array())) {
                echo ",";
            }
            // line 8
            echo "        ";
            ++$context['loop']['index0'];
            ++$context['loop']['index'];
            $context['loop']['first'] = false;
            if (isset($context['loop']['length'])) {
                --$context['loop']['revindex0'];
                --$context['loop']['revindex'];
                $context['loop']['last'] = 0 === $context['loop']['revindex0'];
            }
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['suggestion'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 9
        echo "    </span>
</div>

";
    }

    public function getTemplateName()
    {
        return "searchSuggestionsTemplate.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  68 => 9,  54 => 8,  50 => 7,  41 => 6,  24 => 5,  19 => 2,);
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
<div id=\"search-overview\" class=\"jumbotron\">
    <span id=\"search-suggestions\" style=\"font-size: 0.7em; font-weight: 400;\">Did you mean? 
        
        {% for suggestion in keywordSuggestions %}
            <a href=\"{{ BASE_URL }}search/{{ suggestion['keyword']|url_encode  }}/\">{{ suggestion['keyword'] }}</a>
            {% if not loop.last %},{% endif %}
        {% endfor %}
    </span>
</div>

", "searchSuggestionsTemplate.html.twig", "/home/CI/public_html/_CI_Dev/templates/searchSuggestionsTemplate.html.twig");
    }
}
