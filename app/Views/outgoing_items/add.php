<script src="https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>

<div class="row justify-content-center">
<div class="col-md-9">
    <div class="card shadow-sm border-bottom-primary">
        <div class="card-header bg-white py-3">
            <div class="row">
                <div class="col"><h4 class="h5 m-0 font-weight-bold text-primary">Issue Reagent to Lab Section</h4></div>
                <div class="col-auto">
                    <a href="<?= base_url(is_admin() ? 'outgoingitems' : 'dashboard') ?>" class="btn btn-sm btn-secondary btn-icon-split">
                        <span class="icon"><i class="fa fa-arrow-left"></i></span><span class="text">Back</span>
                    </a>
                </div>
            </div>
        </div>
        <div class="card-body">
            <?php if (isset($validation)): ?>
                <div class="alert alert-danger"><?= $validation->listErrors() ?></div>
            <?php endif; ?>

            <?= form_open('outgoingitems/add', ['id' => 'issueForm']) ?>
            <input type="hidden" name="id_item_out" value="<?= esc($id_item_out) ?>">
            <input type="hidden" name="user_id"     value="<?= session()->get('user_id') ?>">
            <input type="hidden" name="item_id"     id="item_id"    value="<?= old('item_id') ?>">
            <input type="hidden" name="lot_number"  id="lot_number" value="<?= old('lot_number') ?>">

            <!-- Item Code / QR -->
            <div class="row form-group align-items-start">
                <label class="col-md-3 text-md-right pt-2">Item Code / QR <span class="text-danger">*</span></label>
                <div class="col-md-5">
                    <div class="input-group">
                        <input type="text" id="item_code_input" class="form-control" placeholder="Scan or type item code…" autocomplete="off">
                        <div class="input-group-append">
                            <button type="button" class="btn btn-outline-primary" id="btn_open_camera">
                                <i class="fas fa-qrcode"></i>
                            </button>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div id="qr-reader" style="display:none; width:100%; max-width:300px;"></div>
                    <div id="scan_result"></div>
                </div>
            </div>

            <!-- Manual dropdown -->
            <div class="row form-group">
                <label class="col-md-3 text-md-right"><small class="text-muted">Select manually:</small></label>
                <div class="col-md-6">
                    <select id="item_select" class="custom-select">
                        <option value="">— Select reagent —</option>
                        <?php foreach ($items as $b): ?>
                        <option value="<?= esc($b['id_item']) ?>" data-name="<?= esc($b['des_item']) ?>"
                                data-code="<?= esc($b['item_code'] ?? '') ?>" data-stock="<?= $b['stock'] ?>"
                                data-unit="<?= esc($b['des_unit'] ?? '') ?>">
                            <?= esc($b['des_item']) ?>
                        </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <!-- Reagent Name -->
            <div class="row form-group">
                <label class="col-md-3 text-md-right">Reagent Name</label>
                <div class="col-md-6">
                    <input type="text" id="reagent_name" class="form-control bg-light" readonly placeholder="Auto-filled…">
                </div>
                <div class="col-md-3">
                    <small>Stock: <strong id="current_stock_display">—</strong> <span id="unit_display_text"></span></small>
                </div>
            </div>

            <!-- Lot Selection -->
            <div class="row form-group">
                <label class="col-md-3 text-md-right col-form-label">Select Lot (FEFO) <span class="text-danger">*</span></label>
                <div class="col-md-6">
                    <select id="lot_select" class="custom-select">
                        <option value="">— Select reagent first —</option>
                    </select>
                    <small class="text-muted">Lots ordered by expiry date (First Expiry, First Out)</small>
                </div>
                <div class="col-md-3">
                    <small>Lot stock: <strong id="lot_qty_display">—</strong></small>
                </div>
            </div>

            <!-- Qty -->
            <div class="row form-group">
                <label class="col-md-3 text-md-right col-form-label" for="amount_out">Quantity <span class="text-danger">*</span></label>
                <div class="col-md-3">
                    <div class="input-group">
                        <input value="<?= old('amount_out') ?>" name="amount_out" id="amount_out"
                               type="number" min="1" step="1" class="form-control" placeholder="Qty">
                        <div class="input-group-append"><span class="input-group-text" id="unit_label">Unit</span></div>
                    </div>
                    <small id="max_qty_hint" class="text-muted"></small>
                </div>
            </div>

            <hr>
            <h6 class="font-weight-bold text-secondary mb-3"><i class="fas fa-receipt mr-1"></i> Transaction Details</h6>

            <div class="row form-group">
                <label class="col-md-3 text-md-right" for="section_id">Lab Section <span class="text-danger">*</span></label>
                <div class="col-md-5">
                    <div class="input-group">
                        <select name="section_id" id="section_id" class="custom-select">
                            <option value="">— Select lab section —</option>
                            <?php foreach ($sections as $sec): ?>
                            <option value="<?= $sec['id_section'] ?>" <?= old('section_id') == $sec['id_section'] ? 'selected' : '' ?>>
                                <?= esc($sec['section_name']) ?>
                            </option>
                            <?php endforeach; ?>
                        </select>
                        <?php if (is_admin()): ?>
                        <div class="input-group-append">
                            <a class="btn btn-primary" href="<?= base_url('labsection/add') ?>"><i class="fa fa-plus"></i></a>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <div class="row form-group">
                <label class="col-md-3 text-md-right" for="date_out">Issue Date <span class="text-danger">*</span></label>
                <div class="col-md-4">
                    <input value="<?= old('date_out', date('Y-m-d')) ?>" name="date_out" id="date_out"
                           type="text" class="form-control date" placeholder="YYYY-MM-DD">
                </div>
            </div>

            <div class="row form-group">
                <div class="col offset-md-3">
                    <button type="submit" class="btn btn-success"><i class="fas fa-save mr-1"></i> Save</button>
                </div>
            </div>
            <?= form_close() ?>
        </div>
    </div>
