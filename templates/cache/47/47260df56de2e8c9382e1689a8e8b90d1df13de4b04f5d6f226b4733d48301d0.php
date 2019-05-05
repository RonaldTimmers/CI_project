<?php

/* searchMultipleWordsTemplate.html.twig */
class __TwigTemplate_cda2aae9e0bde58174a0a22150175e8eabb991dc888548e865a1118ddb9288a5 extends Twig_Template
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
        // line 1
        echo "
";
        // line 2
        $context['_parent'] = $context;
        $context['_seq'] = twig_ensure_traversable((isset($context["searchResults"]) ? $context["searchResults"] : null));
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
        foreach ($context['_seq'] as $context["_key"] => $context["searchResult"]) {
            echo "    
  
    <hr />
    
    <div class=\"row keyword-suggestion-box\" style=\"margin-bottom: 20px;\">
    <span class=\"keyword-suggestions pull-left\" style=\"font-size: 1.2em;\">Results for: 
    
    ";
            // line 9
            $context['_parent'] = $context;
            $context['_seq'] = twig_ensure_traversable((isset($context["initialKeywords"]) ? $context["initialKeywords"] : null));
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
            foreach ($context['_seq'] as $context["_key"] => $context["initialKeyword"]) {
                // line 10
                echo "            ";
                if (($this->getAttribute((isset($context["initialKeywords"]) ? $context["initialKeywords"] : null), $this->getAttribute($context["loop"], "index0", array()), array(), "array") == $this->getAttribute($context["searchResult"], "searchKeywords", array()))) {
                    // line 11
                    echo "                <a href=\"";
                    echo twig_escape_filter($this->env, (isset($context["BASE_URL"]) ? $context["BASE_URL"] : null), "html", null, true);
                    echo "search/";
                    echo twig_escape_filter($this->env, $this->getAttribute((isset($context["initialKeywords"]) ? $context["initialKeywords"] : null), $this->getAttribute($context["loop"], "index0", array()), array(), "array"), "html", null, true);
                    echo "/\" title=\"Search for ";
                    echo twig_escape_filter($this->env, $this->getAttribute((isset($context["initialKeywords"]) ? $context["initialKeywords"] : null), $this->getAttribute($context["loop"], "index0", array()), array(), "array"), "html", null, true);
                    echo "\"><strong>";
                    echo twig_escape_filter($this->env, $this->getAttribute((isset($context["initialKeywords"]) ? $context["initialKeywords"] : null), $this->getAttribute($context["loop"], "index0", array()), array(), "array"), "html", null, true);
                    echo "</strong></a>
            ";
                } else {
                    // line 13
                    echo "                <del>";
                    echo twig_escape_filter($this->env, $context["initialKeyword"], "html", null, true);
                    echo "</del>
            ";
                }
                // line 14
                echo " 
        
    ";
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
            unset($context['_seq'], $context['_iterated'], $context['_key'], $context['initialKeyword'], $context['_parent'], $context['loop']);
            $context = array_intersect_key($context, $_parent) + $_parent;
            // line 17
            echo "    </span>

    
    ";
            // line 20
            if (($this->getAttribute($this->getAttribute($context["searchResult"], "searchQL", array()), "totalFound", array()) > 0)) {
                // line 21
                echo "        <span class=\"keyword-suggestions pull-right\" style=\"font-size: 1.2em;\">Total Results - <strong> ";
                echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute($context["searchResult"], "searchQL", array()), "totalFound", array()), "html", null, true);
                echo " </strong></span>
    ";
            }
            // line 23
            echo "    </div>
    
    
    ";
            // line 26
            if ($this->getAttribute($context["searchResult"], "thumbs", array())) {
                // line 27
                echo "         
        ";
                // line 28
                echo twig_include($this->env, $context, "thumbsBasic_4_Template.html.twig", array("thumbs" => $this->getAttribute($context["searchResult"], "thumbs", array())));
                echo "

    ";
            } else {
                // line 31
                echo "        
        <div class=\"alert alert-danger\" role=\"alert\">
            <span>We did not found any results for this keyword...</span>
        </div>
        
    ";
            }
            // line 37
            echo "    
     

    <div class=\"clearfix\"></div>

";
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
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['searchResult'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
    }

    public function getTemplateName()
    {
        return "searchMultipleWordsTemplate.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  138 => 37,  130 => 31,  124 => 28,  121 => 27,  119 => 26,  114 => 23,  108 => 21,  106 => 20,  101 => 17,  85 => 14,  79 => 13,  67 => 11,  64 => 10,  47 => 9,  22 => 2,  19 => 1,);
    }

    /** @deprecated since 1.27 (to be removed in 2.0). Use getSourceContext() instead */
    public function getSource()
    {
        @trigger_error('The '.__METHOD__.' method is deprecated since version 1.27 and will be removed in 2.0. Use getSourceContext() instead.', E_USER_DEPRECATED);

        return $this->getSourceContext()->getCode();
    }

    public function getSourceContext()
    {
        return new Twig_Source("
{% for searchResult in searchResults %}    
  
    <hr />
    
    <div class=\"row keyword-suggestion-box\" style=\"margin-bottom: 20px;\">
    <span class=\"keyword-suggestions pull-left\" style=\"font-size: 1.2em;\">Results for: 
    
    {% for initialKeyword in initialKeywords %}
            {% if initialKeywords[loop.index0] == searchResult.searchKeywords %}
                <a href=\"{{ BASE_URL }}search/{{ initialKeywords[loop.index0] }}/\" title=\"Search for {{ initialKeywords[loop.index0] }}\"><strong>{{ initialKeywords[loop.index0] }}</strong></a>
            {% else %}
                <del>{{ initialKeyword }}</del>
            {% endif %} 
        
    {% endfor %}
    </span>

    
    {% if searchResult.searchQL.totalFound > 0 %}
        <span class=\"keyword-suggestions pull-right\" style=\"font-size: 1.2em;\">Total Results - <strong> {{ searchResult.searchQL.totalFound }} </strong></span>
    {% endif %}
    </div>
    
    
    {% if searchResult.thumbs %}
         
        {{ include('thumbsBasic_4_Template.html.twig', {   'thumbs': searchResult.thumbs } ) }}

    {% else %}
        
        <div class=\"alert alert-danger\" role=\"alert\">
            <span>We did not found any results for this keyword...</span>
        </div>
        
    {% endif %}
    
     

    <div class=\"clearfix\"></div>

{% endfor %}", "searchMultipleWordsTemplate.html.twig", "/home/CI/public_html/_CI_Dev/templates/searchMultipleWordsTemplate.html.twig");
    }
}
