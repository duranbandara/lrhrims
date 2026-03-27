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
                            <option value="<?= $sec['id_section'] ?>" <?= old('section_id', $default_section_id) == $sec['id_section'] ? 'selected' : '' ?>>
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

// ── GS1-128 parser (same logic as receive form) ──────────────────────────────
function parseGS1(raw){
    var FIXED={'00':18,'01':14,'02':14,'11':6,'12':6,'13':6,'15':6,'16':6,'17':6,'18':6,'19':6,'20':2,'41':13};
    var result={gtin:null,lot:null,expiry:null},i=0;
    while(i<raw.length){
        if(result.gtin&&result.lot&&result.expiry)break;
        if(raw[i]===GS){i++;continue;}
        var ai2=raw.substr(i,2);
        if(ai2==='01'){if(!result.gtin)result.gtin=raw.substr(i+2,14);i+=16;}
        else if(ai2==='17'){
            if(!result.expiry){var v=raw.substr(i+2,6),yy=parseInt(v.substr(0,2),10),mm=v.substr(2,2),dd=v.substr(4,2);if(dd==='00')dd='01';result.expiry=(yy<30?2000+yy:1900+yy)+'-'+mm+'-'+dd;}
            i+=8;
        }else if(ai2==='10'){
            i+=2;
            if(!result.lot){var start=i,gsPos=raw.indexOf(GS,i);
                if(gsPos!==-1){result.lot=raw.substr(start,gsPos-start);i=gsPos;}
                else{var end=raw.length;for(var j=i;j<raw.length-1;j++){var c=raw.substr(j,2);if(/^\d{2}$/.test(c)&&FIXED[c]!==undefined){end=j;break;}}result.lot=raw.substr(start,end-start);i=end;}
            }else{var gp=raw.indexOf(GS,i);i=gp!==-1?gp:raw.length;}
        }else if(FIXED[ai2]!==undefined){i+=2+FIXED[ai2];}else{i++;}
    }
    return result;
}
function isGS1(v){return v.indexOf(GS)!==-1||(/^01\d{14}/.test(v)&&v.length>20);}

function processInput(v){
    if(isGS1(v)){
        showScanResult('info','<i class="fas fa-spinner fa-spin"></i> Parsing GS1…');
        var p=parseGS1(v);
        if(!p.gtin){showScanResult('danger','Could not read GTIN from barcode.');return;}
        doLookup(p.gtin);
    } else {
        doLookup(v);
    }
}
// ─────────────────────────────────────────────────────────────────────────────

document.getElementById('btn_open_camera').addEventListener('click',function(){
    if(scannerOpen){stopCamera();return;}
    if(location.protocol!=='https:'&&location.hostname!=='localhost'&&location.hostname!=='127.0.0.1'){
        showScanResult('danger','<i class="fas fa-lock"></i> Camera requires a secure connection (HTTPS). Please access this page via HTTPS.');
        return;
    }
    document.getElementById('qr-reader').style.display='block';
    this.innerHTML='<i class="fas fa-times"></i>';
    html5QrCode=new Html5Qrcode("qr-reader");
    html5QrCode.start({facingMode:"environment"},{fps:10,qrbox:{width:220,height:220}},
        function(t){stopCamera();document.getElementById('item_code_input').value=t;processInput(t);},function(){}
    ).catch(function(e){showScanResult('danger','Camera: '+e);stopCamera();});
    scannerOpen=true;
});
function stopCamera(){if(html5QrCode&&scannerOpen)html5QrCode.stop().catch(function(){});document.getElementById('qr-reader').style.display='none';document.getElementById('btn_open_camera').innerHTML='<i class="fas fa-qrcode"></i>';scannerOpen=false;}

var inp=document.getElementById('item_code_input');
inp.addEventListener('keydown',function(e){if(e.key==='Enter'){e.preventDefault();clearTimeout(lookupTimer);var v=this.value.trim();if(v)processInput(v);}});
inp.addEventListener('input',function(){clearTimeout(lookupTimer);var v=this.value.trim();if(!v)return;var d=(isGS1(v)||v.indexOf('|')!==-1)?150:600;lookupTimer=setTimeout(function(){processInput(v);},d);});

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
    sel.selectedIndex=1;sel.dispatchEvent(new Event('change'));
}

function showScanResult(t,m){document.getElementById('scan_result').innerHTML='<div class="alert alert-'+t+' py-1 px-2 mb-0 small">'+m+'</div>';}

// Pre-fill if validation failed
<?php if (old('item_id')): ?>
doLookup('<?= esc(old('item_id')) ?>');
<?php endif; ?>
</script>
