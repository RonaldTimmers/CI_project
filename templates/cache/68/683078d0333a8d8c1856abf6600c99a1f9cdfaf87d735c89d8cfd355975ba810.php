<?php

/* thumbsBasicTemplate.html */
class __TwigTemplate_755716d58660ebcb8847c92f22a86bffb2ae6ed77636e6f3fabc02094ba92423 extends Twig_Template
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
        echo "<!DOCTYPE html>
<div>TODO write content</div>

";
    }

    public function getTemplateName()
    {
        return "thumbsBasicTemplate.html";
    }

    public function getDebugInfo()
    {
        return array (  19 => 1,);
    }

    /** @deprecated since 1.27 (to be removed in 2.0). Use getSourceContext() instead */
    public function getSource()
    {
        @trigger_error('The '.__METHOD__.' method is deprecated since version 1.27 and will be removed in 2.0. Use getSourceContext() instead.', E_USER_DEPRECATED);

        return $this->getSourceContext()->getCode();
    }

    public function getSourceContext()
    {
        return new Twig_Source("", "thumbsBasicTemplate.html", "/home/CI/public_html/_CI_Dev/templates/thumbsBasicTemplate.html");
    }
}
