<h3 class="page-product-heading">AdminTab</h3><div class="rte">
<form action="" method="post" id="comment-form">
<div class="form-group">
<label for="title">Title:</label>
<input type="text" name="title" id="title" class="form-control">
</div>
<div class="form-group">
<label for="image">Image Path:</label>
<input type="text" name="image" id="image" class="form-control">
</div>
<div class="form-group">
<label for="assoc_link">Associated Link:</label>
<input type="text" name="assoc_link" id="assoc_link" class="form-control">
</div>
<div class="panel-footer">
<a href="{$link->getAdminLink('AdminProducts')}" class="btn btn-default"><i class="process-icon-cancel"></i> {l s='Cancel'}</a>
<button type="submit" name="submitAddproduct" class="btn btn-default pull-right"><i class="process-icon-save"></i> {l s='Save'}</button>
<button type="submit" name="submitAddproductAndStay" class="btn btn-default pull-right"><i class="process-icon-save"></i> {l s='Save and stay'}</button>
</div>
</form>
</div>