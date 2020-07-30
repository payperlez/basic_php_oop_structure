<?php

use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Extension\SandboxExtension;
use Twig\Markup;
use Twig\Sandbox\SecurityError;
use Twig\Sandbox\SecurityNotAllowedTagError;
use Twig\Sandbox\SecurityNotAllowedFilterError;
use Twig\Sandbox\SecurityNotAllowedFunctionError;
use Twig\Source;
use Twig\Template;

/* layout/layout.twig */
class __TwigTemplate_fcc316923e928f4de48357d740096ae794b7d8ea1f9897c7fb02a49d53117566 extends Template
{
    private $source;
    private $macros = [];

    public function __construct(Environment $env)
    {
        parent::__construct($env);

        $this->source = $this->getSourceContext();

        $this->parent = false;

        $this->blocks = [
            'content' => [$this, 'block_content'],
        ];
    }

    protected function doDisplay(array $context, array $blocks = [])
    {
        $macros = $this->macros;
        // line 1
        echo "<!DOCTYPE html>
<html>

<head>
  <meta charset=\"utf-8\">
  <meta name=\"viewport\" content=\"width=device-width, initial-scale=1, shrink-to-fit=no\">
  <meta name=\"description\" content=\"Start your development with a Dashboard for Bootstrap 4.\">
  <meta name=\"author\" content=\"Creative Tim\">
  <title>Blogpay - Work Online as a blogger and get paid</title>
  <!-- Favicon -->
  <link rel=\"icon\" href=\"assets/img/brand/logo.png\" type=\"image/png\">
  <!-- Fonts -->
  <link rel=\"stylesheet\" href=\"https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700\">
  <!-- Icons -->
  <link rel=\"stylesheet\" href=\"public/static/vendor/nucleo/css/nucleo.css\" type=\"text/css\">
  <link rel=\"stylesheet\" href=\"public/static/vendor/@fortawesome/fontawesome-free/css/all.min.css\" type=\"text/css\">
  <!-- Page plugins -->
  <!-- Argon CSS -->
  <link rel=\"stylesheet\" href=\"";
        // line 19
        echo twig_escape_filter($this->env, ($context["static"] ?? null), "html", null, true);
        echo "/css/argon.css?v=1.2.0\" type=\"text/css\">
</head>
<body class=\"";
        // line 21
        echo twig_escape_filter($this->env, ($context["bg_color"] ?? null), "html", null, true);
        echo "\"> 
 <!-- Main content -->
  <div class=\"main-content\">
    ";
        // line 24
        $this->displayBlock('content', $context, $blocks);
        // line 25
        echo "  </div>
</body>
<!-- Argon Scripts -->
  <!-- Core -->
  <script src=\"public/static/vendor/jquery/dist/jquery.min.js\"></script>
  <script src=\"public/static/vendor/bootstrap/dist/js/bootstrap.bundle.min.js\"></script>
  <script src=\"public/static/vendor/js-cookie/js.cookie.js\"></script>
  <script src=\"public/static/vendor/jquery.scrollbar/jquery.scrollbar.min.js\"></script>
  <script src=\"public/static/vendor/jquery-scroll-lock/dist/jquery-scrollLock.min.js\"></script>
  <!-- Argon JS -->
  <script src=\"";
        // line 35
        echo twig_escape_filter($this->env, ($context["static"] ?? null), "html", null, true);
        echo "/js/argon.js?v=1.2.0\"></script>
</body>

</html>";
    }

    // line 24
    public function block_content($context, array $blocks = [])
    {
        $macros = $this->macros;
    }

    public function getTemplateName()
    {
        return "layout/layout.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  91 => 24,  83 => 35,  71 => 25,  69 => 24,  63 => 21,  58 => 19,  38 => 1,);
    }

    public function getSourceContext()
    {
        return new Source("", "layout/layout.twig", "/Users/pefum/webroot/myblogpay/public/views/layout/layout.twig");
    }
}
