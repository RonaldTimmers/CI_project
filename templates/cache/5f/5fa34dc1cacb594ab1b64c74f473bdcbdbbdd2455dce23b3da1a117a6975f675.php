<?php

/* thumbsDiscountTemplate.html.twig */
class __TwigTemplate_f164a34db634ef0a680936c7956a6b8bbf6a7b3de2b5cf687d7f77c8d4d8485a extends Twig_Template
{
    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        // line 2
        $this->parent = $this->loadTemplate("thumbsBasicTemplate.html.twig", "thumbsDiscountTemplate.html.twig", 2);
        $this->blocks = array(
            'thumbsSize' => array($this, 'block_thumbsSize'),
            'thumbsImage' => array($this, 'block_thumbsImage'),
            'inner' => array($this, 'block_inner'),
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
        echo "    <div class=\"product-item col-xs-12\">
";
    }

    // line 8
    public function block_thumbsImage($context, array $blocks = array())
    {
        // line 9
        echo "    <img class=\"product-thumb img-responsive img-rounded center-block hide-no-js\" src=\"";
        echo twig_escape_filter($this->env, (isset($context["STATIC_URL"]) ? $context["STATIC_URL"] : null), "html", null, true);
        echo twig_escape_filter($this->env, $this->getAttribute((isset($context["thumb"]) ? $context["thumb"] : null), "thumb_path", array(), "array"), "html", null, true);
        echo "\" alt=\"";
        echo twig_escape_filter($this->env, $this->getAttribute((isset($context["thumb"]) ? $context["thumb"] : null), "title", array(), "array"));
        echo "\"  />
";
    }

    // line 12
    public function block_inner($context, array $blocks = array())
    {
        // line 13
        echo "    
    <span class=\"discount down\">";
        // line 14
        echo twig_escape_filter($this->env, (twig_round(((1 - ($this->getAttribute((isset($context["thumb"]) ? $context["thumb"] : null), "price", array(), "array") / ($this->getAttribute((isset($context["thumb"]) ? $context["thumb"] : null), "price", array(), "array") - ($this->getAttribute((isset($context["thumb"]) ? $context["thumb"] : null), "price", array(), "array") - $this->getAttribute((isset($context["thumb"]) ? $context["thumb"] : null), "prev_price", array(), "array"))))) * 100)) . "%"), "html", null, true);
        echo "</span>
    <div class=\"product-inner\">
        
        ";
        // line 18
        echo "        <a  data-pushstate=\"1\" data-target=\"#product-modal\" data-remote=\"false\" data-id=\"";
        echo twig_escape_filter($this->env, $this->getAttribute((isset($context["thumb"]) ? $context["thumb"] : null), "id", array(), "array"), "html", null, true);
        echo "\"
            href=\"";
        // line 19
        echo twig_escape_filter($this->env, (isset($context["BASE_URL"]) ? $context["BASE_URL"] : null), "html", null, true);
        echo "sku/";
        echo twig_escape_filter($this->env, $this->getAttribute((isset($context["thumb"]) ? $context["thumb"] : null), "id", array(), "array"), "html", null, true);
        echo "-";
        echo twig_escape_filter($this->env, $this->getAttribute((isset($context["thumb"]) ? $context["thumb"] : null), "title_url", array(), "array"), "html", null, true);
        echo "/\" 
            title=\"";
        // line 20
        echo twig_escape_filter($this->env, $this->getAttribute((isset($context["thumb"]) ? $context["thumb"] : null), "title", array(), "array"));
        echo "\" 
            class=\"";
        // line 21
        echo twig_escape_filter($this->env, (isset($context["isModalLink"]) ? $context["isModalLink"] : null), "html", null, true);
        echo "\">   
            
        <span class=\"product-title center-block\">";
        // line 23
        echo twig_escape_filter($this->env, $this->getAttribute((isset($context["thumb"]) ? $context["thumb"] : null), "title", array(), "array"), "html", null, true);
        echo "</span>
            <span>
                <span class=\"product-price\">\$";
        // line 25
        echo twig_escape_filter($this->env, twig_number_format_filter($this->env, $this->getAttribute((isset($context["thumb"]) ? $context["thumb"] : null), "price", array(), "array"), 2, ".", ""), "html", null, true);
        echo "</span>
                <span class=\"product-listprice product-history-listprice\">Before: \$";
        // line 26
        echo twig_escape_filter($this->env, twig_number_format_filter($this->env, $this->getAttribute((isset($context["thumb"]) ? $context["thumb"] : null), "prev_price", array(), "array"), 2, ".", ""), "html", null, true);
        echo "</span>
            </span>

          <div class=\"product-source center-block row hidden-xs\">
                <div class=\"";
        // line 30
        echo twig_escape_filter($this->env, $this->getAttribute((isset($context["thumb"]) ? $context["thumb"] : null), "logo", array(), "array"), "html", null, true);
        echo "\"></div>       
            </div>
        </a>
                
";
    }

    public function getTemplateName()
    {
        return "thumbsDiscountTemplate.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  101 => 30,  94 => 26,  90 => 25,  85 => 23,  80 => 21,  76 => 20,  68 => 19,  63 => 18,  57 => 14,  54 => 13,  51 => 12,  41 => 9,  38 => 8,  33 => 5,  30 => 4,  11 => 2,);
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
    <div class=\"product-item col-xs-12\">
{% endblock %}
  
{% block thumbsImage %}
    <img class=\"product-thumb img-responsive img-rounded center-block hide-no-js\" src=\"{{ STATIC_URL }}{{ thumb['thumb_path'] }}\" alt=\"{{ thumb['title']|e }}\"  />
{% endblock %}
    
{% block inner %}
    
    <span class=\"discount down\">{{ \"#{ ((1 - ( thumb['price'] / ( thumb['price'] - ( thumb['price'] - thumb['prev_price'] )))) * 100 )|round }%\" }}</span>
    <div class=\"product-inner\">
        
        {# <a href=\"{{ BASE_URL }}goto/{{ thumb['id'] }}/\" rel=\"nofollow\" target=\"_blank\"> #}
        <a  data-pushstate=\"1\" data-target=\"#product-modal\" data-remote=\"false\" data-id=\"{{ thumb['id'] }}\"
            href=\"{{ BASE_URL }}sku/{{ thumb['id'] }}-{{ thumb['title_url'] }}/\" 
            title=\"{{ thumb['title']|e }}\" 
            class=\"{{ isModalLink }}\">   
            
        <span class=\"product-title center-block\">{{ thumb['title'] }}</span>
            <span>
                <span class=\"product-price\">\${{ thumb['price']|number_format(2, '.', '') }}</span>
                <span class=\"product-listprice product-history-listprice\">Before: \${{ thumb['prev_price']|number_format(2, '.', '') }}</span>
            </span>

          <div class=\"product-source center-block row hidden-xs\">
                <div class=\"{{ thumb['logo'] }}\"></div>       
            </div>
        </a>
                
{% endblock %}
", "thumbsDiscountTemplate.html.twig", "/home/CI/public_html/_CI_Dev/templates/thumbsDiscountTemplate.html.twig");
    }
}
