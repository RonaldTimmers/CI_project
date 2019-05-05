<?php

/* thumbsBasicTemplate.html.twig */
class __TwigTemplate_85c9624c55c0bc7bb79c5c11e4a59fca8eb35f41fa4aa3b8b9c5e2bca7405cac extends Twig_Template
{
    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        $this->parent = false;

        $this->blocks = array(
            'thumbsSize' => array($this, 'block_thumbsSize'),
            'thumbsImage' => array($this, 'block_thumbsImage'),
            'inner' => array($this, 'block_inner'),
        );
    }

    protected function doDisplay(array $context, array $blocks = array())
    {
        // line 2
        echo "<!DOCTYPE html>
";
        // line 3
        $context['_parent'] = $context;
        $context['_seq'] = twig_ensure_traversable((isset($context["thumbs"]) ? $context["thumbs"] : null));
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
        foreach ($context['_seq'] as $context["_key"] => $context["thumb"]) {
            // line 4
            echo "        
        ";
            // line 5
            $this->displayBlock('thumbsSize', $context, $blocks);
            // line 8
            echo "        
            ";
            // line 10
            echo "            <a  data-pushstate=\"1\" data-target=\"#product-modal\" data-remote=\"false\" data-id=\"";
            echo twig_escape_filter($this->env, $this->getAttribute($context["thumb"], "id", array(), "array"), "html", null, true);
            echo "\"
                href=\"";
            // line 11
            echo twig_escape_filter($this->env, (isset($context["BASE_URL"]) ? $context["BASE_URL"] : null), "html", null, true);
            echo "sku/";
            echo twig_escape_filter($this->env, $this->getAttribute($context["thumb"], "id", array(), "array"), "html", null, true);
            echo "-";
            echo twig_escape_filter($this->env, $this->getAttribute($context["thumb"], "title_url", array(), "array"), "html", null, true);
            echo "/\" 
                title=\"";
            // line 12
            echo twig_escape_filter($this->env, $this->getAttribute($context["thumb"], "title", array(), "array"));
            echo "\" 
                class=\"";
            // line 13
            echo twig_escape_filter($this->env, (isset($context["isModalLink"]) ? $context["isModalLink"] : null), "html", null, true);
            echo "\">  
            
                <div>
                    
                    ";
            // line 17
            $this->displayBlock('thumbsImage', $context, $blocks);
            // line 20
            echo "                    <noscript>
                        <style>.hide-no-js { display: none; }</style>
                        <img class=\"product-thumb img-responsive img-rounded center-block\" src=\"";
            // line 22
            echo twig_escape_filter($this->env, (isset($context["STATIC_URL"]) ? $context["STATIC_URL"] : null), "html", null, true);
            echo twig_escape_filter($this->env, $this->getAttribute($context["thumb"], "thumb_path", array(), "array"), "html", null, true);
            echo "\" alt=\"";
            echo twig_escape_filter($this->env, $this->getAttribute($context["thumb"], "title", array(), "array"), "html", null, true);
            echo "\" />
                    </noscript>
                </div>
                    
            </a>
            
            ";
            // line 28
            $this->displayBlock('inner', $context, $blocks);
            // line 75
            echo "                ";
            // line 88
            echo "            </div>
        </div>
    
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
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['thumb'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 92
        echo "
";
    }

    // line 5
    public function block_thumbsSize($context, array $blocks = array())
    {
        // line 6
        echo "            <div class=\"product-item col-lg-2 col-md-3 col-sm-3 col-ms-4 col-xs-6\">
        ";
    }

    // line 17
    public function block_thumbsImage($context, array $blocks = array())
    {
        // line 18
        echo "                        <img class=\"product-thumb img-responsive img-rounded center-block hide-no-js\" data-src=\"";
        echo twig_escape_filter($this->env, (isset($context["STATIC_URL"]) ? $context["STATIC_URL"] : null), "html", null, true);
        echo twig_escape_filter($this->env, $this->getAttribute((isset($context["thumb"]) ? $context["thumb"] : null), "thumb_path", array(), "array"), "html", null, true);
        echo "\" alt=\"";
        echo twig_escape_filter($this->env, $this->getAttribute((isset($context["thumb"]) ? $context["thumb"] : null), "title", array(), "array"));
        echo "\" src=\"/img/spinner.gif\" />
                    ";
    }

    // line 28
    public function block_inner($context, array $blocks = array())
    {
        // line 29
        echo "                    
            <div class=\"product-inner\">
                
                ";
        // line 33
        echo "            <a  data-pushstate=\"1\" data-target=\"#product-modal\" data-remote=\"false\" data-id=\"";
        echo twig_escape_filter($this->env, $this->getAttribute((isset($context["thumb"]) ? $context["thumb"] : null), "id", array(), "array"), "html", null, true);
        echo "\"
                href=\"";
        // line 34
        echo twig_escape_filter($this->env, (isset($context["BASE_URL"]) ? $context["BASE_URL"] : null), "html", null, true);
        echo "sku/";
        echo twig_escape_filter($this->env, $this->getAttribute((isset($context["thumb"]) ? $context["thumb"] : null), "id", array(), "array"), "html", null, true);
        echo "-";
        echo twig_escape_filter($this->env, $this->getAttribute((isset($context["thumb"]) ? $context["thumb"] : null), "title_url", array(), "array"), "html", null, true);
        echo "/\" 
                title=\"";
        // line 35
        echo twig_escape_filter($this->env, $this->getAttribute((isset($context["thumb"]) ? $context["thumb"] : null), "title", array(), "array"));
        echo "\" 
                class=\"";
        // line 36
        echo twig_escape_filter($this->env, (isset($context["isModalLink"]) ? $context["isModalLink"] : null), "html", null, true);
        echo "\">   
                
                <span class=\"product-title center-block\">";
        // line 38
        echo twig_escape_filter($this->env, $this->getAttribute((isset($context["thumb"]) ? $context["thumb"] : null), "title", array(), "array"), "html", null, true);
        echo "</span>
                <span class=\"product-price center-block\">\$";
        // line 39
        echo twig_escape_filter($this->env, twig_number_format_filter($this->env, $this->getAttribute((isset($context["thumb"]) ? $context["thumb"] : null), "price", array(), "array"), 2, ".", ""), "html", null, true);
        echo "</span>

                
                ";
        // line 42
        if ( !$this->getAttribute((isset($context["thumb"]) ? $context["thumb"] : null), "stock", array(), "array")) {
            // line 43
            echo "                   <span class=\"product-stock in-stock center-block\">Stock: 
                       <span aria-hidden=\"true\" class=\"glyphicon glyphicon-ok-sign\"></span>
                                    
                   </span>
                ";
        } else {
            // line 47
            echo "   
                   <span class=\"product-stock no-stock center-block\">Stock: 
                                <span aria-hidden=\"true\" class=\"glyphicon glyphicon-remove-sign\"></span>
                                    
                   </span>
                ";
        }
        // line 53
        echo "                
                
                ";
        // line 55
        if (((isset($context["isSimilairThumbs"]) ? $context["isSimilairThumbs"] : null) == false)) {
            // line 56
            echo "                    
                    <div class=\"row hidden-xs\">
                        <div class=\"product-stars col-xs-5\">
                            <span class=\"star0\">
                                <span class=\"star";
            // line 60
            echo twig_escape_filter($this->env, $this->getAttribute((isset($context["thumb"]) ? $context["thumb"] : null), "stars", array(), "array"), "html", null, true);
            echo "\"></span>
                            </span>
                        </div>
                    </div>
                    
               ";
        }
        // line 66
        echo "               

               
                  <div class=\"product-source center-block row hidden-xs\">
                        <div class=\"";
        // line 70
        echo twig_escape_filter($this->env, $this->getAttribute((isset($context["thumb"]) ? $context["thumb"] : null), "logo", array(), "array"), "html", null, true);
        echo "\"></div>       
                    </div>
                </a>
                    
            ";
    }

    public function getTemplateName()
    {
        return "thumbsBasicTemplate.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  223 => 70,  217 => 66,  208 => 60,  202 => 56,  200 => 55,  196 => 53,  188 => 47,  181 => 43,  179 => 42,  173 => 39,  169 => 38,  164 => 36,  160 => 35,  152 => 34,  147 => 33,  142 => 29,  139 => 28,  129 => 18,  126 => 17,  121 => 6,  118 => 5,  113 => 92,  96 => 88,  94 => 75,  92 => 28,  80 => 22,  76 => 20,  74 => 17,  67 => 13,  63 => 12,  55 => 11,  50 => 10,  47 => 8,  45 => 5,  42 => 4,  25 => 3,  22 => 2,);
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
<!DOCTYPE html>
{% for thumb in thumbs %}
        
        {% block thumbsSize %}
            <div class=\"product-item col-lg-2 col-md-3 col-sm-3 col-ms-4 col-xs-6\">
        {% endblock %}
        
            {# <a href=\"{{ BASE_URL }}goto/{{ thumb['id'] }}/\" rel=\"nofollow\" target=\"_blank\"> #}
            <a  data-pushstate=\"1\" data-target=\"#product-modal\" data-remote=\"false\" data-id=\"{{ thumb['id'] }}\"
                href=\"{{ BASE_URL }}sku/{{ thumb['id'] }}-{{ thumb['title_url'] }}/\" 
                title=\"{{ thumb['title']|e }}\" 
                class=\"{{ isModalLink }}\">  
            
                <div>
                    
                    {% block thumbsImage %}
                        <img class=\"product-thumb img-responsive img-rounded center-block hide-no-js\" data-src=\"{{ STATIC_URL }}{{ thumb['thumb_path'] }}\" alt=\"{{ thumb['title']|e }}\" src=\"/img/spinner.gif\" />
                    {% endblock %}
                    <noscript>
                        <style>.hide-no-js { display: none; }</style>
                        <img class=\"product-thumb img-responsive img-rounded center-block\" src=\"{{ STATIC_URL }}{{ thumb['thumb_path'] }}\" alt=\"{{ thumb['title'] }}\" />
                    </noscript>
                </div>
                    
            </a>
            
            {% block inner %}
                    
            <div class=\"product-inner\">
                
                {# <a href=\"{{ BASE_URL }}goto/{{ thumb['id'] }}/\" rel=\"nofollow\" target=\"_blank\"> #}
            <a  data-pushstate=\"1\" data-target=\"#product-modal\" data-remote=\"false\" data-id=\"{{ thumb['id'] }}\"
                href=\"{{ BASE_URL }}sku/{{ thumb['id'] }}-{{ thumb['title_url'] }}/\" 
                title=\"{{ thumb['title']|e }}\" 
                class=\"{{ isModalLink }}\">   
                
                <span class=\"product-title center-block\">{{ thumb['title'] }}</span>
                <span class=\"product-price center-block\">\${{ thumb['price']|number_format(2, '.', '') }}</span>

                
                {% if not thumb['stock'] %}
                   <span class=\"product-stock in-stock center-block\">Stock: 
                       <span aria-hidden=\"true\" class=\"glyphicon glyphicon-ok-sign\"></span>
                                    
                   </span>
                {% else %}   
                   <span class=\"product-stock no-stock center-block\">Stock: 
                                <span aria-hidden=\"true\" class=\"glyphicon glyphicon-remove-sign\"></span>
                                    
                   </span>
                {% endif %}
                
                
                {% if isSimilairThumbs == false %}
                    
                    <div class=\"row hidden-xs\">
                        <div class=\"product-stars col-xs-5\">
                            <span class=\"star0\">
                                <span class=\"star{{ thumb['stars'] }}\"></span>
                            </span>
                        </div>
                    </div>
                    
               {% endif %}
               

               
                  <div class=\"product-source center-block row hidden-xs\">
                        <div class=\"{{ thumb['logo'] }}\"></div>       
                    </div>
                </a>
                    
            {% endblock inner %}
                {# Removed on 23-3-18 -> Test if visitors stay longer
                
                <div class=\"product-hover-area row col-xs-12\">
                    <div class=\"product-CTA-button\">
                        <a  data-pushstate=\"1\" data-target=\"#product-modal\" data-remote=\"false\" data-id=\"{{ thumb['id'] }}\"
                            href=\"{{ BASE_URL }}sku/{{ thumb['id'] }}-{{ thumb['title_url'] }}/\" 
                            title=\"{{ thumb['title']|e }}\" 
                            class=\"btn btn-default btn-block {{ isModalLink }}\">See Details 
                            <span class=\"glyphicon glyphicon-search\" aria-hidden=\"true\"></span>
                        </a>
                    </div>
                </div>
                #}
            </div>
        </div>
    
{% endfor %}

", "thumbsBasicTemplate.html.twig", "/home/CI/public_html/_CI_Dev/templates/thumbsBasicTemplate.html.twig");
    }
}
