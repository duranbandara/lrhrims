<script src="https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>

<div class="row justify-content-center">
    <div class="col-md-9">
        <div class="card shadow-sm border-bottom-primary">
            <div class="card-header bg-white py-3">
                <div class="row">
                    <div class="col">
                        <h4 class="h5 align-middle m-0 font-weight-bold text-primary">Receive Reagent Form</h4>
                    </div>
                    <div class="col-auto">
                        <a href="<?= base_url(is_admin() ? 'incomingitems' : 'dashboard') ?>" class="btn btn-sm btn-secondary btn-icon-split">
                            <span class="icon"><i class="fa fa-arrow-left"></i></span>
                            <span class="text">Back</span>
                        </a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <?php if (isset($validation)): ?>
                    <div class="alert alert-danger"><?= $validation->listErrors() ?></div>
                <?php endif; ?>

                <?= form_open('incomingitems/add') ?>
                <input type="hidden" name="id_item_in" value="<?= esc($id_item_in) ?>">
                <input type="hidden" name="user_id" value="<?= session()->get('user_id') ?>">
                <input type="hidden" name="item_id" id="item_id" value="<?= old('item_id') ?>">

                <!-- Item Code / QR entry -->
                <div class="row form-group align-items-start">
                    <label class="col-md-3 text-md-right pt-2">Item Code / QR <span class="text-danger">*</span></label>
                    <div class="col-md-5">
                        <div class="input-group">
                            <input type="text" id="item_code_input" class="form-control"
                                   placeholder="Scan or type item code…" autocomplete="off">
                            <div class="input-group-append">
                                <button type="button" class="btn btn-outline-primary" id="btn_open_camera" title="Open camera">
                                    <i class="fas fa-qrcode"></i>
                                </button>
                            </div>
                        </div>
                        <small class="text-muted">Supports GS1-128 barcodes or plain item code</small>
                    </div>
                    <div class="col-md-4">
                        <div id="qr-reader" style="display:none; width:100%; max-width:300px;"></div>
                        <div id="scan_result"></div>
                    </div>
                </div>

                <!-- Manual dropdown -->
                <div class="row form-group">
                    <label class="col-md-3 text-md-right">
                        <small class="text-muted d-block">Can't find reagent?</small>
                        <small class="text-muted">Select manually:</small>
                    </label>
                    <div class="col-md-6">
                        <div class="input-group">
                            <select id="item_select" class="custom-select">
                                <option value="">— Select reagent from list —</option>
                                <?php foreach ($items as $b): ?>
                                    <option value="<?= esc($b['id_item']) ?>"
                                            data-name="<?= esc($b['des_item']) ?>"
                                            data-code="<?= esc($b['item_code'] ?? '') ?>"
                                            data-stock="<?= $b['stock'] ?>"
                                            data-unit="<?= esc($b['des_unit'] ?? '') ?>">
                                        <?= esc($b['des_item']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <?php if (is_admin()): ?>
                            <div class="input-group-append">
                                <a class="btn btn-outline-secondary" href="<?= base_url('goods/add') ?>"><i class="fa fa-plus"></i></a>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <!-- Reagent Name -->
                <div class="row form-group">
                    <label class="col-md-3 text-md-right">Reagent Name</label>
                    <div class="col-md-6">
                        <input type="text" id="reagent_name" class="form-control bg-light" readonly placeholder="Auto-filled…">
                    </div>
                </div>

                <!-- Lot Number -->
                <div class="row form-group">
                    <label class="col-md-3 text-md-right col-form-label" for="lot_number">Lot Number <span class="text-danger">*</span></label>
                    <div class="col-md-5">
                        <input value="<?= old('lot_number') ?>" name="lot_number" id="lot_number" type="text" class="form-control" placeholder="e.g. LOT240001">
                    </div>
                </div>

                <!-- Expiry Date -->
                <div class="row form-group">
                    <label class="col-md-3 text-md-right col-form-label" for="expiry_date">Expiry Date <span class="text-danger">*</span></label>
                    <div class="col-md-5">
                        <input value="<?= old('expiry_date') ?>" name="expiry_date" id="expiry_date" type="text" class="form-control date" placeholder="YYYY-MM-DD">
                    </div>
                </div>

                <!-- Stock & Qty -->
                <div class="row form-group">
                    <label class="col-md-3 text-md-right col-form-label">Current Stock</label>
                    <div class="col-md-3">
                        <div class="input-group">
                            <input type="number" id="current_stock" class="form-control bg-light" readonly placeholder="—">
                            <div class="input-group-append"><span class="input-group-text" id="unit_display">Unit</span></div>
                        </div>
                    </div>
                </div>

                <!-- Qty Received -->
                <div class="row form-group">
                    <label class="col-md-3 text-md-right col-form-label" for="amount_in">Qty Received <span class="text-danger">*</span></label>
                    <div class="col-md-5">
                        <div class="input-group">
                            <input value="<?= old('amount_in') ?>" name="amount_in" id="amount_in" type="number" min="1" step="1" class="form-control" placeholder="Enter quantity">
                            <div class="input-group-append"><span class="input-group-text" id="unit_label">Unit</span></div>
                        </div>
                    </div>
                </div>

                <hr>
                <h6 class="font-weight-bold text-secondary mb-3"><i class="fas fa-receipt mr-1"></i> Transaction Details</h6>

                <div class="row form-group">
                    <label class="col-md-3 text-md-right" for="date_in">Date Received <span class="text-danger">*</span></label>
                    <div class="col-md-4">
                        <input value="<?= old('date_in', date('Y-m-d')) ?>" name="date_in" id="date_in" type="date" class="form-control">
                    </div>
                </div>

                <div class="row form-group">
                    <label class="col-md-3 text-md-right" for="supplier_id">Supplier</label>
                    <div class="col-md-6">
                        <div class="input-group">
                            <select name="supplier_id" id="supplier_id" class="custom-select">
                                <option value="">— No Supplier —</option>
                                <?php foreach ($supplier as $s): ?>
                                    <option value="<?= $s['id_supplier'] ?>" <?= old('supplier_id') == $s['id_supplier'] ? 'selected' : '' ?>>
                                        <?= esc($s['des_supplier']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <div class="input-group-append">
                                <a class="btn btn-primary" href="<?= base_url('supplier/add') ?>"><i class="fa fa-plus"></i></a>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row form-group">
                    <div class="col offset-md-3">
                        <button type="submit" class="btn btn-success"><i class="fas fa-save mr-1"></i> Save</button>
                        <button type="button" class="btn btn-danger" id="btn_reset">Reset</button>
                    </div>
                </div>
                <?= form_close() ?>
            </div>
        </div>
    </div>
</div>

<script>
var html5QrCode = null, scannerOpen = false, lookupTimer = null;
var GS = '\x1d';
var SCAN_URL = '<?= base_url('incomingitems/scan') ?>';

function parseGS1(raw) {
    var FIXED = {'00':18,'01':14,'02':14,'11':6,'12':6,'13':6,'15':6,'16':6,'17':6,'18':6,'19':6,'20':2,'41':13};
    var result = {gtin:null,lot:null,expiry:null}, i = 0;
    while (i < raw.length) {
        if (result.gtin && result.lot && result.expiry) break;
        if (raw[i] === GS) { i++; continue; }
        var ai2 = raw.substr(i,2);
        if (ai2==='01') { if (!result.gtin) result.gtin=raw.substr(i+2,14); i+=16; }
        else if (ai2==='17') {
            if (!result.expiry) {
                var v=raw.substr(i+2,6), yy=parseInt(v.substr(0,2),10), mm=v.substr(2,2), dd=v.substr(4,2);
                if(dd==='00') dd='01';
                result.expiry=(yy<30?2000+yy:1900+yy)+'-'+mm+'-'+dd;
            }
            i+=8;
        } else if (ai2==='10') {
            i+=2;
            if (!result.lot) {
                var start=i, gsPos=raw.indexOf(GS,i);
                if (gsPos!==-1) { result.lot=raw.substr(start,gsPos-start); i=gsPos; }
                else {
                    var end=raw.length;
                    for(var j=i;j<raw.length-1;j++){var c=raw.substr(j,2);if(/^\d{2}$/.test(c)&&FIXED[c]!==undefined){end=j;break;}}
                    result.lot=raw.substr(start,end-start); i=end;
                }
            } else { var gp=raw.indexOf(GS,i); i=gp!==-1?gp:raw.length; }
        } else if (FIXED[ai2]!==undefined) { i+=2+FIXED[ai2]; } else { i++; }
    }
    return result;
}
function isGS1(v) { return v.indexOf(GS)!==-1||(/^01\d{14}/.test(v)&&v.length>20); }

document.getElementById('btn_open_camera').addEventListener('click', function() {
    if (scannerOpen) { stopCamera(); return; }
    document.getElementById('qr-reader').style.display='block';
    this.innerHTML='<i class="fas fa-times"></i>'; this.title='Close camera';
    html5QrCode=new Html5Qrcode("qr-reader");
    html5QrCode.start({facingMode:"environment"},{fps:10,qrbox:{width:220,height:220}},
        function(t){stopCamera();document.getElementById('item_code_input').value=t;processInput(t);},
        function(){}
    ).catch(function(e){showScanResult('danger','Camera error: '+e);stopCamera();});
    scannerOpen=true;
});
function stopCamera(){if(html5QrCode&&scannerOpen)html5QrCode.stop().catch(function(){});document.getElementById('qr-reader').style.display='none';var b=document.getElementById('btn_open_camera');b.innerHTML='<i class="fas fa-qrcode"></i>';b.title='Open camera';scannerOpen=false;}

var inp=document.getElementById('item_code_input');
inp.addEventListener('keydown',function(e){if(e.key==='Enter'){e.preventDefault();clearTimeout(lookupTimer);var v=this.value.trim();if(v)processInput(v);}});
inp.addEventListener('input',function(){clearTimeout(lookupTimer);var v=this.value.trim();if(!v)return;var d=(isGS1(v)||v.indexOf('|')!==-1)?150:900;lookupTimer=setTimeout(function(){processInput(v);},d);});

document.getElementById('item_select').addEventListener('change',function(){
    var opt=this.options[this.selectedIndex]; if(!this.value)return;
    setFields({reagent_id:this.value,reagent_name:opt.dataset.name,item_code:opt.dataset.code,current_stock:opt.dataset.stock,unit:opt.dataset.unit,lot_number:'',expiry_date:''});
    showScanResult('info','<i class="fas fa-info-circle"></i> Reagent selected. Enter lot & expiry.');
    document.getElementById('lot_number').focus();
});

document.getElementById('btn_reset').addEventListener('click',function(){
    ['item_id','reagent_name','lot_number','expiry_date','current_stock','amount_in'].forEach(function(id){document.getElementById(id).value='';});
    document.getElementById('item_code_input').value='';
    document.getElementById('item_select').value='';
    document.getElementById('unit_display').textContent='Unit';
    document.getElementById('unit_label').textContent='Unit';
    document.getElementById('scan_result').innerHTML='';
    document.getElementById('date_in').value = TODAY_DATE;
});

var TODAY_DATE = '<?= date('Y-m-d') ?>';
document.getElementById('date_in').value = document.getElementById('date_in').value || TODAY_DATE;

function processInput(v){ if(isGS1(v))processGS1(v); else if(v.indexOf('|')!==-1)doPost(v); else doPost(v); }

function processGS1(raw){
    showScanResult('info','<i class="fas fa-spinner fa-spin"></i> Parsing GS1…');
    var p=parseGS1(raw);
    if(!p.gtin){showScanResult('danger','Could not read GTIN from barcode.');return;}
    doPost(p.gtin,p.lot,p.expiry);
}

function doPost(qr,lot,expiry){
    showScanResult('info','<i class="fas fa-spinner fa-spin"></i> Looking up reagent…');
    $.post(SCAN_URL,{qr:qr},function(d){
        if(d.success){
            if(lot) d.lot_number=lot;
            if(expiry) d.expiry_date=expiry;
            setFields(d);
            var msg='<i class="fas fa-check-circle"></i> Found: <strong>'+d.reagent_name+'</strong>';
            if(d.lot_number) msg+=' — Lot: '+d.lot_number;
            if(d.expiry_date) msg+=' — Exp: '+d.expiry_date;
            showScanResult('success',msg);
            document.getElementById('amount_in').focus();
        } else {
            showScanResult('danger','<i class="fas fa-times-circle"></i> '+d.message);
        }
    },'json').fail(function(){showScanResult('danger','Server error.');});
}

function setFields(d){
    if(d.reagent_id)  document.getElementById('item_id').value=d.reagent_id;
    if(d.reagent_name)document.getElementById('reagent_name').value=d.reagent_name;
    if(d.item_code)   document.getElementById('item_code_input').value=d.item_code;
    if(d.current_stock!==undefined)document.getElementById('current_stock').value=d.current_stock;
    var u=d.unit||'Unit';document.getElementById('unit_display').textContent=u;document.getElementById('unit_label').textContent=u;
    if(d.lot_number)  document.getElementById('lot_number').value=d.lot_number;
    if(d.expiry_date) document.getElementById('expiry_date').value=d.expiry_date;
    var sel=document.getElementById('item_select');
    for(var i=0;i<sel.options.length;i++){if(sel.options[i].value===d.reagent_id){sel.selectedIndex=i;break;}}
}

function showScanResult(t,m){document.getElementById('scan_result').innerHTML='<div class="alert alert-'+t+' py-1 px-2 mb-0 small">'+m+'</div>';}
</script>