</div>
</div>

<script>
var html5QrCode=null, scannerOpen=false, lookupTimer=null;
var GS='\x1d';
var SCAN_URL='<?= base_url('outgoingitems/scan') ?>';

document.getElementById('btn_open_camera').addEventListener('click',function(){
    if(scannerOpen){stopCamera();return;}
    document.getElementById('qr-reader').style.display='block';
    this.innerHTML='<i class="fas fa-times"></i>';
    html5QrCode=new Html5Qrcode("qr-reader");
    html5QrCode.start({facingMode:"environment"},{fps:10,qrbox:{width:220,height:220}},
        function(t){stopCamera();document.getElementById('item_code_input').value=t;doLookup(t);},function(){}
    ).catch(function(e){showScanResult('danger','Camera: '+e);stopCamera();});
    scannerOpen=true;
});
function stopCamera(){if(html5QrCode&&scannerOpen)html5QrCode.stop().catch(function(){});document.getElementById('qr-reader').style.display='none';document.getElementById('btn_open_camera').innerHTML='<i class="fas fa-qrcode"></i>';scannerOpen=false;}

var inp=document.getElementById('item_code_input');
inp.addEventListener('keydown',function(e){if(e.key==='Enter'){e.preventDefault();clearTimeout(lookupTimer);var v=this.value.trim();if(v)doLookup(v);}});
inp.addEventListener('input',function(){clearTimeout(lookupTimer);var v=this.value.trim();if(!v)return;lookupTimer=setTimeout(function(){doLookup(v);},600);});

document.getElementById('item_select').addEventListener('change',function(){
    if(!this.value)return;
    var opt=this.options[this.selectedIndex];
    document.getElementById('item_code_input').value=opt.dataset.code;
    doLookup(this.value);
});

document.getElementById('lot_select').addEventListener('change',function(){
    document.getElementById('lot_number').value=this.value;
    var opt=this.options[this.selectedIndex];
    var qty=opt.dataset.qty||'0';
    document.getElementById('lot_qty_display').textContent=qty;
    document.getElementById('amount_out').max=qty;
    document.getElementById('max_qty_hint').textContent='Max: '+qty+' available in this lot';
});

function doLookup(qr){
    showScanResult('info','<i class="fas fa-spinner fa-spin"></i> Looking up…');
    $.post(SCAN_URL,{qr:qr},function(d){
        if(d.success){
            document.getElementById('item_id').value=d.reagent_id;
            document.getElementById('reagent_name').value=d.reagent_name;
            document.getElementById('current_stock_display').textContent=d.current_stock;
            document.getElementById('unit_display_text').textContent=d.unit||'';
            document.getElementById('unit_label').textContent=d.unit||'Unit';
            var sel=document.getElementById('item_select');
            for(var i=0;i<sel.options.length;i++){if(sel.options[i].value===d.reagent_id){sel.selectedIndex=i;break;}}
            fillLots(d.lots);
            showScanResult('success','<i class="fas fa-check-circle"></i> Found: <strong>'+d.reagent_name+'</strong>');
        } else {
            showScanResult('danger','<i class="fas fa-times-circle"></i> '+d.message);
        }
    },'json').fail(function(){showScanResult('danger','Server error.');});
}

function fillLots(lots){
    var sel=document.getElementById('lot_select');
    sel.innerHTML='<option value="">— Select lot —</option>';
    if(!lots||lots.length===0){sel.innerHTML='<option value="">No lots available</option>';return;}
    lots.forEach(function(l){
        var opt=document.createElement('option');
        opt.value=l.lot_number;
        opt.dataset.qty=l.quantity;
        var d=new Date(l.expiry_date), today=new Date(), diff=Math.round((d-today)/86400000);
        var badge=diff<0?'EXPIRED':diff+'d left';
        opt.textContent=l.lot_number+' (Exp: '+l.expiry_date+') — qty: '+l.quantity+' ['+badge+']';
        sel.appendChild(opt);
    });
    if(lots.length===1){sel.selectedIndex=1;sel.dispatchEvent(new Event('change'));}
}

function showScanResult(t,m){document.getElementById('scan_result').innerHTML='<div class="alert alert-'+t+' py-1 px-2 mb-0 small">'+m+'</div>';}

// Pre-fill if validation failed
<?php if (old('item_id')): ?>
doLookup('<?= esc(old('item_id')) ?>');
<?php endif; ?>
</script>
