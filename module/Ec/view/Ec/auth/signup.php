<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<html>
  <head>
    <meta charset="EUC-JP">
    <title></title>
  </head>
  <body>
    <?php
    // put your code here
    echo 'hello zf2!';

    $title = 'signup form';
    $this->headTitle($title);

    $form = $this->form;
    echo $this->form()->openTag($form);
    echo $this->formRow($form->get('email'));
    echo $this->formRow($form->get('password'));
    echo $this->formHidden($form->get('csrf'));
    echo $this->formSubmit($form->get('submit'));
    echo $this->form()->closeTag($form);
    ?>
  </body>
</html>
