<?php if ($errors->has($name)): ?>
<span class="help-block"><?= e($errors->first($name)) ?></span>
<?php endif; ?>