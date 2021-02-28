</section>
<footer id="main-footer"></footer>
<?php $this->part('templates'); ?>
<?php $this->bodyEnd(); ?>
<iframe id="require-authentication" src="" style="display:none; position:fixed; top:0%; left:0%; width:100%; height:100%; z-index:100000"></iframe>

<?php if ($this->isEditable()): ?>
    <div id="editbox-human-crop" class="js-editbox" title="<?php \MapasCulturais\i::esc_attr_e("Recortar imagem");?>">
        <img id="human-crop-image"/>
    </div>
<?php endif; ?>
<!-- <div vw class="enabled">
    <div vw-access-button class="active"></div>
    <div vw-plugin-wrapper>
      <div class="vw-plugin-top-wrapper"></div>
    </div>
  </div>
  <script src="https://vlibras.gov.br/app/vlibras-plugin.js"></script>
  <script>
    new window.VLibras.Widget('https://vlibras.gov.br/app');
  </script> -->
</body>
</html>
